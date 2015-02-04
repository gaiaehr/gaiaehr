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
			return array('success' => false, 'type' => 'error', 'message' => 'Possible hack, please use the Logon Screen.');
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
			return array('success' => false, 'type' => 'error', 'message' => 'Possible hack, please use the Logon Screen.');
		}
		//-------------------------------------------
		// Simple check username
		//-------------------------------------------
		if(!$params->authUser){
			return array('success' => false, 'type' => 'error', 'message' => 'The username field can not be in blank. Try again.');
		}
		//-------------------------------------------
		// Simple check password
		//-------------------------------------------
		if(!$params->authPass){
			return array('success' => false, 'type' => 'error', 'message' => 'The password field can not be in blank. Try again.');
		}
		//-------------------------------------------
		// remove empty spaces single and double quotes from username and password
		//-------------------------------------------
		$params->authUser = trim(str_replace(array('\'', '"'), '', $params->authUser));
		$params->authPass = trim(str_replace(array('\'', '"'), '', $params->authPass));

		//-------------------------------------------
		// Username & password match
		//-------------------------------------------
		$u = MatchaModel::setSenchaModel('App.model.administration.User');
		$user = $u->load(array('username' => $params->authUser, 'authorized' => 1), array('id', 'username', 'title', 'fname', 'mname', 'lname', 'email', 'facility_id', 'npi', 'password'))->one();

		if($user === false || $params->authPass != $user['password']){
			return array('success' => false, 'type' => 'error', 'message' => 'The username or password you provided is invalid.');
		} else{
			//-------------------------------------------
			// Change some User related variables and go
			//-------------------------------------------
			$_SESSION['user']['name'] = trim($user['title'] . ' ' . $user['lname'] . ', ' . $user['fname'] . ' ' . $user['mname']);
			$_SESSION['user']['id'] = $user['id'];
			$_SESSION['user']['email'] = $user['email'];
			$_SESSION['user']['facility'] = ($params->facility == 0 ? $user['facility_id'] : $params->facility);
			$_SESSION['user']['localization'] = $params->lang;
			$_SESSION['user']['npi'] = $user['npi'] ;
			$_SESSION['user']['site'] = $params->site;
			$_SESSION['user']['auth'] = true;
			//-------------------------------------------
			// Also fetch the current version of the
			// Application & Database
			//-------------------------------------------
//			$sql = "SELECT * FROM version LIMIT 1";
//			$db->setSQL($sql);
//			$version = $db->fetchRecord();
//			$_SESSION['ver']['codeName'] = $version['v_tag'];
//			$_SESSION['ver']['major'] = $version['v_major'];
//			$_SESSION['ver']['rev'] = $version['v_patch'];
//			$_SESSION['ver']['minor'] = $version['v_minor'];
//			$_SESSION['ver']['database'] = $version['v_database'];
			$_SESSION['site']['localization'] = $params->lang;
			$_SESSION['site']['checkInMode'] = $params->checkInMode;
			$_SESSION['timeout'] = time();
			$_SESSION['user']['token'] = MatchaUtils::__encrypt('{"uid":' . $user['id'] . ',"sid":' . $this->session->loginSession() . ',"site":"' . $params->site . '"}');
			$_SESSION['inactive']['timeout'] = time();

			unset($db);

			return array(
				'success' => true,
				'token' => $_SESSION['user']['token'],
				'user' => array(
					'id' => $_SESSION['user']['id'],
					'name' => $_SESSION['user']['name'],
					'email' => $_SESSION['user']['email'],
					'facility' => $_SESSION['user']['facility'],
				    'localization' => $params->lang
				)
			);
		}
	}

	/**
	 * @static
	 * @return mixed
	 */
	public function unAuth(){
		$this->session->logoutSession();
		session_unset();
		session_destroy();
		return;
	}

	/**
	 * @static
	 * @return int
	 */
	public function ckAuth(){
		//MatchaModel::setSenchaModel('App.model.patient.HCFAOptions');
//		if(!isset($_SESSION['site']['flops'])) $_SESSION['site']['flops'] = 0;
//		$_SESSION['site']['flops']++;
		//****************************************************************
		// If the session has passed 60 flops, with out any activity exit
		// the application.
		//
		// return an exit code
		//****************************************************************
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
