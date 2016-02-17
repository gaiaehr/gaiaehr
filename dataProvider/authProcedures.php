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

include_once(ROOT . '/classes/Sessions.php');
include_once(ROOT . '/classes/Crypt.php');
include_once(ROOT . '/dataProvider/Patient.php');

/**
 * set_error_handler it's a PHP function to overwrite the errors that PHP spit out, in costume way
 * TODO: This is a temporary fix, this function should be part of Match::Connect
 */
set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

class authProcedures {

	private $session;

	function __construct(){
		$this->session = new Sessions();
	}

	/**
	 * @param stdClass $params
	 * @return int
	 */
	public function login(stdClass $params){
		error_reporting(E_ALL);
		//-------------------------------------------
		// Check that the username do not pass
		// the maximum limit of the field.
		//
		// NOTE:
		// If this condition is met, the user did not
		// use the logon form. Possible hack.
		//-------------------------------------------
		if(strlen($params->authUser) >= 26){
			return array(
                'success' => false,
                'type' => 'error',
                'message' => 'Possible hack, please use the Logon Screen.'
            );
		}
		//-------------------------------------------
		// Check that the username do not pass
		// the maximum limit of the field.
		//
		// NOTE:
		// If this condition is met, the user did not
		// use the logon form. Possible hack.
		//-------------------------------------------
		if(strlen($params->authPass) >= 15){
			return array(
                'success' => false,
                'type' => 'error',
                'message' => 'Possible hack, please use the Logon Screen.'
            );
		}
		//-------------------------------------------
		// Simple check username
		//-------------------------------------------
		if(!$params->authUser){
			return array(
                'success' => false,
                'type' => 'error',
                'message' => 'The username field can not be in blank. Try again.'
            );
		}
		//-------------------------------------------
		// Simple check password
		//-------------------------------------------
		if(!$params->authPass){
			return array(
                'success' => false,
                'type' => 'error',
                'message' => 'The password field can not be in blank. Try again.'
            );
		}
		//-------------------------------------------
		// remove empty spaces single and double quotes from username and password
		//-------------------------------------------
		$params->authUser = trim(str_replace(array('\'', '"'), '', $params->authUser));
		$params->authPass = trim(str_replace(array('\'', '"'), '', $params->authPass));

		//-------------------------------------------
		// Username & password match
		// Only bring authorized and active users.
		//-------------------------------------------
		$u = MatchaModel::setSenchaModel('App.model.administration.User');
		$user = $u->load(
			array(
				'username' => $params->authUser,
				'authorized' => 1,
				'active' => 1
			),
			array(
				'id',
				'username',
				'title',
				'fname',
				'mname',
				'lname',
				'email',
				'facility_id',
				'npi',
				'password'
			)
		)->one();

		if($user === false || $params->authPass != $user['password']){
			return array(
                'success' => false,
                'type' => 'error',
                'message' => 'The username or password you provided is invalid.'
            );
		} else{
			//-------------------------------------------
			// Change some User related variables and go
			//-------------------------------------------
			$_SESSION['user']['name'] = trim($user['title'] . ' ' . $user['lname'] . ', ' . $user['fname'] . ' ' . $user['mname']);
			$_SESSION['user']['id'] = $user['id'];
			$_SESSION['user']['email'] = $user['email'];
			$_SESSION['user']['facility'] = (!isset($params->facility) || $params->facility == 0) ? $user['facility_id'] : $params->facility;
			$_SESSION['user']['localization'] = isset($params->lang) ? $params->lang : 'en_US';
			$_SESSION['user']['npi'] = $user['npi'] ;
			$_SESSION['user']['site'] = site_name;
			$_SESSION['user']['auth'] = true;
			$_SESSION['site']['localization'] = $_SESSION['user']['localization'];
			$_SESSION['site']['checkInMode'] = isset($params->checkInMode) ? $params->checkInMode: false;
			$_SESSION['timeout'] = time();
			$_SESSION['user']['token'] = MatchaUtils::__encrypt('{"uid":' . $user['id'] . ',"sid":' . $this->session->loginSession() . ',"site":"' . site_name . '"}');
			$_SESSION['inactive']['timeout'] = time();

			unset($db);

			return array(
				'success' => true,
				'token' => $_SESSION['user']['token'],
				'user' => array(
					'id' => $_SESSION['user']['id'],
					'name' => $_SESSION['user']['name'],
					'npi' => $_SESSION['user']['npi'],
					'site' => $_SESSION['user']['site'],
					'email' => $_SESSION['user']['email'],
					'facility' => $_SESSION['user']['facility'],
				    'localization' => $_SESSION['user']['localization']
				)
			);
		}
	}

	/**
	 * unAuth
	 * A method executed from GaiaEHR to logout the user and destroys the session
	 * @static
	 * @return mixed
	 */
	public function unAuth(){
		try
		{
			$this->session->logoutSession();
			session_unset();
			session_destroy();
			return;
		}
		catch(Exception $ErrorObject)
		{
			// TODO: Configure a way to return the Exceptions to the GaiaEHR Client
			return;
		}
	}

	/**
	 * @static
	 * @return int
	 */
	public function ckAuth(){

		if(isset($_SESSION['session_id'])){
			$this->session->updateSession();
			return array('authorized' => true);

		} elseif(isset($_SESSION['session_id']) && (isset($_SESSION['user']) && !$_SESSION['user']['auth'])){
			$this->unAuth();
			return array('authorized' => false);

		}else{
			return array('authorized' => false);
		}
	}

	public function getSites(){
		$rows = array();
		foreach($_SESSION['sites']['sites'] as $row){
			$site['site_id'] = $row;
			$site['site'] = $row;
			array_push($rows, $site);
		}
		return $rows;
	}

}
