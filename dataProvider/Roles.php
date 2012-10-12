<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Roles.php
 * Date: 2/3/12
 * Time: 8:57 PM
 */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['root'].'/dataProvider/ACL.php');
class Roles extends ACL {

    /**
     *
     * @return mixed
     */
    public function getRoleForm(){
        $items = array();
        $perms = array();
        $roles = $this->getAllRoles();
        $categories = array('General','Calendar','Patients','Encounters','Demographics','Documents','ePrescription','Administrators','Pool Areas','Miscellaneous');
        foreach($this->getAllPermissions('full') as $perm){
            array_push($perms,$perm);
        }
        foreach($categories as $cat){
            $item = array();
            $item['xtype']      = 'fieldset';
            $item['title']      = $cat;
            $item['layout']     = 'anchor';
            $item['labelWidth'] = 100;
            $item['defaults']   = array(
                'xtype'         => 'fieldcontainer',
                'defaultType'   => 'mitos.checkbox',
                'layout'        => 'hbox',
                'defaults'      => array(
                    'padding'    =>'0 100 0 0'
                ),
                'labelWidth'    => 200
             );
            $item['items']      = array();
            foreach($perms as $perm){
                $row = null;
                if(strtolower($perm['Cat']) == strtolower($item['title'])){
                    $row['fieldLabel'] = $perm['Name'];
                    $checkboxes = array();
                    foreach($roles['row'] as $role){
                        //TODO:  for development...  false : false
                        $disable = false; //(strtolower($role['role_name']) == 'administrator')? false : false;
                        $checkbox = array('name'=>strtolower($perm['Key']).'_'.strtolower(str_replace(' ','_',$role['role_name'])),'disabled'=>$disable );
                        array_push($checkboxes,$checkbox);
                    }

                    $row['items'] = $checkboxes;
                    array_push($item['items'],$row);
                }
            }
            array_push($items,$item);
        }
        $rawStr     = json_encode($items);
        $regex      = '("\w*?":|"Ext\.create|\)"\})';
        $cleanItems = array();
        preg_match_all( $regex, $rawStr, $rawItems );
        foreach($rawItems[0] as $item){
            array_push( $cleanItems, str_replace( '"', '', $item) );
        }
        $itemsJsArray = str_replace( '"', '\'', str_replace( $rawItems[0], $cleanItems, $rawStr ));
        return $itemsJsArray;
    }


    /**
     * @return array
     */
    public function getRolesData(){
        $this->conn->setSQL("SELECT role_key, perm_key, value FROM acl_role_perms");
        $rows = array();

        foreach($this->conn->fetchRecords(PDO::FETCH_ASSOC) as $row){
            $rows[$row['perm_key'].'_'.$row['role_key']] = $row['value'];
        }
        return $rows;
    }


    /**
     * @param stdClass $params
     * @return string
     */
    public function saveRolesData(stdClass $params){

        $data = get_object_vars($params);

        function parse_boolean($val) {
            return $val;
        }

        foreach($data as $key => $val){
            $val = parse_boolean($val);
            if(!strpos($key,'_front_office') === false){
                $this->saveRolePerm('front_office',str_replace('_front_office','',$key), $val);
            }elseif(!strpos($key,'_auditor') === false){
                $this->saveRolePerm('auditor',str_replace('_auditor','',$key), $val);
            }elseif(!strpos($key,'_clinician') === false){
                $this->saveRolePerm('clinician',str_replace('_clinician','',$key), $val);
            }elseif(!strpos($key,'_physician') === false){
                $this->saveRolePerm('physician',str_replace('_physician','',$key), $val);
            }elseif(!strpos($key,'_administrator') === false){
                $this->saveRolePerm('administrator',str_replace('_administrator','',$key), $val);
            }
        }

	    $this->conn->execEvent('User Roles Updated values are: ' . json_encode($data));

        return array('success'=>true);
    }

	/**
	 * @param $roleKey
	 * @param $permKey
	 * @param $val
	 * @return void
	 */
    private function saveRolePerm($roleKey, $permKey, $val){

	    $this->conn->setSQL("SELECT id
	                           FROM acl_role_perms
	                          WHERE role_key = '$roleKey'
	                            AND perm_key = '$permKey'");
	    $fo = $this->conn->fetchRecord();

		$data = array();
        if($fo['id'] != null){
			$data['value'] = $val;
	        $this->conn->setSQL($this->conn->sqlBind($data, 'acl_role_perms', 'U', array('role_key' => $roleKey, 'perm_key' => $permKey)));
            $this->conn->execOnly();
        }else{
	        $data['role_key'] = $roleKey;
	        $data['perm_key'] = $permKey;
	        $data['value'] = $val;
	        $sql = $this->conn->sqlBind($data, 'acl_role_perms', 'I');
	        $this->conn->setSQL($sql);
	        $this->conn->execOnly();
        }
    }


}