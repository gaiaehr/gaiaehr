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

include_once(ROOT . '/dataProvider/Person.php');
include_once(ROOT . '/dataProvider/ACL.php');

class User {

	/**
	 * @var MatchaCUP
	 */
	private $u;

	/**
	 * @var ACL
	 */
	private $acl;

	function __construct(){
		$this->acl = new ACL();
        if(!isset($this->u))
            $this->u = MatchaModel::setSenchaModel('App.model.administration.User');
	}

	public function getUsers($params){
		$users = $this->u->load($params)->all();
		foreach($users['data'] as $index => $user){
			$user['fullname'] = Person::fullname($user['fname'], $user['mname'], $user['lname']);
			unset($user['password'], $user['pwd_history1'], $user['pwd_history2']);
			$users['data'][$index] = (object) $user;
		}
		return $users;
	}

	public function getUser($params){
		$user = $this->u->load($params)->one();
		if($user !== false){
			$user = isset($user['data']) ? $user['data'] : $user;
			$user['fullname'] = Person::fullname($user['fname'], $user['mname'], $user['lname']);
			unset($user['password'], $user['pwd_history1'], $user['pwd_history2']);
		}
		return $user;
	}

	public function getUserByUid($uid){
		$user = $this->u->load($uid)->one();
		if($user !== false){
			$user = isset($user['data']) ? $user['data'] : $user;
			$user['fullname'] = Person::fullname($user['fname'], $user['mname'], $user['lname']);
			unset($user['password'], $user['pwd_history1'], $user['pwd_history2']);
		}
		return $user;
	}

	public function addUser(stdClass $params){
		try{
			if(!$this->usernameExist($params->username)){

				unset($params->fullname, $params->pwd_history1, $params->pwd_history2);
				$user = (object) $this->u->save($params);
				$user = (object) isset($user->data) ? $user->data : $user;
				unset($user->password, $user->pwd_history1, $user->pwd_history2);
				$user->fullname = Person::fullname($user->fname, $user->mname, $user->lname);
				$user->password = '';
				return (object) $user;
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
		return (object) $user;
	}

	public function updatePassword(stdClass $params){
		$user = $this->u->load($params->id)->one();

		if($user === false){
			return ['success' => false, 'message' => 'user not found'];
		}
		$user = isset($user['data']) ? $user['data'] : $user;
		if($user['password'] != $params->old_password){
			return ['success' => false, 'message' => 'wrong_password_error'];
		}

		if( $user['password'] == $params->new_password ||
			$user['pwd_history1'] == $params->new_password ||
			$user['pwd_history2'] == $params->new_password){
			return ['success' => false, 'message' => 'password_history_error'];
		}

		$rec = new stdClass();
		$rec->id = $params->id;
		$rec->password = $params->new_password;
		$rec->pwd_history1 = $user['password'];
		$rec->pwd_history2 = $user['pwd_history1'];
		$this->u->save($rec);
		return ['success' => true];
	}

	public function usernameExist($username){
		$user = $this->u->load(['username' => $username])->one();
		return $user !== false;
	}

	public function getCurrentUserId(){
		return isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
	}

	public function getCurrentUserTitleLastName(){
		$user = $this->u->load($this->getCurrentUserId(), ['title', 'lname'])->one();
		return $user['title'] . ' ' . $user['lname'];
	}

	public function getUserNameById($id){
		$user = $this->u->load($id)->one();
		return $user['title'] . ' ' . $user['lname'];
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
		$user = $this->u->load($this->getCurrentUserId(), ['id', 'npi', 'title', 'fname', 'mname', 'lname', 'username', 'code'])->one();
		return $user;
	}

	public function updateMyAccount(stdClass $params){
		$data = new $params;
		unset($data->password, $data->pwd_history1, $data->pwd_history2);
		return $this->u->save($data);
	}

	public function verifyUserPass($pass, $uid = null){
		$user = $this->u->load(
			[
				'id' => isset($uid) ? $uid : $_SESSION['user']['id'],
				'authorized' => '1'
			],
			[
				'password'
			]
		)->one();
		return $user['password'] == $pass;
	}

	public function getProviders(){
		$records = [];
		$records[] = ['name' => 'All', 'id' => 'all'];
		$users = $this->u->load(['role_id' => 2])->all();
		foreach($users As $row){
			$row['name'] = $this->getUserNameById($row['id']);
			$records[] = $row;
		}
		return $records;
	}

	public function getActiveProviders(){
//		$records = array();
//		$records[] = array('option_name' => 'Select', 'option_value' => '');
		$this->u->addFilter('npi', '', '!=');
		$this->u->addFilter('active', 1);
//		$users = $this->u->load()->all();
//		foreach($users As $row){
//			$foo = array();
//			$foo['option_name'] = $row['title'] . Person::fullname($row['fname'],$row['mname'],$row['lname']);
//			$foo['option_value'] = $row['id'];
//			$records[] = $foo;
//		}
		return $this->u->load()->all();
	}

	public function getUserRolesByCurrentUserOrUserId($uid = null){
		$user = $this->u->load($uid == null ? $_SESSION['user']['id'] : $uid)->one();
		return $user;
	}

	public function getUserByNPI($npi){
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'NPI';
		$params->filter[0]->value = $npi;
		return $this->getUser($params);
	}

	public function userLiveSearch($params){
		$acls = isset($params->acl) ? explode('&' , $params->acl) : false;

		if($acls === false){
			$params->query = $params->query . '%';
			$this->u->sql('SELECT `u`.*, `ar`.`role_name` AS role FROM users as u
					    LEFT JOIN `acl_roles` AS ar ON `ar`.`id` = `u`.`role_id`
						    WHERE `u`.`fname` LIKE ?
						       OR `u`.`lname` LIKE ?
						       OR `u`.`username` LIKE ?');
			$records = $this->u->all([$params->query, $params->query, $params->query ]);
		}else{
			foreach($acls as &$acl){
				$acl = '`ap`.`perm_key` = \'' . $acl . '\'';
			}
			$count = count($acls);
			$where = implode(' OR ',  $acls);
			$sql = "SELECT `u`.*, `ar`.`role_name` AS role FROM users AS u
                 	LEFT JOIN `acl_roles` AS ar ON `ar`.`id` = `u`.`role_id`
 					WHERE `u`.`id` IN (
					    SELECT  `up`.`id` FROM `users` AS up
					    LEFT JOIN `acl_role_perms` AS arp ON `arp`.`role_id` = `up`.`role_id`
					    LEFT JOIN `acl_permissions` AS ap ON `ap`.`id` = `arp`.`perm_id`
 						WHERE `arp`.`value` = 1 AND ( {$where} )
					   	GROUP BY `up`.`id`
					    HAVING COUNT(`up`.`id`) = {$count}
					) AND (
		                fname LIKE ? OR lname LIKE ? OR username LIKE ?
	                )";

			$this->u->sql($sql);
			$params->query = $params->query . '%';
			$records = $this->u->all([ $params->query, $params->query, $params->query ]);
		}

		return [
			'total' => count($records),
		    'data' => array_slice($records, $params->start, $params->limit)
		];
	}

	public function getUsersByAcl($acl){

		$acls = explode('&' , $acl);
		foreach($acls as &$acl){
			$acl = '`ap`.`perm_key` = \'' . $acl . '\'';
		}
		$count = count($acls);
		$where = implode(' OR ',  $acls);

		$sql = "SELECT `u`.*, `ar`.`role_name` AS role FROM users AS u
                 	LEFT JOIN `acl_roles` AS ar ON `ar`.`id` = `u`.`role_id`
 					WHERE `u`.`id` IN (
					    SELECT  `up`.`id` FROM `users` AS up
					    LEFT JOIN `acl_role_perms` AS arp ON `arp`.`role_id` = `up`.`role_id`
					    LEFT JOIN `acl_permissions` AS ap ON `ap`.`id` = `arp`.`perm_id`
 						WHERE `arp`.`value` = 1 AND ( {$where} )
					   	GROUP BY `up`.`id`
					    HAVING COUNT(`up`.`id`) = {$count}
					) AND (
		                active = 1
	                )";

		$records = $this->u->sql($sql)->all();

		return [
			'total' => count($records),
			'data' => $records
		];
	}
}
