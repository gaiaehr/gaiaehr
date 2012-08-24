<?php
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
class SiteSetup
{
	private $conn;
	private $err;
	private $sitesDir = 'sites';
	private $dbPrefix;
	private $AESkey;
	private $newConf;
	var $siteName;
	var $connType;
	var $dbUser;
	var $dbPass;
	var $dbHost;
	var $dbPort;
	var $dbName;
	var $rootUser;
	var $rootPass;
	var $adminUser;
	var $adminPass;

	function __construct()
	{
		chdir($_SESSION['site']['root']);
	}

	public function checkDatabaseCredentials(stdClass $params)
	{
		if(isset($params->rootUser)) {
			$success = $this->rootDatabaseConn($params->dbHost, $params->dbPort, $params->rootUser, $params->rootPass);
		} else {
			$success = $this->databaseConn($params->dbHost, $params->dbPort, $params->dbName, $params->dbUser, $params->dbPass);
		}
		//TODO: check if database exist
		//$params->dbName;
		return array('success' => $success, 'dbInfo' => $params);

	}

	function databaseConn($host, $port, $dbName, $dbUser, $dbPass)
	{
		try {
			$this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $dbUser, $dbPass);
			return true;
		} catch(PDOException $e) {
			$this->err = $e->getMessage();
			return false;
		}
	}

	public function rootDatabaseConn($host, $port, $rootUser, $rootPass)
	{
		try {
			$this->conn = new PDO("mysql:host=$host;port=$port", $rootUser, $rootPass);
			return true;
		} catch(PDOException $e) {
			$this->err = $e->getMessage();
			return false;
		}
	}

	public function checkRequirements()
	{
		$row = array();
		// check if ...
		$status = (empty($_SESSION['site']['sites']) ? 'Ok' : 'Fail');
		$row[] = array('msg'=> 'GaiaEHR is not installed', 'status'=> $status);

		// verified that php 5.2.0 or later is installed
		$status = (version_compare(phpversion(), "5.3.2", ">=") ? 'Ok' : 'Fail');
		$row[] = array('msg'=> 'PHP 5.3.2 + installed', 'status'=> $status);

		// Check if get_magic_quotes_gpc is off
		$status = (get_magic_quotes_gpc() != 1 ? 'Ok' : 'Fail');
		$row[] = array('msg'=> 'get_magic_quotes_gpc off/disabled', 'status'=> $status);

		// try chmod sites folder and check chmod after that
		chmod('sites', 777);
		$status = (substr(sprintf('%o', fileperms('sites')), -4) ? 'Ok' : 'Fail');
		$row[] = array('msg'=> 'Sites folder is writable', 'status'=> $status);

		// check if safe_mode is off
		$status = (!ini_get('safe_mode') ? 'Ok' : 'Fail');
		$row[] = array('msg'=> 'PHP safe mode off', 'status'=> $status);

		// check if ZipArchive is enable
		$status = (class_exists('ZipArchive') ? 'Ok' : 'Fail');
		$row[] = array('msg'=> 'PHP class ZipArchive', 'status'=> $status);

		return $row;
	}











	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************



	function check_perms()
	{
		chmod($this->sitesDir, 0777);
		clearstatcache();
		if(substr(sprintf('%o', fileperms($this->sitesDir)), -4) == '0777') {
			return true;
		} else {
			return false;
		}
	}



	//*****************************************************************************************
	// Create new database and dump data
	//*****************************************************************************************
	function createDatabase()
	{
		$this->conn->exec("CREATE DATABASE " . $this->dbName . "");
		return $this->displayError();
	}

	//*****************************************************************************************
	// Drop new database - this method is called if and error is found during the instalation
	//*****************************************************************************************
	function dropDatabase()
	{
		if($this->connType == 'root') {
			$this->conn->exec("DROP DATABASE " . $this->dbName . "");
		}
	}

	//*****************************************************************************************
	// Create new database user and grat privileges to the new database
	//*****************************************************************************************
	function createDatabaseUser()
	{
		$this->conn->exec("GRANT ALL PRIVILEGES ON " . $this->dbName . ".*
					 					  		TO '" . $this->dbUser . "'@'localhost'
   					 		   		 IDENTIFIED BY '" . $this->dbPass . "'
   					 	   		  	 WITH GRANT OPTION;");
		return $this->displayError();
	}

	//*****************************************************************************************
	// lets dump install.sql data inside the database
	//*****************************************************************************************
	function sqldump()
	{
		if(file_exists($sqlFile = "sql/install.sql")) {
			$query = file_get_contents($sqlFile);
			$this->conn->query($query);
			$this->displayError();
		} else {
			$this->dropDatabase();
			echo '{"success":false,"msg":"Error - Unable to find install.sql"}';
			exit;
		}
	}

	//*****************************************************************************************
	// Set Default Language  //  TODO  //
	//*****************************************************************************************
	function defaultLanguage()
	{
		$this->conn->query("");
	}

	//*****************************************************************************************
	// lets check the sites folder and make sure we can the new site is not alredy created
	// and make sure we can write on it before we star working with the database
	//*****************************************************************************************
	function siteCk()
	{
		if($this->check_perms()) {
			if(file_exists($this->sitesDir . "/" . $this->siteName)) {
				echo '{"success":false,"msg":"Error - The site <b>' . $this->siteName . '</b> already exist."}';
				exit;
			}
		} else {
			echo '{"success":false,"msg":"Error - Unable to write on sites folder."}';
			exit;
		}
	}

	//*****************************************************************************************
	// Create new AES Key - this key is unique for every site
	//*****************************************************************************************
	function createRandomKey()
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime() * 1000000);
		$i   = 0;
		$key = "";
		while($i <= 31) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$key = $key . $tmp;
			$i++;
		}
		if($key == "") {
			$this->dropDatabase();
			echo '{"success":false,"msg":"Error - There was an error generating AES Key foe site encrytion."}';
			exit;
		} else {
			$this->AESkey = $key;
		}

	}

	//*****************************************************************************************
	// Bild The New conf.php
	//*****************************************************************************************
	function buildConf()
	{
		if(file_exists($conf = "lib/site_setup/conf.php")) {
			$buffer        = file_get_contents($conf);
			$search        = array('%host%', '%user%', '%pass%', '%db%', '%port%', '%key%');
			$replace       = array($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, $this->dbPort, $this->AESkey);
			$this->newConf = str_replace($search, $replace, $buffer);
			return;
		} else {
			$this->dropDatabase();
			echo '{"success":false,"msg":"Error - Unable to find default conf.php"}';
			exit;
		}
	}

	//*****************************************************************************************
	// Create Site Conf File
	//*****************************************************************************************
	function createSiteConf()
	{
		$workingDir = $this->sitesDir . "/" . $this->siteName;
		mkdir($workingDir, 0777, true);
		chmod($workingDir, 0777);
		$conf_file = ($workingDir . "/conf.php");
		$handle    = fopen($conf_file, "w");
		fwrite($handle, $this->newConf);
		fclose($handle);
		chmod($conf_file, 0644);
		if(!file_exists($conf_file)) {
			echo '{"success":false,"msg":"Error - The conf.php file for <b>' . $this->siteName . '</b> could not be created."}';
			exit;
		}
		return;
	}

	//*****************************************************************************************
	// Create Admin User and AES Encrypted Password Using Site AESkey
	//*****************************************************************************************
	function adminUser()
	{
		require_once("classes/AES.php");
		$admin = $this->adminUser;
		$aes   = new AES($this->AESkey);
		$ePass = $aes->encrypt($this->adminPass);
		$this->conn->exec("INSERT INTO users
							  	   SET username 	='" . $admin . "',
							  	       fname		='Adminstrator',
							  	  	   password 	='" . $ePass . "',
							  	       authorized 	='1'");
		return $this->displayError();
	}

	function installationCk()
	{
		if(!$this->displayError()) {
			$error = array('success' => true, 'msg' => 'Congratulation! GaiaEHR is installed please click Ok to Login.');
			echo json_encode($error, JSON_FORCE_OBJECT);
			exit;
		}
	}

	//*****************************************************************************************
	// Method to Install a Site With Root Access and Creating Database
	//*****************************************************************************************
	function rootInstall()
	{
		$this->siteCk();
		$this->rootDatabaseConn();
		$this->createDatabase();
		$this->createDatabaseUser();
		$this->DatabaseConn();
		$this->sqldump();
		$this->createRandomKey();
		$this->buildConf();
		$this->createSiteConf();
		$this->adminUser();
		$this->installationCk();
	}

	//*****************************************************************************************
	// Method to Install a Site With Databse User Access
	//*****************************************************************************************
	function dbInstall()
	{
		$this->siteCk();
		$this->DatabaseConn();
		$this->sqldump();
		$this->createRandomKey();
		$this->buildConf();
		$this->createSiteConf();
		$this->adminUser();
		$this->installationCk();
	}

} // end class siteSetup
?>
