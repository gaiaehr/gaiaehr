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

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');
include_once ($_SESSION['root'] . '/classes/Crypt.php');

class Sessions
{
	/**
	 * @var MatchaHelper
	 */
	private $db;

	/**
	 * Creates the MatchaHelper instance
	 */
	function __construct()
	{
		$this->db = new MatchaHelper();
		return;
	}

	public function loginSession()
	{
		$date                  = time();
		$data['sid']           = session_id();
		$data['uid']           = $_SESSION['user']['id'];
		$data['login']         = $date;
		$data['last_request']  = $date;
		$this->db->setSQL($this->db->sqlBind($data, 'users_sessions', 'I'));
		$this->db->execLog();
		$_SESSION['session_id'] = $this->db->lastInsertId;
		return $_SESSION['session_id'];
	}

	public function setSessionByToken($token)
	{
		$s = json_decode(Crypt::decrypt($token));
		$this->db->setSQL("SELECT s.id AS sid, s.uid AS uid, u.title, u.lname, u.fname, u.mname, u.email
							 FROM users_sessions AS s
						LEFT JOIN users AS u ON s.uid = u.id
							WHERE s.id = '$s->sid' AND s.logout IS NULL");
		$r = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		if(!empty($r)){
			$_SESSION['user']['name']  = $r['title'] . " " . $r['lname'] . ", " . $r['fname'] . " " . $r['mname'];
			$_SESSION['user']['id']    = $r['uid'];
			$_SESSION['user']['email'] = $r['email'];
			$_SESSION['user']['site']  = $s->site;
			$_SESSION['user']['auth']  = true;
			return true;
		}else{
			return false;
		}
	}

	public function updateSession()
	{
		$_SESSION['inactive']['timeout'] = time();
		$data['last_request'] = $_SESSION['inactive']['timeout'];
		$this->db->setSQL($this->db->sqlBind($data, 'users_sessions', 'U', array('id' => $_SESSION['session_id'])));
		$this->db->execOnly();
		return true;
	}

	public function logoutSession()
	{
		$data['logout'] = time();
		$this->db->setSQL($this->db->sqlBind($data, 'users_sessions', 'U', array('id' => $_SESSION['session_id'])));
		$this->db->execOnly();
		return true;
	}

	public function logoutInactiveUsers()
	{
		$now   = time();
		$foo   = $now - $_SESSION['inactive']['time'];
		$users = array();
		$this->db->setSQL("SELECT id, uid FROM users_sessions WHERE last_request < $foo AND logout IS NULL");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $user){
			if(isset($user['id'])){
				$id      = $user['id'];
				$users[] = array('uid' => $user['uid']);
				$this->db->setSQL("UPDATE users_sessions SET logout = '$now' WHERE id = '$id'");
				$this->db->execOnly();
			}
		}
		return $users;
	}

}
//$s = new Sessions();
//$s->setSessionByToken("uzUc7qJ4YHc6F76WfoRnJwSycND+CLaUVmL2AcdEyHniHzONcq2C70wo7A+oA8aw\/C\/Q8UrRPZ7rrrmNut482w==");
