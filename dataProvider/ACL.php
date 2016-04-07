<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class ACL {

	/**
	 * @var PDO
	 */
	private static $conn;
	/**
	 * @var array
	 */
	private static $perms = [];
	/**
	 * @var int
	 */
	private static $user_id;
	/**
	 * @var array
	 */
	private static $user_roles = [];
	/**
	 * @var MatchaCup
	 */
	private static $U;
	/**
	 * @var MatchaCup
	 */
	private static $AR;
	/**
	 * @var MatchaCup
	 */
	private static $AP;
	/**
	 * @var MatchaCup
	 */
	public static $ARP;
	/**
	 * @var MatchaCup
	 */
	private static $AUP;
	/**
	 * @var MatchaCup
	 */
	private static $AG;

	/**
	 * true if emergency access is enable
	 * @var bool
	 */
	private static $emerAccess = false;

	private static $isConstructed = false;

	public static function construct($uid = null) {
		if(!self::$isConstructed){
			self::$isConstructed = true;
			self::$conn = Matcha::getConn();
			self::$user_id = isset($uid) ? $uid : ((isset($_SESSION) && isset($_SESSION['user']) && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : '0');
			self::setModels();
			self::$user_roles = self::getUserRoles();
			self::$emerAccess = self::isEmergencyAccess();
			self::buildACL();
		}
	}

	public static function setModels() {
		self::setUserModel();
		self::setGroupModel();
		self::setRoleModel();
		self::setPermissionModel();
		self::setRolePermissionModel();
		self::setUserPermissionModel();
	}

	public static function setUserModel() {
		if(self::$U == null)
			self::$U = MatchaModel::setSenchaModel('App.model.administration.User');
	}

	public static function setUserPermissionModel() {
		if(self::$AUP == null)
			self::$AUP = MatchaModel::setSenchaModel('App.model.administration.AclUserPermissions');
	}

	public static function setGroupModel() {
		if(self::$AG == null)
			self::$AG = MatchaModel::setSenchaModel('App.model.administration.AclGroup');
	}

	public static function setRoleModel() {
		if(self::$AR == null)
			self::$AR = MatchaModel::setSenchaModel('App.model.administration.AclRoles');
	}

	public static function setPermissionModel() {
		if(self::$AP == null)
			self::$AP = MatchaModel::setSenchaModel('App.model.administration.AclPermissions');
	}

	public static function setRolePermissionModel() {
		if(self::$ARP == null)
			self::$ARP = MatchaModel::setSenchaModel('App.model.administration.AclRolePermissions');
	}

	/**
	 * ACL GROUPS
	 */
	public static function getAclGroups($params) {
		self::setGroupModel();
		return self::$AG->load($params)->all();
	}

	public static function getAclGroup($params) {
		self::setGroupModel();
		return self::$AG->load($params)->one();
	}

	public static function addAclGroup($params) {
		self::setGroupModel();
		return self::$AG->save($params);
	}

	public static function updateAclGroup($params) {
		self::setGroupModel();
		return self::$AG->save($params);
	}

	public static function deleteAclGroup($params) {
		self::setGroupModel();
		return self::$AG->destroy($params);
	}

	/**
	 * ACL ROLES
	 */
	public static function getAclRoles($params) {
		self::setRoleModel();
		return self::$AR->load($params)->all();
	}

	public static function getAclRole($params) {
		self::setRoleModel();
		return self::$AR->load($params)->one();
	}

	public static function addAclRole($params) {
		self::setRoleModel();
		return self::$AR->save($params);
	}

	public static function updateAclRole($params) {
		self::setRoleModel();
		return self::$AR->save($params);
	}

	public static function deleteAclRole($params) {
		self::setRoleModel();
		return self::$AR->destroy($params);
	}

	/**
	 * ACL PERMISSIONS
	 */
	public static function getAclPermissions($params) {
		self::setPermissionModel();
		return self::$AP->load($params)->all();
	}

	/**
	 * ACL ROLE PERMISSIONS
	 */
	public static function getAclRolePermission($params) {
		self::setRolePermissionModel();
		return self::$ARP->load($params)->one();
	}

	public static function updateAclRolePermission($params) {
		self::setRolePermissionModel();
		return self::$ARP->save($params);
	}

	/**
	 * ACL GRID LOGIC
	 */
	public static function getGroupPerms($params) {

		$columns = [];
		$fields = [];
		$data = [];

		$column = [];
		$column['text'] = 'Permission';
		$column['locked'] = true;
		$column['dataIndex'] = 'title';
		$column['width'] = 300;
		$columns[] = $column;

		$roles = self::getAclRoles(['group_id' => $params->group_id]);
		$permissions = self::getAclPermissions(['active' => 1]);

		$first = true;
		foreach($permissions as $permission){

			$permission = (object)$permission;
			$pData = [];
			$pData['id'] = $permission->id;
			$pData['title'] = $permission->perm_name;
			$pData['group_id'] = $params->group_id;
			$pData['category'] = $permission->perm_cat;

			foreach($roles as $role){
				$role = (object)$role;

				$rp = self::getAclRolePermission([
					'role_id' => $role->id,
					'perm_id' => $permission->id
				]);
				$pData['role-' . $role->id] = $rp === false ? $rp : $rp['value'];

				// columns and fields info
				if(!$first)
					continue;
				// get columns info
				$column = [];
				$column['text'] = $role->role_name;
				$column['align'] = 'center';
				$column['width'] = 150;
				$column['dataIndex'] = 'role-' . $role->id;
				$columns[] = $column;
				// model fields
				$field = [];
				$field['name'] = 'role-' . $role->id;
				$field['type'] = 'bool';
				$fields[] = $field;
			}
			$first = false;
			$data[] = $pData;
		}

		return [
			'total' => count($data),
			'data' => $data,
			'columns' => $columns,
			'fields' => $fields
		];
	}

	public static function updateGroupPerms($params) {
		if(is_array($params)){
			foreach($params as $i => $param){
				$params[$i] = self::saveRolePerm($param);
			}
		} else {
			$params = self::saveRolePerm($params);
		}
		return $params;
	}

	public static function saveRolePerm($record) {
		$perm_id = $record->id;

		foreach($record as $key => $value){

			if(substr($key, 0, 4) != 'role')  continue;

			$k = explode('-', $key);
			$role_id = $k[1];

			$rp = self::getAclRolePermission([
				'role_id' => $role_id,
				'perm_id' => $perm_id
			]);

			if($rp === false){
				$rp = new stdClass();
				$rp->role_id = $role_id;
				$rp->perm_id = $perm_id;
			}
			$rp = (object) $rp;

			$rp->value = $value;
			self::updateAclRolePermission($rp);
		}

		return $record;
	}

	//------------------------------------------------------------------------------------------------------------------
	// Main Sencha Model Getter and Setters
	//------------------------------------------------------------------------------------------------------------------

	/**
	 * @internal param string $format
	 * @return array
	 */
	public static function getAllRoles() {
		self::construct();
		return [
			'totals' => self::$AR->load()->rowCount(),
			'row' => self::$AR->load()->all()
		];
	}

	/**
	 * @return array
	 */
	private static function getUserRoles() {
		$sth = $rolesRec = self::$conn->prepare("SELECT users.role_id FROM `users` WHERE users.id = ?");
		$sth->execute([self::$user_id]);
		$record = $sth->fetch(PDO::FETCH_ASSOC);

		if($record === false){
			$roles = [];
		}else{
			$roles = [$record['role_id']];
		}

		return $roles;
	}

	//------------------------------------------------------------------------------------------------------------------
	// Extra methods
	// This methods are used by the view to gather extra data from the store or the model
	//------------------------------------------------------------------------------------------------------------------

	private static function buildACL() {
		//first, get the rules for the user's role
		if(count(self::$user_roles) > 0){
			self::$perms = array_merge(
                self::$perms,
                self::getRolePerms(self::$user_roles)
            );
		}
		//then, get the individual user permissions
		self::$perms = array_merge(
            self::$perms,
            self::getUserPerms()
        );

		if(self::$emerAccess){
			self::$perms = array_merge(
                self::$perms,
                self::getEmergencyAccessPerms()
            );
		}

	}

	/**
	 * @param $perm_id
	 * @return mixed
	 */
	private static function getPermNameByPermId($perm_id) {
		$row = self::$AP->load(['perm_id' => $perm_id])->one();
		return $row['perm_name'];
	}

	/**
	 * @param $role_id
	 * @return mixed
	 */
	private static function getRoleNameByRoleId($role_id) {
		$row = self::$AR->load(['role_id' => $role_id])->one();
		return $row['role_name'];
	}

	/**
	 * @internal param $role
	 * @return array
	 */
	private static function getRolePerms() {
		$perms = [];

		if(is_array(self::$user_roles)){
			$fo = implode("','", self::$user_roles);
			$sql = "SELECT rp.value, p.perm_key, p.perm_name, p.id as perm_id
				      FROM `acl_permissions` AS p
		   	 	 LEFT JOIN `acl_role_perms` AS rp ON p.`id` = rp.`perm_id`
				     WHERE rp.`role_id` IN (?)
				       AND rp.value = '1'
				       AND p.active = '1'";
		} else {
			$fo = self::$user_roles;
			$sql = "SELECT rp.value, p.perm_key, p.perm_name, p.id as perm_id
				      FROM `acl_permissions` AS p
		   	     LEFT JOIN `acl_role_perms` AS rp ON p.`id` = rp.`perm_id`
				     WHERE rp.`role_id` = ?
				       AND rp.value = '1'
				       AND p.active = '1'";
		}

		$sth = self::$conn->prepare($sql);
		$sth->execute([$fo]);
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($records as $record){
			$pK = strtolower($record['perm_key']);

			if($pK == '' || $record['value'] == '0'){
				continue;
			}

			$perms[$pK] = [
				'perm' => $pK,
				'value' => true,
				'name' => $record['perm_name']
			];
		}
		return $perms;
	}

	/**
	 * @internal param $user_id
	 * @return array
	 */
	public static function getUserPerms() {
		self::construct();
		$perms = [];

		$sql = "SELECT up.value, p.perm_key, p.perm_name, p.id as perm_id
				  FROM `acl_user_perms` AS up
		     LEFT JOIN `acl_permissions` AS p ON p.`id` = up.`perm_id`
				 WHERE up.`user_id` IN (?)
				   AND p.active = '1'";

		$sth = self::$conn->prepare($sql);
		$sth->execute([self::$user_id]);
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($records as $record){
			$pK = strtolower($record['perm_key']);

			if($pK == '' || $record['value'] == '0'){
				continue;
			}

			$perms[$pK] = [
				'perm' => $pK,
				'value' => true,
				'name' => $record['perm_name']
			];
		}

		return $perms;
	}

	/**
	 * @param $role_id
	 * @return bool
	 */
	private static function userHasRole($role_id) {
		foreach(self::$user_roles as $k => $v)
			if(floatval($v) === floatval($role_id))
				return true;
		return false;
	}

	public static function getAllUserPermsAccess() {
		self::construct();
		return array_values(self::$perms);
	}

	/**
	 * Has Permission.
	 *
	 * This public function will return true if the user
	 * has permission to to the permission passed as
	 * an argument
	 *
	 *
	 * @param $perm_key
	 * @return bool
	 *
	 * {@source }
	 */
	public static function hasPermission($perm_key) {
		self::construct();
		$perm_key = strtolower($perm_key);

		if(isset(self::$perms[$perm_key]) && self::$perms[$perm_key]['value']){
			return true;
		} else {
			return false;
		}
	}

	public static function hasPermissionByUid($perm_key, $uid) {
		self::$isConstructed = false;
		self::construct($uid);
		$perm_key = strtolower($perm_key);

		if(isset(self::$perms[$perm_key]) && self::$perms[$perm_key]['value']){
			self::$isConstructed = false;
			return true;
		} else {
			self::$isConstructed = false;
			return false;
		}
	}

	public static function createRandomKey() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime() * 1000000);
		$i = 0;
		$AESkey = '';
		while($i <= 31) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$AESkey = $AESkey . $tmp;
			$i++;
		}
		return strlen($AESkey) == 32 ? $AESkey : false;

	}

	public static function getEmergencyAccessPerms() {
		self::construct();
		$perms = [];
		$sql = "SELECT DISTINCT rp.value, p.perm_key, p.perm_name, p.id as perm_id
			      FROM `acl_roles` AS r
			 LEFT JOIN `acl_role_perms` AS rp ON r.`id` = rp.`role_id`
			 LEFT JOIN `acl_permissions` AS p ON p.`id` = rp.`perm_id`
			     WHERE r.`group_id` = ? AND rp.value = '1' AND p.active = '1'";
		$sth = self::$conn->prepare($sql);
		$sth->execute([4]);
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($records as $record){
			$pK = strtolower($record['perm_key']);
			if($pK == '' || $record['value'] == '0'){ continue; }

			$perms[$pK] = [
				'perm' => $pK,
				'value' => true,
				'name' => $record['perm_name']
			];
		}
		return $perms;
	}

	public static function emergencyAccess($uid) {
		self::construct();
		if(!isset($_SESSION['user']) && !isset($_SESSION['user']['token']))
			return false;
		include_once(ROOT . '/classes/Crypt.php');
		$foo = json_decode(Crypt::decrypt($_SESSION['user']['token']), true);
		if($foo['uid'] != $uid)
			return false;
		if(!self::hasPermission('emergency_access'))
			return false;
		$_SESSION['user']['emergencyAccess'] = true;
		return $_SESSION['user']['emergencyAccess'];
	}

	public static function isEmergencyAccess() {
		self::construct();
		if(is_numeric(self::$user_id) && isset($_SESSION['user']) && isset($_SESSION['user']['auth']) && isset($_SESSION['user']['emergencyAccess']) && $_SESSION['user']['auth'] && $_SESSION['user']['emergencyAccess'])
			return true;
		return false;
	}

	/**
	 * @param {string} $category
	 * @param {array} $permissions
	 * @param {bool} $install
	 */
	public static function updateModulePermissions($category, $permissions, $install){
		self::$conn = Matcha::getConn();
		self::setPermissionModel();

		foreach($permissions as $permission){
			$permission = (object) $permission;

			$sth = self::$conn->prepare('SELECT * FROM `acl_permissions` WHERE perm_key = ?');
			$sth->execute([$permission->key]);
			$record = $sth->fetch(PDO::FETCH_ASSOC);

			if($install && $record === false){
				$sth = self::$conn->prepare('INSERT INTO `acl_permissions` (`perm_cat`, `perm_name`, `perm_key`, `active`, `seq`) VALUES (?,?,?,?,?)');
				$sth->execute([
					$category,
					$permission->title,
					$permission->key,
					'1',
					isset($permission->seq) ? $permission->seq : '0'
				]);
			}elseif($install){
				$record = (object) $record;
				$sth = self::$conn->prepare('UPDATE `acl_permissions` SET `perm_cat` = ?, `perm_name` = ?, `perm_key` = ?, `active` = ?, `seq` = ? WHERE id = ?');
				$sth->execute([
					$category,
					$permission->title,
					$permission->key,
					'1',
					isset($permission->seq) ? $permission->seq : '0',
					$record->id
				]);
			}elseif($record !== false){
				$record = (object) $record;
				$sth = self::$conn->prepare('UPDATE `acl_permissions` SET `active` = ?  WHERE id = ?');
				$sth->execute(['0',$record->id]);
			}
		}
	}
}
