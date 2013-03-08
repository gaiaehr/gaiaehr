<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

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

if (!isset($_SESSION))
{
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

include_once ($_SESSION['root'] . '/dataProvider/Person.php');
include_once ($_SESSION['root'] . '/classes/AES.php');
include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');

class User
{

    /**
     * @var MatchaHelper
     */
    private $db;
    /**
     * @var
     */
    private $user_id;
    private $user;

    private $User = null;
    private $AclUserRoles = null;

    function __construct()
    {
        $this->db = new MatchaHelper();
        $this->User = MatchaModel::setSenchaModel('App.model.administration.User');
        $this->AclUserRoles = MatchaModel::setSenchaModel('App.model.administration.AclUserRoles');
        return;
    }

    //
    //	/**
    //	 * @param stdClass $params
    //	 * @return mixed
    //	 */
    //	public function getUsers(stdClass $params){
    //		$this->setUserModel();
    //		return $this->u->load($params)->all();
    //	}
    //
    //	/**
    //	 * @param stdClass $params
    //	 * @return mixed
    //	 */
    //	public function getUser(stdClass $params){
    //		$this->setUserModel();
    //		return $this->u->load($params)->one();
    //	}
    //
    //	/**
    //	 * @param stdClass $params
    //	 * @return mixed
    //	 */
    //	public function saveUser($params){
    //		$this->setUserModel();
    //		return $this->u->save($params);
    //	}
    //
    //	/**
    //	 * @param $uid
    //	 */
    //	public function setUserByUid($uid){
    //		$this->setUserModel();
    //		$this->user = $this->u->load($uid)->one();
    //	}
    //
    //	/**
    //	 * @param $params
    //	 */
    //	public function setUser($params){
    //		$this->setUserModel();
    //		$this->user = $this->u->load($params)->one();
    //	}
    //

    /**
     * @param $username
     * @return bool
     */
    public function usernameExist($username)
    {
        $userResult = $this->User->load( array('username' => $username) )->one();
        return is_array($userResult);
    }

    /**
     * @return AES
     */
    private function getAES()
    {
        return new AES($_SESSION['site']['AESkey']);
    }

    /**
     * @return mixed
     */
    public function getCurrentUserId()
    {
        return $_SESSION['user']['id'];
    }

    public function getCurrentUserTitleLastName()
    {
        $userResult = $this->User->load( array('id' => $this->getCurrentUserId()), array('title', 'lname') )->one();
        return $userResult['title'] . ' ' . $userResult['lname'];
    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function getUsers(stdClass $params)
    {
        $this->db->setSQL("SELECT u.*, r.role_id
                             FROM users AS u
                        LEFT JOIN acl_user_roles AS r ON r.user_id = u.id
                            WHERE u.authorized = 1 OR u.username != ''
                         ORDER BY u.username
                            LIMIT $params->start,$params->limit");
        $rows = array();
        foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
        {
            $row['fullname'] = Person::fullname($row['fname'], $row['mname'], $row['lname']);
            unset($row['password'], $row['pwd_history1'], $row['pwd_history2']);
            array_push($rows, $row);
        }
        return $rows;
    }

    public function getUserNameById($id)
    {
        $userResult = $this->User->load( array('id' => $id) )->one();
        $userName = $userResult['title'] . ' ' . $userResult['lname'];
        return $userName;
    }

    public function getUserFullNameById($id)
    {
        $userResult = $this->User->load( array('id' => $id) )->one();
        $userName = Person::fullname($userResult['fname'], $userResult['mname'], $userResult['lname']);
        return $userName;
    }

    public function getCurrentUserData()
    {
        $userResult = $this->User->load( array('id' => $this->getCurrentUserId()) )->one();
        return $userResult;
    }

    public function getCurrentUserBasicData()
    {
        $userResult = $this->User->load( array('id' => $this->getCurrentUserId()), array('id', 'title', 'fname', 'mname', 'lname') )->one();
        return $userResult;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addUser(stdClass $params)
    {
        if (!$this->usernameExist($params->username))
        {



	        unset($params->fullname);
	        if (isset($params->taxonomy) && $params->taxonomy == '') unset($params->taxonomy);

	        $password = $params->password;
	        unset($params->password);


            $this->user = $this->User->save($params);

            $params->fullname = Person::fullname($params->fname, $params->mname, $params->lname);
            $this->changePassword($password);
            $params->password = '';

	        $role['role_id'] = $params->role_id;
	        $role['user_id'] = $this->user['id'];
            $sql = $this->db->sqlBind($role, 'acl_user_roles', 'I');
            $this->db->setSQL($sql);
            $this->db->execLog();


	        return $params;
        }
        else
        {
            return array('success' => false, 'error' => "Username \"$params->username\" exist, please try a different username");
        }
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateUser(stdClass $params)
    {
        $data = get_object_vars($params);
        $params->password = '';
        $this->user['id'] = $data['id'];
        $role['role_id'] = $data['role_id'];
        unset($data['id'], $data['role_id'], $data['fullname']);
        if ($data['password'] != '')
        {
            $this->changePassword($data['password']);
        }
        unset($data['password']);
        $sql = $this->db->sqlBind($role, 'acl_user_roles', 'U', array('user_id' => $this->user['id']));
        $this->db->setSQL($sql);
        $this->db->execLog();
        $sql = $this->db->sqlBind($data, 'users', 'U', array('id' => $this->user['id']));
        $this->db->setSQL($sql);
        $this->db->execLog();
        return $params;

    }


    /**
     * @param stdClass $params
     * @return array
     */
    public function chechPasswordHistory(stdClass $params)
    {
        $aes = $this->getAES();
        $this->user['id'] = $params->id;
        $aesPwd = $aes->encrypt($params->password);
        $this->db->setSQL("SELECT password, pwd_history1, pwd_history2  FROM users WHERE id='" . $this->user['id'] . "'");
        $pwds = $this->db->fetchRecord();
        if ($pwds['password'] == $aesPwd || $pwds['pwd_history1'] == $aesPwd || $pwds['pwd_history2'] == $aesPwd)
        {
            return array('error' => true);
        }
        else
        {
            return array('error' => false);
        }
    }

    /**
     * @param $newpassword
     * @return mixed
     */
    public function changePassword($newpassword)
    {
        $aes = $this->getAES();
        $aesPwd = $aes->encrypt($newpassword);
	    $id = $this->user['id'];
        $this->db->setSQL("SELECT password, pwd_history1 FROM users WHERE id = '$id'");
        $pwds = $this->db->fetchRecord(PDO::FETCH_ASSOC);

	    $row['password'] = $aesPwd;
        $row['pwd_history1'] = $pwds['password'];
        $row['pwd_history2'] = $pwds['pwd_history1'];
        $sql = $this->db->sqlBind($row, 'users', 'U', array('id' => $this->user['id']));

        $this->db->setSQL($sql);
        $this->db->execOnly();
        return;

    }

    public function changeMyPassword(stdClass $params)
    {
        $this->user['id'] = $params->id;
        return array('success' => true);
    }

    public function updateMyAccount(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id']);
        $sql = $this->db->sqlBind($data, 'users', 'U', array('id' => $params->id));
        $this->db->setSQL($sql);
        $this->db->execLog();
        return array('success' => true);
    }

    public function verifyUserPass($pass)
    {
        $aes = new AES($_SESSION['site']['AESkey']);
        $userResult = $this->User->load( array('id' => $_SESSION['user']['id'], 'password' => $aes->encrypt($pass), 'authorized'=>'1'), array('username') )->one();
        $count = count($userResult);
        return ($count != 0) ? 1 : 2;
    }

    public function getProviders()
    {
        $this->db->setSQL("SELECT u.id, u.fname, u.lname, u.mname
                FROM acl_user_roles AS acl
                LEFT JOIN users AS u ON u.id = acl.user_id
                WHERE acl.role_id = '2'");
        $records = array();
        $records[] = array('name' => 'All', 'id' => 'all');
        foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) As $row)
        {
            $row['name'] = $this->getUserNameById($row['id']);
            $records[] = $row;
        }
        return $records;
    }

    public function getUserRolesByCurrentUserOrUserId($uid = null)
    {
        $uid = ($uid == null) ? $_SESSION['user']['id'] : $uid;
        return $this->User->load( array('id' => $uid))->one();
    }

}

//$u = new User();
//print_r($u->getUserByUsername('demo'));
