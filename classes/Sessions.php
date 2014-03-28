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

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once(dirname(dirname(__FILE__)) . '/classes/MatchaHelper.php');
include_once(dirname(dirname(__FILE__)) . '/classes/Crypt.php');
include_once(dirname(dirname(__FILE__)) . '/dataProvider/User.php');

class Sessions {
	/**
	 * @var bool|MatchaCUP
	 */
	private $s;

	/**
	 * Creates the MatchaHelper instance
	 */
	function __construct(){
		$this->s = MatchaModel::setSenchaModel('App.model.administration.UserSessions');
		return;
	}

	public function loginSession(){
		$data = new stdClass();
		$date = time();
		$data->sid = session_id();
		$data->uid = $_SESSION['user']['id'];
		$data->login = $date;
		$data->last_request = $date;
		$record = $this->s->save($data);
		unset($data);
		return $_SESSION['session_id'] =  $record['id'];
	}

	public function setSessionByToken($token){
		$s = json_decode(Crypt::decrypt($token));
		$session = $this->s->load($s->sid)->one();
		if($session === false){
			$User = new User();
			$user = $User->getUser($session['uid']);
			$_SESSION['user']['name'] = $user['title'] . ' ' . $user['lname'] .  ', '  . $user['fname'] . ' ' . $user['mname'];
			$_SESSION['user']['id'] = $user['id'];
			$_SESSION['user']['email'] = $user['email'];
			$_SESSION['user']['site'] = $s->site;
			$_SESSION['user']['auth'] = true;
			unset($User, $user);
			return true;
		} else{
			return false;
		}
	}

	public function updateSession(){
		$data = new stdClass();
		$data->id = $_SESSION['session_id'];
		$data->last_request = $_SESSION['inactive']['timeout'] = time();
		Matcha::pauseLog(true);
		$this->s->save($data);
		Matcha::pauseLog(false);
		unset($data);
		return true;
	}

	public function logoutSession(){
		$data = new stdClass();
		$data->id = $_SESSION['session_id'];
		$data->logout = time();
		$this->s->save($data);
		unset($data);
		return true;
	}

	public function logoutInactiveUsers(){
		$now = time();
		$users = array();
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'last_request';
		$params->filter[0]->operator = '<';
		$params->filter[0]->value = ($now - $_SESSION['inactive']['time']);
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'logout';
		$params->filter[1]->value = null;
		$sessions = $this->s->load($params)->all();

		foreach($sessions as $session){
			if(isset($user['id'])){
				$users[] = array('uid' => $session['uid']);
				$data = new stdClass();
				$data->id = $session['id'];
				$data->logout = $now;
				$this->s->save($data);
				unset($data);
			}
		}
		unset($params);
		return $users;
	}

}
//$s = new Sessions();
//$s->setSessionByToken("uzUc7qJ4YHc6F76WfoRnJwSycND+CLaUVmL2AcdEyHniHzONcq2C70wo7A+oA8aw\/C\/Q8UrRPZ7rrrmNut482w==");
