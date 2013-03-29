<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

include_once ($_SESSION['root'] . '/dataProvider/Person.php');
include_once ($_SESSION['root'] . '/classes/AES.php');

class User
{

    /**
     * @var MatchaHelper
     */
    private $db;
    /**
     * @var
     */
    private $user;

	/**
	 * @var MatchaCUP
	 */
	private $u;
	/**
	 * @var MatchaCUP
	 */
    private $a;

    function __construct()
    {
        $this->db = new MatchaHelper();
        $this->u = MatchaModel::setSenchaModel('App.model.administration.User');
//        $this->a = MatchaModel::setSenchaModel('App.model.administration.AclUserRoles');
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
    	/**
    	 * @param stdClass $params
    	 * @return mixed
    	 */
    	public function saveUser($params){
    		return $this->u->save($params);
    	}

    	/**
    	 * @param $uid
    	 */
    	public function setUserByUid($uid){
    		$this->user = $this->u->load($uid)->one();
    	}

    	/**
    	 * @param $params
    	 */
    	public function setUser($params){
    		$this->user = $this->u->load($params)->one();
    	}


    /**
     * @param $username
     * @return bool
     */
    public function usernameExist($username)
    {
        $user = $this->u->load(array('username' => $username))->one();
        return !empty($user);
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
        $userResult = $this->u->load($this->getCurrentUserId(), array('title', 'lname'))->one();
        return $userResult['title'] . ' ' . $userResult['lname'];
    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function getUsers(stdClass $params)
    {
	    $rows = array();
        foreach ($this->u->load($params)->all() as $row)
        {
            $row['fullname'] = Person::fullname($row['fname'], $row['mname'], $row['lname']);
            unset($row['password'], $row['pwd_history1'], $row['pwd_history2']);
            array_push($rows, $row);
        }
        return $rows;
    }

    public function getUserNameById($id)
    {
        $userResult = $this->u->load($id)->one();
        $userName = $userResult['title'] . ' ' . $userResult['lname'];
        return $userName;
    }

    public function getUserFullNameById($id)
    {
        $userResult = $this->u->load($id)->one();
        $userName = Person::fullname($userResult['fname'], $userResult['mname'], $userResult['lname']);
        return $userName;
    }

    public function getCurrentUserData()
    {
        $userResult = $this->u->load($this->getCurrentUserId())->one();
        return $userResult;
    }

    public function getCurrentUserBasicData()
    {
        $userResult = $this->u->load($this->getCurrentUserId(), array('id', 'title', 'fname', 'mname', 'lname'))->one();
        return $userResult;
    }

    /**
     * @param stdClass $params
     * @throws Exception
     * @return stdClass
     */
    public function addUser(stdClass $params)
    {
        try{
            if(!$this->usernameExist($params->username))
            {
                unset($params->fullname);
                if (isset($params->taxonomy) && $params->taxonomy == '') unset($params->taxonomy);
                $password = $params->password;
                // unset passwords, this will be handle later
                unset($params->password, $params->pwd_history1, $params->pwd_history2);
                $this->user = $this->u->save($params);

                $params->fullname = Person::fullname($params->fname, $params->mname, $params->lname);
                $this->changePassword($password);
                $params->password = '';
                return $params;
            }
            else
            {
                throw new Exception("Username \"$params->username\" exist, please try a different username");
            }
        }catch (Exception $e){
            return $e;
        }
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateUser(stdClass $params)
    {
	    $password = $params->password;
	    unset($params->password, $params->pwd_history1, $params->pwd_history2);
	    $this->user = $this->u->save($params);
	    if($password) $this->changePassword($password);
        return $params;

    }


    /**
     * @param stdClass $params
     * @return array
     */
    public function chechPasswordHistory(stdClass $params)
    {
        $aes = $this->getAES();
        $id = $params->id;
        $aesPwd = $aes->encrypt($params->password);
        $this->db->setSQL("SELECT password, pwd_history1, pwd_history2  FROM users WHERE id='$id'");
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

		$params = new stdClass();
		$params->id = $id;
		$params->password = $aesPwd;
		$params->pwd_history1 = $pwds['password'];
		$params->pwd_history2 = $pwds['pwd_history1'];
		$this->u->save($params);
		return;
	}

    public function changeMyPassword(stdClass $params)
    {
        $this->user['id'] = $params->id;
        return array('success' => true);
    }

    public function updateMyAccount(stdClass $params)
    {
	    $data  = new $params;
	    unset($data->password, $data->pwd_history1, $data->pwd_history2);
	    $this->user = $this->u->save($data);
	    if($params->password != '') $this->changePassword($params->password);

        $this->u->save($params);
        return array('success' => true);
    }

    public function verifyUserPass($pass)
    {
        $aes = new AES($_SESSION['site']['AESkey']);
        $userResult = $this->u->load( array('id' => $_SESSION['user']['id'], 'password' => $aes->encrypt($pass), 'authorized'=>'1'), array('username') )->one();
        $count = count($userResult);
        return ($count != 0) ? 1 : 2;
    }

    public function getProviders()
    {
        $records = array();
        $records[] = array('name' => 'All', 'id' => 'all');
        foreach($this->u->load(array('role_id' => 2))->all() As $row)
        {
            $row['name'] = $this->getUserNameById($row['id']);
            $records[] = $row;
        }
        return $records;
    }

    public function getUserRolesByCurrentUserOrUserId($uid = null)
    {
        $uid = ($uid == null) ? $_SESSION['user']['id'] : $uid;
        return $this->u->load($uid)->one();
    }

}

//$u = new User();
//print_r($u->getUserByUsername('demo'));
