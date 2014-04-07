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

class ACL
{

	/**
	 * @var array
	 */
	private static $perms = array();
	/**
	 * @var int
	 */
	private static $user_id;
	/**
	 * @var array
	 */
	private static $user_roles = array();
	/**
	 * @var MatchaCup
	 */
	private static $U = null;
	/**
	 * @var MatchaCup
	 */
	private static $AR = null;
	/**
	 * @var MatchaCup
	 */
    private static $AP = null;
	/**
	 * @var MatchaCup
	 */
    public  static $ARP = null;
	/**
	 * @var MatchaCup
	 */
    private static $AUP = null;

	/**
	 * true if emergency access is enable
	 * @var bool
	 */
	private static $emerAccess = false;

	private static $isConstructed =  false;


	public static function construct($uid = null)
	{
		if(!self::$isConstructed){
			self::$isConstructed = true;
			self::$user_id = isset($uid) ? $uid : ((isset($_SESSION) && isset($_SESSION['user']) && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : '0');
			self::setModels();
			self::$user_roles = self::getUserRoles();
			self::$emerAccess = self::isEmergencyAccess();
			self::buildACL();
		}

	}

	public static function setModels(){
		if(self::$U == null) self::$U = MatchaModel::setSenchaModel('App.model.administration.User');
		if(self::$AR == null) self::$AR = MatchaModel::setSenchaModel('App.model.administration.AclRoles');
		if(self::$AP == null) self::$AP = MatchaModel::setSenchaModel('App.model.administration.AclPermissions');
		if(self::$ARP == null) self::$ARP = MatchaModel::setSenchaModel('App.model.administration.AclRolePermissions');
		if(self::$AUP == null) self::$AUP = MatchaModel::setSenchaModel('App.model.administration.AclUserPermissions');
	}

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------

	/**
	 * @internal param string $format
	 * @return array
	 */
	public static function getAllRoles()
	{
		self::construct();
		return array( 'totals' => self::$AR->load()->rowCount(), 'row' => self::$AR->load()->all() );
	}

	/**
	 * @param string $format
	 * @return array
	 */
	public static function getAllPermissions($format = 'ids')
	{
		self::construct();
		$format = strtolower($format);
		$resp = array();
		foreach(self::$AP->load()->all() as $row)
        {
			if($format == 'full')
            {
				$resp[$row['perm_key']] = array(
					'id' => $row['id'],
                    'Name' => $row['perm_name'],
                    'Key' => $row['perm_key'],
                    'Cat' => $row['perm_cat']
				);
			}
            else
            {
				$resp[] = $row['id'];
			}
		}
		return $resp;
	}

	/**
	 * @return array
	 */
	private static function getUserRoles()
	{
		$roles = array();
        $sqlStatement['SELECT'] = 'acl_roles.role_key';
        $sqlStatement['LEFTJOIN'] = 'acl_roles ON users.role_id = acl_roles.id';
        $sqlStatement['WHERE'] = 'users.id =\'' . self::$user_id . '\'';
        $rolesRec = self::$U->buildSQL($sqlStatement)->all();
        if($rolesRec !== false){
            foreach($rolesRec AS $role) {
                $roles[] = (string) $role['role_key'];
            }
        }
		return $roles;
	}

    //------------------------------------------------------------------------------------------------------------------
    // Extra methods
    // This methods are used by the view to gather extra data from the store or the model
    //------------------------------------------------------------------------------------------------------------------

	private static function buildACL()
	{
		//first, get the rules for the user's role
		if(count(self::$user_roles) > 0){
			self::$perms = array_merge(self::$perms, self::getRolePerms(self::$user_roles));
		}
		//then, get the individual user permissions
		self::$perms = array_merge(self::$perms, self::getUserPerms());
	}

	/**
	 * @param $perm_Key
	 * @return mixed
	 */
	private static function getPermNameByPermKey($perm_Key)
	{
        $row = self::$AP->load(array('perm_key'=>$perm_Key))->one();
		return $row['perm_name'];
	}

	/**
	 * @param $role_key
	 * @return mixed
	 */
	private static function getRoleNameByRoleKey($role_key)
	{
        $row = self::$AR->load(array('role_key'=>$role_key))->one();
		return $row['role_name'];
	}

	/**
	 * @internal param $role
	 * @return array
	 */
	private static function getRolePerms()
	{
		$perms = array();
        if(is_array(self::$user_roles))
        {
            $fo = implode("','", self::$user_roles);
            $sqlStatement['SELECT'] = "*";
            $sqlStatement['WHERE'] = "role_key IN ('$fo')";
            $sqlStatement['ORDER'] = "id ASC";
        }
        else
        {
            $fo = self::$user_roles;
            $sqlStatement['SELECT'] = "*";
            $sqlStatement['WHERE'] = "role_key = '$fo'";
            $sqlStatement['ORDER'] = "id ASC";
        }
		if(self::$emerAccess) $emerPerms = self::getEmergencyAccessPerms();
		foreach(self::$ARP->buildSQL($sqlStatement)->all() as $row)
        {
			$pK = $pK = strtolower($row['perm_key']);
			if($pK == '') continue;
			if($row['value'] == '1'){
				$hP = true;
			}else{
				if(self::$emerAccess && isset($emerPerms[$row['perm_key']]) && $emerPerms[$row['perm_key']]){
					$hP = true;
				} else {
					$hP = false;
				}
			}
			$perms[$pK] = array(
				'perm' => $pK,
				'inheritted' => true,
				'value' => $hP,
				'Name' => self::getPermNameByPermKey($row['perm_key']),
				'id' => $row['id']
			);
		}
		return $perms;
	}

	/**
	 * @internal param $user_id
	 * @return array
	 */
	public static function getUserPerms()
	{
		self::construct();
		$perms = array();
		if(self::$emerAccess) $emerPerms = self::getEmergencyAccessPerms();
		foreach(self::$AUP->load(array('user_id'=>self::$user_id))->all() as $row)
        {
			$pK = strtolower($row['perm_key']);
			if($pK == '') continue;
	        if($row['value'] == '1'){
		        $hP = true;
	        }else{
		        if(self::$emerAccess && isset($emerPerms[$row['perm_key']]) && $emerPerms[$row['perm_key']]){
			        $hP = true;
		        } else {
			        $hP = false;
		        }
	        }
	        $perms[$pK] = array(
				'perm' => $pK,
				'inheritted' => false,
				'value' => $hP,
				'Name' => self::getPermNameByPermKey($row['perm_key']),
				'id' => $row['id']
			);
		}
		return $perms;
	}

	/**
	 * @param $role_id
	 * @return bool
	 */
	private static function userHasRole($role_id)
	{
		foreach(self::$user_roles as $k => $v) if(floatval($v) === floatval($role_id)) return true;
		return false;
	}

	public static function getAllUserPermsAccess()
	{
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
	public static function hasPermission($perm_key)
	{
		self::construct();
		$perm_key = strtolower($perm_key);
		if(array_key_exists($perm_key, self::$perms))
        {
			if(self::$perms[$perm_key]['value'] === '1' || self::$perms[$perm_key]['value'] === true)
            {
				return true;
			}
            else
            {
				return false;
			}
		}
        else
        {
			return false;
		}
	}
	public static function hasPermissionByUid($perm_key, $uid)
	{
		self::$isConstructed = false;
		self::construct($uid);
		$perm_key = strtolower($perm_key);
		if(array_key_exists($perm_key, self::$perms))
        {
			if(self::$perms[$perm_key]['value'] === '1' || self::$perms[$perm_key]['value'] === true)
            {
	            self::$isConstructed = false;
				return true;
			}
            else
            {
	            self::$isConstructed = false;
				return false;
			}
		}
        else
        {
	        self::$isConstructed = false;
			return false;
		}
	}

	public static function createRandomKey()
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime() * 1000000);
		$i      = 0;
		$AESkey = '';
		while($i <= 31)
        {
			$num    = rand() % 33;
			$tmp    = substr($chars, $num, 1);
			$AESkey = $AESkey . $tmp;
			$i++;
		}
		if(strlen($AESkey) == 32): return $AESkey;
        else: return false;
        endif;
	}

	public static function getEmergencyAccessPerms(){
		self::construct();
		$perms = array();
		$sqlStatement['SELECT'] = "*";
		$sqlStatement['WHERE'] = "role_key = 'emergencyaccess'";
		$sqlStatement['ORDER'] = "id ASC";
		foreach(self::$ARP->buildSQL($sqlStatement)->all() as $row)
		{
			$pK = strtolower($row['perm_key']);
			if($pK == '' || !$row['value']) continue;
			if($row['value'] == '1') $hP = true; else $hP = false;
			$perms[$pK] = $hP;
		}
		return $perms;
	}

	public static function emergencyAccess($uid){
		self::construct();
		if(!isset($_SESSION['user']) && !isset($_SESSION['user']['token'])) return false;
		include_once (dirname(dirname(__FILE__)) . '/classes/Crypt.php');
		$foo = json_decode(Crypt::decrypt($_SESSION['user']['token']), true);
		if($foo['uid'] != $uid) return false;
		if(!self::hasPermission('emergency_access')) return false;
		$_SESSION['user']['emergencyAccess'] = true;
		return $_SESSION['user']['emergencyAccess'];
	}

	public static function isEmergencyAccess(){
		self::construct();
		if(	is_numeric(self::$user_id) &&
			isset($_SESSION['user']) &&
			isset($_SESSION['user']['auth']) &&
			isset($_SESSION['user']['emergencyAccess']) &&
			$_SESSION['user']['auth'] &&
			$_SESSION['user']['emergencyAccess']) return true;
		return false;
	}
}
