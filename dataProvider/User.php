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

if (!isset($_SESSION))
{
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
		try
		{
			$users = (object)R::load('users', $this->getCurrentUserId());
			return $users->title . ' ' . $users->lname;
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getUsers(stdClass $params)
	{
		$rows = array();
		try
		{
			$records = (array)R::getAll('SELECT u.*, r.role_id
	                             FROM users AS u
	                        LEFT JOIN acl_user_roles AS r ON r.user_id = u.id
	                            WHERE u.authorized = 1 OR u.username != ""
	                         ORDER BY u.username
	                            LIMIT :start,:records', array(
				':start' => $params->start,
				':records' => $params->limit
			));
			foreach ($records as $row)
			{
				$row['fullname'] = Person::fullname($row['fname'], $row['mname'], $row['lname']);
				unset($row['password'], $row['pwd_history1'], $row['pwd_history2']);
				array_push($rows, $row);
			}
			return $rows;
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	public function getUserNameById($id)
	{
		// like LINQ
		try
		{
			$user = R::$f->begin()->select('title, lname')->from('users')->where(' id = ? ')->put($id)->get('row');
			return $user['title'] . ' ' . $user['lname'];
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	public function getUserFullNameById($id)
	{
		// like LINQ
		try
		{
			$user = R::$f->begin()->select('title, fname, mname, lname')->from('users')->where(' id = ? ')->put($id)->get('row');
			return Person::fullname($user['fname'], $user['mname'], $user['lname']);
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	public function getCurrentUserData()
	{
		// like LINQ
		try
		{
			$id = $this->getCurrentUserId();
			$user = R::$f->begin()->select('*')->from('users')->where(' id = ? ')->put($id)->get('row');
			return $user;
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	public function getCurrentUserBasicData()
	{
		// like LINQ
		try
		{
			$id = $this->getCurrentUserId();
			$user = R::$f->begin()->select('id, title, fname, mname, lname')->from('users')->where(' id = ? ')->put($id)->get('row');
			return $user;
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addUser(stdClass $params)
	{
		if (!$this->usernameExist($params->username))
		{
			$data = get_object_vars($params);
			unset($data['password']);
			$role['role_id'] = $data['role_id'];
			unset($data['id'], $data['role_id'], $data['fullname']);
			if ($data['taxonomy'] == '')
				unset($data['taxonomy']);
			foreach ($data as $key => $val)
			{
				if ($val == null || $val == '')
				{
					unset($data[$key]);
				}
			}

			$sql = $this->db->sqlBind($data, 'users', 'I');
			$this->db->setSQL($sql);
			$this->db->execLog();
			$params->id = $this->user_id = $this->db->lastInsertId;
			$params->fullname = Person::fullname($params->fname, $params->mname, $params->lname);
			if ($params->password != '')
			{
				$this->changePassword($params->password);
			}
			$params->password = '';
			$role['user_id'] = $params->id;
			$sql = $this->db->sqlBind($role, 'acl_user_roles', 'I');
			$this->db->setSQL($sql);
			$this->db->execLog();
			return $params;
		}
		else
		{
			return $e;
		}
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateUser(stdClass $params)
	{
		try
		{
			$data = get_object_vars($params);
			$params->password = '';
			$this->user_id = $data['id'];
			$role['role_id'] = $data['role_id'];
			unset($data['id'], $data['role_id'], $data['fullname']);
			if ($data['password'] != '')
				$this->changePassword($data['password']);
			unset($data['password']);
			$sql = $this->db->sqlBind($role, 'acl_user_roles', 'U', array('user_id' => $this->user_id));
			$this->db->setSQL($sql);
			$this->db->execLog();
			$sql = $this->db->sqlBind($data, 'users', 'U', array('id' => $this->user_id));
			$this->db->setSQL($sql);
			$this->db->execLog();
			return $params;
		}
		catch(Exception $e)
		{
			return $e;
		}

	}

	public function usernameExist($username)
	{
		try
		{
			$user = R::$f->begin()->select('count(id)')->from('users')->where(' username = ? ')->put($username)->get('row');
			return $user['count(id)'] >= 1;
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function checkPasswordHistory(stdClass $params)
	{
		$aes = $this->getAES();
		$this->user_id = $params->id;
		$aesPwd = $aes->encrypt($params->password);

		$pwds = R::$f->begin()->select('password, pwd_history1, pwd_history2')->from('users')->where(' id = ? ')->put($this->user_id)->get('row');

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

		$pwds = R::$f->begin()->select('password, pwd_history1')->from('users')->where(' id = ? ')->put($this->user_id)->get('row');

		R::begin();
		try
		{
			$user = R::load('users', $this->user_id);
			$user->password = $aesPwd;
			$user->pwd_history1 = $pwds['password'];
			$user->pwd_history2 = $pwds['pwd_history1'];
			$id = R::store($user);
			R::commit();
			return array('success' => true);
		}
		catch(Exception $e)
		{
			R::rollback();
			return $e;
		}
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

		R::begin();
		$user = R::load('users', $params->id);
		try
		{
			$user = $data;
			R::store($user);
			R::commit();
			return array('success' => true);
		}
		catch(Exception $e)
		{
			R::rollback();
			return $e;
		}
	}

	public function verifyUserPass($pass)
	{
		try
		{
			$aes = new AES($_SESSION['site']['AESkey']);
			$pass = $aes->encrypt($pass);
			$uid = $_SESSION['user']['id'];
			$total = R::count('users', ' id = :id AND password = :password AND authorized = :authorized ', array(
				':id' => $uid,
				':password' => $pass,
				':authorized' => '1'
			));
			return ($total != 0) ? 1 : 2;
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

	public function getProviders()
	{
		$this->db->setSQL("SELECT u.id, u.fname, u.lname, u.mname
                FROM acl_user_roles AS acl
                LEFT JOIN users AS u ON u.id = acl.user_id
                WHERE acl.role_id = '2'");
		$records = array();
		$records[] = array(
			'name' => 'All',
			'id' => 'all'
		);
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) As $row)
		{
			$row['name'] = $this->getUserNameById($row['id']);
			$records[] = $row;
		}
		return $records;
	}

	public function getUserRolesByCurrentUserOrUserId($uid = null)
	{
		try
		{
			$uid = ($uid == null) ? $_SESSION['user']['id'] : $uid;
			return R::$f->begin()->select('*')->from('acl_user_roles')->where(' user_id = ? ')->put($uid)->get('row');
		}
		catch(Exception $e)
		{
			return $e;
		}
	}

}
