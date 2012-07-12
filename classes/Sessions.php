<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 7/11/12
 * Time: 12:03 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name ('GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'].'/classes/dbHelper.php');
class Sessions
{
	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * Creates the dbHelper instance
	 */
	function __construct(){
	    $this->db = new dbHelper();
	    return;
	}

	public function loginSession(){
		$date = time();
		$data['sid'] = session_id();
		$data['uid'] = $_SESSION['user']['id'];
		$data['login'] = $date;
		$data['last_request	'] = $date;
		$this->db->setSQL($this->db->sqlBind($data,'users_sessions','I'));
		$this->db->execLog();
		$_SESSION['session_id'] = $this->db->lastInsertId;
		return true;
	}

	public function updateSession(){
		$_SESSION['inactive']['timeout'] = time();
		$data['last_request	'] = $_SESSION['inactive']['timeout'];
		$this->db->setSQL($this->db->sqlBind($data,'users_sessions','U', array('id' => $_SESSION['session_id'])));
		$this->db->execOnly();
		return true;
	}

	public function logoutSession(){
		$data['logout'] = time();
		$this->db->setSQL($this->db->sqlBind($data,'users_sessions','U', array('id' => $_SESSION['session_id'])));
		$this->db->execOnly();
		return true;
	}

	public function logoutInactiveUsers(){
		$now = time();
		$foo = $now - $_SESSION['inactive']['time'];
		$this->db->setSQL("UPDATE users_sessions SET logout = '$now' WHERE last_request < $foo AND logout IS NULL");
		$this->db->execOnly();
		return true;
	}
}
//
//$s = new Sessions();
//$s->logoutInactiveUsers();