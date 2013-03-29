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
     * Data Object
     */
    private $User = NULL;
    private $UserCache;
    private $db;


    function __construct()
    {
        $this->db = new MatchaHelper();
        $this->User = MatchaModel::setSenchaModel('App.model.administration.User');
        return;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
    public function saveUser($params)
    {
        return $this->User->save($params);
    }

    public function getUsers(stdClass $params)
    {
        $rows = array();
        foreach ($this->User->load($params)->all() as $row)
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
                $password = $params->password;
                // unset passwords, this will be handle later
                unset($params->password, $params->pwd_history1, $params->pwd_history2);
                $this->user = $this->User->save($params);

                $params->fullname = Person::fullname($params->fname, $params->mname, $params->lname);
                $this->changePassword($password);
                $params->password = '';
                return $params;
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
        $this->UserCache = $this->User->save($params);
        if($password) $this->changePassword($password);
        return $params;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Extra methods
    // This methods are used by the view to gather extra data from the store or the model
    //------------------------------------------------------------------------------------------------------------------
    public function setUserByUid($uid)
    {
        $this->UserCache = $this->User->load($uid)->one();
    }

    public function setUser($params)
    {
        $this->UserCache = $this->User->load($params)->one();
    }

    public function usernameExist($username)
    {
        $user = $this->User->load(array('username' => $username))->one();
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
        $userResult = $this->User->load($this->getCurrentUserId(), array('title', 'lname'))->one();
        return $userResult['title'] . ' ' . $userResult['lname'];
    }

    public function getUserNameById($id)
    {
        $userResult = $this->User->load($id)->one();
        $userName = $userResult['title'] . ' ' . $userResult['lname'];
        return $userName;
    }

    public function getUserFullNameById($id)
    {
        $userResult = $this->User->load($id)->one();
        $userName = Person::fullname($userResult['fname'], $userResult['mname'], $userResult['lname']);
        return $userName;
    }

    public function getCurrentUserData()
    {
        $userResult = $this->User->load($this->getCurrentUserId())->one();
        return $userResult;
    }

    public function getCurrentUserBasicData()
    {
        $userResult = $this->User->load($this->getCurrentUserId(), array('id', 'title', 'fname', 'mname', 'lname'))->one();
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

        $id = $this->UserCache['id'];
        $this->db->setSQL("SELECT password, pwd_history1 FROM users WHERE id = '$id'");
        $pwds = $this->db->fetchRecord(PDO::FETCH_ASSOC);

        $params = new stdClass();
        $params->id = $id;
        $params->password = $aesPwd;
        $params->pwd_history1 = $pwds['password'];
        $params->pwd_history2 = $pwds['pwd_history1'];
        $this->User->save($params);
        return;
    }

    public function changeMyPassword(stdClass $params)
    {
        $this->UserCache['id'] = $params->id;
        return array('success' => true);
    }

    public function updateMyAccount(stdClass $params)
    {
        $data  = new $params;
        unset($data->password, $data->pwd_history1, $data->pwd_history2);
        $this->UserCache = $this->User->save($data);
        if($params->password != '') $this->changePassword($params->password);
        $this->User->save($params);
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
        $records = array();
        $records[] = array('name' => 'All', 'id' => 'all');
        foreach($this->User->load(array('role_id' => 2))->all() As $row)
        {
            $row['name'] = $this->getUserNameById($row['id']);
            $records[] = $row;
        }
        return $records;
    }

    public function getUserRolesByCurrentUserOrUserId($uid = null)
    {
        $uid = ($uid == null) ? $_SESSION['user']['id'] : $uid;
        return $this->User->load($uid)->one();
    }

}

//$u = new User();
//print_r($u->getUserByUsername('demo'));
