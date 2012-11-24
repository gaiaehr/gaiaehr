<?php
/*
 GaiaEHR (Electronic Health Records)
 authProcedures.php
 Athenticate Procedures dataProvider
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
include_once ($_SESSION['root'] . '/classes/Sessions.php');
include_once ($_SESSION['root'] . '/classes/Crypt.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
class authProcedures
{

	private $session;

	function __construct()
	{
		$this->session = new Sessions();
	}

	/**
	 * @param stdClass $params
	 * @return int
	 */
	public function login(stdClass $params)
	{
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
				'success' => false, 'type' => 'error', 'message' => 'Possible hack, please use the Logon Screen.'
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
		if(strlen($params->authPass) >= 11){
			return array(
				'success' => false, 'type' => 'error', 'message' => 'Possible hack, please use the Logon Screen.'
			);
		}
		//-------------------------------------------
		// Simple check username
		//-------------------------------------------
		if(!$params->authUser){
			return array(
				'success' => false, 'type' => 'error', 'message' => 'The username field can not be in blank. Try again.'
			);
		}
		//-------------------------------------------
		// Simple check password
		//-------------------------------------------
		if(!$params->authPass){
			return array(
				'success' => false, 'type' => 'error', 'message' => 'The password field can not be in blank. Try again.'
			);
		}
		//-------------------------------------------
		// Find the AES key in the selected site
		// And include the rest of the remaining
		// variables to connect to the database.
		//-------------------------------------------
		define('_GaiaEXEC', 1);
		chdir($_SESSION['root']);
		include_once ('registry.php');
		include_once ('classes/AES.php');
		include_once ('classes/dbHelper.php');
		$fileConf = 'sites/' . $params->site . '/conf.php';
		if(file_exists($fileConf)){
			/** @noinspection PhpIncludeInspection */
			include_once ($fileConf);
			$db  = new dbHelper();
			$err = $db->getError();
			if(!is_array($err)){
				return array(
					'success' => false, 'type' => 'error', 'message' => 'For some reason, I can\'t connect to the database.'
				);
			}
			// Do not stop here!, continue with the rest of the code.
		} else {
			return array(
				'success' => false, 'type' => 'error', 'message' => 'No configuration file found for site <span style="font-weight:bold">' . $params->site . '</span>.<br>Please double check URL or contact support desk.'
			);
		}
		//-------------------------------------------
		// remove empty space from username and password
		//-------------------------------------------
		$params->authUser = str_replace(' ', '', $params->authUser);
		$params->authPass = str_replace(' ', '', $params->authPass);
		//-------------------------------------------
		// Convert the password to AES and validate
		//-------------------------------------------
		$aes = new AES($_SESSION['site']['AESkey']);
		//-------------------------------------------
		// Username & password match
		//-------------------------------------------
		$db->setSQL("SELECT id, username, title, fname, mname, lname, email, password
                         FROM users
        		        WHERE username   = '$params->authUser'
        		          AND authorized = '1'
        		        LIMIT 1");
		$user = $db->fetchRecord();
		if($params->authPass != $aes->decrypt($user['password'])){
			return array(
				'success' => false, 'type' => 'error', 'message' => 'The username or password you provided is invalid.'
			);
		} else {
			//-------------------------------------------
			// Change some User related variables and go
			//-------------------------------------------
			$_SESSION['user']['name']  = $user['title'] . " " . $user['lname'] . ", " . $user['fname'] . " " . $user['mname'];
			$_SESSION['user']['id']    = $user['id'];
			$_SESSION['user']['email'] = $user['email'];
			$_SESSION['user']['site']  = $params->site;
			$_SESSION['user']['auth']  = true;
			//-------------------------------------------
			// Also fetch the current version of the
			// Application & Database
			//-------------------------------------------
			$sql = "SELECT * FROM version LIMIT 1";
			$db->setSQL($sql);
			$version                          = $db->fetchRecord();
			$_SESSION['ver']['codeName']      = $version['v_tag'];
			$_SESSION['ver']['major']         = $version['v_major'];
			$_SESSION['ver']['rev']           = $version['v_patch'];
			$_SESSION['ver']['minor']         = $version['v_minor'];
			$_SESSION['ver']['database']      = $version['v_database'];
			$_SESSION['site']['localization'] = $params->lang;
			$_SESSION['site']['checkInMode']  = $params->checkInMode;
			$_SESSION['timeout']              = time();
			$session                          = new Sessions();
			$token = Crypt::encrypt('{"uid":'.$user['id'].',"sid":'.$session->loginSession().',"site":"'.$params->site.'"}');
			$_SESSION['inactive']['timeout'] = time();
			return array(
				'success' => true,
				'token' => $token,
				'user' => array(
					'id' => $_SESSION['user']['id'],
					'name' => $_SESSION['user']['name'],
					'email' => $_SESSION['user']['email'],
				)
			);
		}
	}

	/**
	 * @static
	 * @return mixed
	 */
	public function unAuth()
	{
		$s = new Sessions();
		$p = new Patient();
		$s->logoutSession();
		$p->patientChartInByPid($_SESSION['patient']['pid']);
		session_unset();
		session_destroy();
		return;
	}

	/**
	 * @static
	 * @return int
	 */
	public function ckAuth()
	{
		$_SESSION['site']['flops']++;
		//****************************************************************
		// If the session has passed 60 flops, with out any activity exit
		// the application.
		//
		// return an exit code
		//****************************************************************
		if($_SESSION['site']['flops'] < 300){
			$session = new Sessions();
			$session->updateSession();
			return array('authorized' => true);
		} else {
			$this->unAuth();
			return array('authorized' => false);
		}
	}

	public function getSites()
	{
		$rows = array();
		foreach($_SESSION['sites']['sites'] as $row){
			$site['site_id'] = $row;
			$site['site']    = $row;
			array_push($rows, $site);
		}
		return $rows;
	}

}
