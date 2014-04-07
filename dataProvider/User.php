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

include_once(dirname(__FILE__) . '/Person.php');
include_once(dirname(__FILE__) . '/ACL.php');

class User {

	/**
	 * @var MatchaCUP
	 */
	private $u;

	/**
	 * @var MatchaHelper
	 */
	private $db;

	/**
	 * @var ACL
	 */
	private $acl;

	function __construct(){
		$this->db = new MatchaHelper();
		$this->acl = new ACL();
		$this->u = MatchaModel::setSenchaModel('App.model.administration.User');
	}

	public function getUsers($params){
		$users = $this->u->load($params)->all();
		foreach($users as $index => $user){
			$users[$index]['fullname'] = Person::fullname($user['fname'], $user['mname'], $user['lname']);
			unset($users[$index]['password'], $users[$index]['pwd_history1'], $users[$index]['pwd_history2']);
		}
		return $users;
	}

	public function getUser($params){
		$user = $this->u->load($params)->all();
		$user['fullname'] = Person::fullname($user['fname'], $user['mname'], $user['lname']);
		unset($user['password'], $user['pwd_history1'], $user['pwd_history2']);
		return $user;
	}

	public function addUser(stdClass $params){
		try{
			if(!$this->usernameExist($params->username)){
				unset($params->fullname, $params->pwd_history1, $params->pwd_history2);
				$user = $this->u->save($params);
				unset($user['password'], $user['pwd_history1'], $user['pwd_history2']);
				$user['fullname'] = Person::fullname($user['fname'], $user['mname'], $user['lname']);
				$user['password'] = '';
				return $user;
			} else{
				throw new Exception("Username \"$params->username\" exist, please try a different username");
			}
		} catch(Exception $e){
			return $e;
		}
	}

	public function updateUser(stdClass $params){
		unset($params->pwd_history1, $params->pwd_history2);
		if(isset($params->password) && $params->password == ''){
			unset($params->password);
		}
		$user = $this->u->save($params);
//		unset($user['password'], $user['pwd_history1'], $user['pwd_history2']);
		return $this->u;
	}

	public function updatePassword(stdClass $params){
		$user = $this->u->load($params->id)->one();

		if($user === false){
			return array('success' => false, 'message' => 'user not found');
		}

		if($user['password'] != $params->old_password){
			return array('success' => false, 'message' => 'wrong_password_error');
		}

		if( $user['password'] == $params->new_password ||
			$user['pwd_history1'] == $params->new_password ||
			$user['pwd_history2'] == $params->new_password){
			return array('success' => false, 'message' => 'password_history_error');
		}

		$rec = new stdClass();
		$rec->id = $params->id;
		$rec->password = $params->new_password;
		$rec->pwd_history1 = $user['password'];
		$rec->pwd_history2 = $user['pwd_history1'];
		$this->u->save($rec);
		return array('success' => true);
	}

	public function usernameExist($username){
		$user = $this->u->load(array('username' => $username))->one();
		return !empty($user);
	}

	public function getCurrentUserId(){
		return $_SESSION['user']['id'];
	}

	public function getCurrentUserTitleLastName(){
		$userResult = $this->u->load($this->getCurrentUserId(), array('title', 'lname'))->one();
		return $userResult['title'] . ' ' . $userResult['lname'];
	}

	public function getUserNameById($id){
		$userResult = $this->u->load($id)->one();
		return $userResult['title'] . ' ' . $userResult['lname'];
	}

	public function getUserFullNameById($id){
		$user = $this->u->load($id)->one();
		return Person::fullname($user['fname'], $user['mname'], $user['lname']);
	}

	public function getCurrentUserData(){
		$user = $this->u->load($this->getCurrentUserId())->one();
		unset($user['password'], $user['pwd_history1'], $user['pwd_history2']);
		return $user;
	}

	public function getCurrentUserBasicData(){
		return $this->u->load($this->getCurrentUserId(), array('id', 'npi', 'title', 'fname', 'mname', 'lname'))->one();
	}

	public function updateMyAccount(stdClass $params){
		$data = new $params;
		unset($data->password, $data->pwd_history1, $data->pwd_history2);
		return $this->u->save($data);
	}

	public function verifyUserPass($pass, $uid = null){
		$user = $this->u->load(
			array(
				'id' => isset($uid) ? $uid : $_SESSION['user']['id'],
				'authorized' => '1'
			),
			array(
				'password'
			)
		)->one();
		return $user['password'] == $pass;
	}

	public function getProviders(){
		$records = array();
		$records[] = array('name' => 'All', 'id' => 'all');
		foreach($this->u->load(array('role_id' => 2))->all() As $row){
			$row['name'] = $this->getUserNameById($row['id']);
			$records[] = $row;
		}
		return $records;
	}

	public function getActiveProviders(){
		$records = array();
		$records[] = array('option_name' => 'Select', 'option_value' => '');
		foreach($this->u->load(array('role_id' => 2, 'active' => 1))->all() As $row){
			$foo = array();
			$foo['option_name'] = $row['title'] . Person::fullname($row['fname'],$row['mname'],$row['lname']);
			$foo['option_value'] = $row['id'];
			$records[] = $foo;
		}
		return $records;
	}

	public function getUserRolesByCurrentUserOrUserId($uid = null){
		return $this->u->load($uid == null ? $_SESSION['user']['id'] : $uid)->one();
	}
}