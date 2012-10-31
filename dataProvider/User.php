<?php
/*
 GaiaEHR (Electronic Health Records)
 User.php
 User dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/dataProvider/Person.php');
include_once ($_SESSION['root'] . '/classes/AES.php');
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
class User
{

	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * @var
	 */
	private $user_id;

	function __construct()
	{
		$this->db = new dbHelper();
		return;
	}

	/**
	 * @return AES
	 */
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
		$id = $this->getCurrentUserId();
		$this->db->setSQL("SELECT title, lname FROM users WHERE id = '$id'");
		$foo = $this->db->fetchRecord();
		$foo = $foo['title'] . ' ' . $foo['lname'];
		return $foo;
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
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$row['fullname'] = Person::fullname($row['fname'], $row['mname'], $row['lname']);
			unset($row['password'], $row['pwd_history1'], $row['pwd_history2']);
			array_push($rows, $row);
		}
		return $rows;
	}

	public function getUserNameById($id)
	{
		$this->db->setSQL("SELECT title, lname FROM users WHERE id = '$id'");
		$user     = $this->db->fetchRecord();
		$userName = $user['title'] . ' ' . $user['lname'];
		return $userName;
	}

	public function getUserFullNameById($id)
	{
		$this->db->setSQL("SELECT title, fname, mname, lname FROM users WHERE id = '$id'");
		$user     = $this->db->fetchRecord();
		$userName = Person::fullname($user['fname'], $user['mname'], $user['lname']);
		return $userName;
	}

	public function getCurrentUserData()
	{
		$id = $this->getCurrentUserId();
		$this->db->setSQL("SELECT * FROM users WHERE id = '$id'");
		$user = $this->db->fetchRecord();
		return $user;
	}

	public function getCurrentUserBasicData()
	{
		$id = $this->getCurrentUserId();
		$this->db->setSQL("SELECT id, title, fname, mname, lname FROM users WHERE id = '$id'");
		$user = $this->db->fetchRecord();
		return $user;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addUser(stdClass $params)
	{
		if(!$this->usernameExist($params->username)){
			$data = get_object_vars($params);
			unset($data['password']);
			$role['role_id'] = $data['role_id'];
			unset($data['id'], $data['role_id'], $data['fullname']);
			if($data['taxonomy'] == ''){
				unset($data['taxonomy']);
			}
			foreach($data as $key => $val){
				if($val == null || $val == ''){
					unset($data[$key]);
				}
			}
			$sql = $this->db->sqlBind($data, 'users', 'I');
			$this->db->setSQL($sql);
			$this->db->execLog();
			$params->id = $this->user_id = $this->db->lastInsertId;
			$params->fullname = Person::fullname($params->fname, $params->mname, $params->lname);
			if($params->password != ''){
				$this->changePassword($params->password);
			}
			$params->password = '';
			$role['user_id'] = $params->id;
			$sql             = $this->db->sqlBind($role, 'acl_user_roles', 'I');
			$this->db->setSQL($sql);
			$this->db->execLog();
			return $params;
		}else{
			return array('success' => false, 'error' => "Username \"$params->username\" exist, please try a different username");
		}
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateUser(stdClass $params)
	{
		$data             = get_object_vars($params);
		$params->password = '';
		$this->user_id   = $data['id'];
		$role['role_id'] = $data['role_id'];
		unset($data['id'], $data['role_id'], $data['fullname']);
		if($data['password'] != ''){
			$this->changePassword($data['password']);
		}
		unset($data['password']);
		$sql = $this->db->sqlBind($role, 'acl_user_roles', 'U', array('user_id' => $this->user_id));
		$this->db->setSQL($sql);
		$this->db->execLog();
		$sql = $this->db->sqlBind($data, 'users', 'U', array('id' => $this->user_id));
		$this->db->setSQL($sql);
		$this->db->execLog();
		return $params;

	}

	public function usernameExist($username){
		$this->db->setSQL("SELECT count(id) FROM users WHERE username = '$username'");
		$user = $this->db->fetchRecord();
		return $user['count(id)'] >= 1;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function chechPasswordHistory(stdClass $params)
	{
		$aes           = $this->getAES();
		$this->user_id = $params->id;
		$aesPwd        = $aes->encrypt($params->password);
		$this->db->setSQL("SELECT password, pwd_history1, pwd_history2  FROM users WHERE id='" . $this->user_id . "'");
		$pwds = $this->db->fetchRecord();
		if($pwds['password'] == $aesPwd || $pwds['pwd_history1'] == $aesPwd || $pwds['pwd_history2'] == $aesPwd){
			return array('error' => true);
		} else {
			return array('error' => false);
		}
	}

	/**
	 * @param $newpassword
	 * @return mixed
	 */
	public function changePassword($newpassword)
	{
		$aes    = $this->getAES();
		$aesPwd = $aes->encrypt($newpassword);
		$this->db->setSQL("SELECT password, pwd_history1 FROM users WHERE id='$this->user_id'");
		$pwds                = $this->db->fetchRecord();
		$row['password']     = $aesPwd;
		$row['pwd_history1'] = $pwds['password'];
		$row['pwd_history2'] = $pwds['pwd_history1'];
		$sql                 = $this->db->sqlBind($row, 'users', 'U', array('id' => $this->user_id));
		$this->db->setSQL($sql);
		$this->db->execLog();
		return;

	}

	public function changeMyPassword(stdClass $params)
	{
		$this->user_id = $params->id;
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
		$aes  = new AES($_SESSION['site']['AESkey']);
		$pass = $aes->encrypt($pass);
		$uid  = $_SESSION['user']['id'];
		$this->db->setSQL("SELECT username FROM users WHERE id = '$uid' AND password = '$pass' AND authorized = '1' LIMIT 1");
		$count = $this->db->rowCount();
		return ($count != 0) ? 1 : 2;
	}

	public function getProviders()
	{
		$this->db->setSQL("SELECT u.id, u.fname, u.lname, u.mname
                FROM acl_user_roles AS acl
                LEFT JOIN users AS u ON u.id = acl.user_id
                WHERE acl.role_id = '2'");
		$records   = array();
		$records[] = array(
			'name' => 'All', 'id' => 'all'
		);
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) As $row){
			$row['name'] = $this->getUserNameById($row['id']);
			$records[]   = $row;
		}
		return $records;
	}

	public function getUserRolesByCurrentUserOrUserId($uid = null)
	{
		$uid = ($uid == null) ? $_SESSION['user']['id'] : $uid;
		$this->db->setSQL("SELECT * FROM acl_user_roles WHERE user_id = '$uid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

}

//$u = new User();
//print_r($u->getUserByUsername('demo'));
