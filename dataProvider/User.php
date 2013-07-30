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
	 * @var bool|MatchaCUP|null
	 */
	private $u = NULL;
	/**
	 * @var
	 */
	private $user;
	/**
	 * @var MatchaHelper
	 */
	private $db;


    function __construct()
    {
        $this->db = new MatchaHelper();
	    $this->u = MatchaModel::setSenchaModel('App.model.administration.User');
        return;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
    public function saveUser($params)
    {
        return $this->u->save($params);
    }

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

    public function addUser(stdClass $params)
    {
        try
        {
            if(!$this->usernameExist($params->username))
            {
                unset($params->fullname);

                if (isset($params->taxonomy) && $params->taxonomy == '') unset($params->taxonomy);

	            // handle passwords
	            $aes = $this->getAES();
	            $params->password = $aes->encrypt($params->password);
                unset($params->pwd_history1, $params->pwd_history2);
	            // save new user
                $this->user = $this->u->save($params);

	            unset($this->user['password'], $this->user['pwd_history1'], $this->user['pwd_history2']);
	            $this->user['fullname'] = Person::fullname($params->fname, $params->mname, $params->lname);
	            $this->user['password'] = '';
                return $this->user;
            }
            else
            {
                throw new Exception("Username \"$params->username\" exist, please try a different username");
            }
        }
        catch (Exception $e)
        {
            return $e;
        }
    }

    public function updateUser(stdClass $params)
    {
        $password = $params->password;
        unset($params->password, $params->pwd_history1, $params->pwd_history2);
        $this->user = $this->u->save($params);
        if($password) $this->changePassword($password);
        return $params;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Extra methods
    // This methods are used by the view to gather extra data from the store or the model
    //------------------------------------------------------------------------------------------------------------------
    public function setUserByUid($uid)
    {
        $this->user = $this->u->load($uid)->one();
    }

    public function setUser($params)
    {
        $this->user = $this->u->load($params)->one();
    }

    public function usernameExist($username)
    {
        $user = $this->u->load(array('username' => $username))->one();
        return !empty($user);
    }

    private function getAES()
    {
        return new AES($_SESSION['site']['AESkey']);
    }

    public function getCurrentUserId()
    {
        return $_SESSION['user']['id'];
    }

    public function getCurrentUserTitleLastName()
    {
        $userResult = $this->u->load($this->getCurrentUserId(), array('title', 'lname'))->one();
        return $userResult['title'] . ' ' . $userResult['lname'];
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
