<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class ACL
{

	/**
	 * @var array
	 */
	private $perms = array();
	/**
	 * @var int
	 */
	private $user_id;
	/**
	 * @var array
	 */
	private $user_roles = array();
	/**
	 * @var MatchaCup
	 */
	private $U = null;
	/**
	 * @var MatchaCup
	 */
	private $AR = null;
	/**
	 * @var MatchaCup
	 */
    private $AP = null;
	/**
	 * @var MatchaCup
	 */
    public  $ARP = null;
	/**
	 * @var MatchaCup
	 */
    private $AUP = null;

	/**
	 * true if emergency access is enable
	 * @var bool
	 */
	private $emerAccess = false;

    /**
	 * @internal param string $user_id
	 * @param null|string $uid
	 * @internal param null $user_id
	 */
	public function __construct($uid = '')
	{
		$this->user_id = (!is_numeric($uid)) ? $_SESSION['user']['id'] : $uid;
		$this->setModels();
		$this->user_roles = $this->getUserRoles();
		$this->emerAccess = $this->isEmergencyAccess();
		$this->buildACL();
	}

	public function setModels(){
		if($this->U == null) $this->U = MatchaModel::setSenchaModel('App.model.administration.User');
		if($this->AR == null) $this->AR = MatchaModel::setSenchaModel('App.model.administration.AclRoles');
		if($this->AP == null) $this->AP = MatchaModel::setSenchaModel('App.model.administration.AclPermissions');
		if($this->ARP == null) $this->ARP = MatchaModel::setSenchaModel('App.model.administration.AclRolePermissions');
		if($this->AUP == null) $this->AUP = MatchaModel::setSenchaModel('App.model.administration.AclUserPermissions');
	}

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------

	/**
	 * @internal param string $format
	 * @return array
	 */
	public function getAllRoles()
	{
		return array( 'totals' => $this->AR->load()->rowCount(), 'row' => $this->AR->load()->all() );
	}

	/**
	 * @param string $format
	 * @return array
	 */
	public function getAllPermissions($format = 'ids')
	{
		$format = strtolower($format);
		$resp = array();
		foreach($this->AP->load()->all() as $row)
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
	private function getUserRoles()
	{
		$roles = array();
        $sqlStatement['SELECT'] = "acl_roles.role_key";
        $sqlStatement['LEFTJOIN'] = "acl_roles ON users.role_id = acl_roles.id";
        $sqlStatement['WHERE'] = "users.id ='$this->user_id'";
        $rolesRec = $this->U->buildSQL($sqlStatement)->all();
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

	private function buildACL()
	{
		//first, get the rules for the user's role
		if(count($this->user_roles) > 0){
			$this->perms = array_merge($this->perms, $this->getRolePerms($this->user_roles));
		}
		//then, get the individual user permissions
		$this->perms = array_merge($this->perms, $this->getUserPerms());
	}

	/**
	 * @param $perm_Key
	 * @return mixed
	 */
	private function getPermNameByPermKey($perm_Key)
	{
        $row = $this->AP->load(array('perm_key'=>$perm_Key))->one();
		return $row['perm_name'];
	}

	/**
	 * @param $role_key
	 * @return mixed
	 */
	private function getRoleNameByRoleKey($role_key)
	{
        $row = $this->AR->load(array('role_key'=>$role_key))->one();
		return $row['role_name'];
	}

	/**
	 * @internal param $role
	 * @return array
	 */
	private function getRolePerms()
	{
		$perms = array();
        if(is_array($this->user_roles))
        {
            $fo = implode("','", $this->user_roles);
            $sqlStatement['SELECT'] = "*";
            $sqlStatement['WHERE'] = "role_key IN ('$fo')";
            $sqlStatement['ORDER'] = "id ASC";
        }
        else
        {
            $fo = $this->user_roles;
            $sqlStatement['SELECT'] = "*";
            $sqlStatement['WHERE'] = "role_key = '$fo'";
            $sqlStatement['ORDER'] = "id ASC";
        }
		if($this->emerAccess) $emerPerms = $this->getEmergencyAccessPerms();
		foreach($this->ARP->buildSQL($sqlStatement)->all() as $row)
        {
			$pK = $pK = strtolower($row['perm_key']);
			if($pK == '') continue;
			if($row['value'] == '1'){
				$hP = true;
			}else{
				if($this->emerAccess && isset($emerPerms[$row['perm_key']]) && $emerPerms[$row['perm_key']]){
					$hP = true;
				} else {
					$hP = false;
				}
			}
			$perms[$pK] = array(
				'perm' => $pK,
				'inheritted' => true,
				'value' => $hP,
				'Name' => $this->getPermNameByPermKey($row['perm_key']),
				'id' => $row['id']
			);
		}
		return $perms;
	}

	/**
	 * @internal param $user_id
	 * @return array
	 */
	public function getUserPerms()
	{
		$perms = array();
		if($this->emerAccess) $emerPerms = $this->getEmergencyAccessPerms();
		foreach($this->AUP->load(array('user_id'=>$this->user_id))->all() as $row)
        {
			$pK = strtolower($row['perm_key']);
			if($pK == '') continue;
	        if($row['value'] == '1'){
		        $hP = true;
	        }else{
		        if($this->emerAccess && isset($emerPerms[$row['perm_key']]) && $emerPerms[$row['perm_key']]){
			        $hP = true;
		        } else {
			        $hP = false;
		        }
	        }
	        $perms[$pK] = array(
				'perm' => $pK,
				'inheritted' => false,
				'value' => $hP,
				'Name' => $this->getPermNameByPermKey($row['perm_key']),
				'id' => $row['id']
			);
		}
		return $perms;
	}

	/**
	 * @param $role_id
	 * @return bool
	 */
	private function userHasRole($role_id)
	{
		foreach($this->user_roles as $k => $v) if(floatval($v) === floatval($role_id)) return true;
		return false;
	}

	public function getAllUserPermsAccess()
	{
		return array_values($this->perms);
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
	public function hasPermission($perm_key)
	{
		$perm_key = strtolower($perm_key);
		if(array_key_exists($perm_key, $this->perms))
        {
			if($this->perms[$perm_key]['value'] === '1' || $this->perms[$perm_key]['value'] === true)
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

	public function getEmergencyAccessPerms(){
		$perms = array();
		$sqlStatement['SELECT'] = "*";
		$sqlStatement['WHERE'] = "role_key = 'emergencyaccess'";
		$sqlStatement['ORDER'] = "id ASC";
		foreach($this->ARP->buildSQL($sqlStatement)->all() as $row)
		{
			$pK = strtolower($row['perm_key']);
			if($pK == '' || !$row['value']) continue;
			if($row['value'] == '1') $hP = true; else $hP = false;
			$perms[$pK] = $hP;
		}
		return $perms;
	}

	public function emergencyAccess($uid){
		if(!isset($_SESSION['user']) && !isset($_SESSION['user']['token'])) return false;
		include_once ($_SESSION['root'] . '/classes/Crypt.php');
		$foo = json_decode(Crypt::decrypt($_SESSION['user']['token']), true);
		if($foo['uid'] != $uid) return false;
		if(!$this->hasPermission('emergency_access')) return false;
		$_SESSION['user']['emergencyAccess'] = true;
		return $_SESSION['user']['emergencyAccess'];
	}


	public function isEmergencyAccess(){
		if(	is_numeric($this->user_id) &&
			isset($_SESSION['user']) &&
			isset($_SESSION['user']['auth']) &&
			isset($_SESSION['user']['emergencyAccess']) &&
			$_SESSION['user']['auth'] &&
			$_SESSION['user']['emergencyAccess']) return true;
		return false;
	}
}
