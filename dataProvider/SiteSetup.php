<?php
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
class SiteSetup {
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

	function __construct(){
		//error_reporting(0);
		chdir($_SESSION['site']['root']);
	}







	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************
	//*****************************************************************************************




	//*****************************************************************************************
	// Ckeck Sites Folder cmod 777
	//*****************************************************************************************
	function check_perms(){
		chmod($this->sitesDir, 0777);
	    clearstatcache();
    	if(substr(sprintf('%o', fileperms($this->sitesDir)), -4) == '0777'){
    		return true; 
    	}else{
		    return false;
	    }
	}

	//*****************************************************************************************
	// Return Last Error as Json back to ExtJs
	//*****************************************************************************************
	function displayError(){
		if ($this->err || $this->conn->errorInfo()){
			if($this->err){
				$error = array('success' => false, 'msg' => $this->err);
				echo json_encode($error, JSON_FORCE_OBJECT);
				exit;
			}else{
				$error = $this->conn->errorInfo();
				if($error[2]){
					$this->dropDatabase();
					$error = array('success' => false, 'msg' => $error[1].' - '.$error[2] );
					echo json_encode($error, JSON_FORCE_OBJECT);
					exit;
				}
			}
		}
	}

	//*****************************************************************************************
	// Test Databases Connections
	//*****************************************************************************************
	function testConn() {
		switch ($this->connType) {
			case 'user':
				$this->DatabaseConn();
			break;
			case 'root';
				$this->rootDatabaseConn();
			break;
		}
		if (!$this->displayError()){
			echo '{"success":true,"msg":"Congratulation! Your Database Credentials are Valid"}';
			exit;
		}
	}

	//*****************************************************************************************
	// This is the Root Database Connection
	//*****************************************************************************************
	function rootDatabaseConn() {
		try {
		$this->conn = new PDO("mysql:host=".$this->dbHost.";port=".$this->dbPort,$this->rootUser,$this->rootPass);
		} catch (PDOException $e) {
    		$this->err = $e->getMessage();
    		if($e != null){
				$this->displayError();
			}
		}
	}

	//*****************************************************************************************
	// This is the Database User Connection
	//*****************************************************************************************
	function DatabaseConn() {
		try {
			$this->conn = new PDO("mysql:host=".$this->dbHost.";
								   		 port=".$this->dbPort.";
								 	   dbname=".$this->dbName,$this->dbUser,$this->dbPass);
		} catch (PDOException $e) {
    		$this->err = $e->getMessage();
			if($e != null){
				$this->displayError();
			}
		}
	}

	//*****************************************************************************************
	// Create new database and dump data
	//*****************************************************************************************
	function createDatabase() {
		$this->conn->exec("CREATE DATABASE ".$this->dbName."");
		return $this->displayError();
	}

	//*****************************************************************************************
	// Drop new database - this method is called if and error is found during the instalation
	//*****************************************************************************************
	function dropDatabase() {
		if($this->connType == 'root') {
			$this->conn->exec("DROP DATABASE ".$this->dbName."");
		}
	}

	//*****************************************************************************************
	// Create new database user and grat privileges to the new database
	//*****************************************************************************************
	function createDatabaseUser() {
		$this->conn->exec("GRANT ALL PRIVILEGES ON ".$this->dbName.".*
					 					  		TO '".$this->dbUser."'@'localhost'
   					 		   		 IDENTIFIED BY '".$this->dbPass."'
   					 	   		  	 WITH GRANT OPTION;");
		return $this->displayError();
	}

	//*****************************************************************************************
	// lets dump install.sql data inside the database
	//*****************************************************************************************
	function sqldump() {
		if (file_exists($sqlFile = "sql/install.sql")) {
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
	function defaultLanguage() {
		$this->conn->query("");
	}

	//*****************************************************************************************
	// lets check the sites folder and make sure we can the new site is not alredy created
	// and make sure we can write on it before we star working with the database
	//*****************************************************************************************
	function siteCk(){
		if($this->check_perms()){
			if (file_exists($this->sitesDir."/".$this->siteName)){
				echo '{"success":false,"msg":"Error - The site <b>'.$this->siteName.'</b> already exist."}';
				exit;
			}
		}else{
			echo '{"success":false,"msg":"Error - Unable to write on sites folder."}';
			exit;
		}
	}

	//*****************************************************************************************
	// Create new AES Key - this key is unique for every site
	//*****************************************************************************************
	function createRandomKey() {
	    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
	    srand((double)microtime()*1000000);
	    $i = 0;
	    $key = "" ;
	    while ($i <= 31) {
	        $num = rand() % 33;
	        $tmp = substr($chars, $num, 1);
	        $key = $key . $tmp;
	        $i++;
	    }
		if ($key == ""){
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
	function buildConf(){
		if (file_exists($conf = "lib/site_setup/conf.php")){
			$buffer = file_get_contents($conf);
			$search  = array('%host%','%user%', '%pass%', '%db%', '%port%', '%key%');
			$replace = array($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, $this->dbPort, $this->AESkey);
			$this->newConf = str_replace($search, $replace, $buffer);
			return;
		}else{
			$this->dropDatabase();
			echo '{"success":false,"msg":"Error - Unable to find default conf.php"}';
			exit;
		}
	}

	//*****************************************************************************************
	// Create Site Conf File
	//*****************************************************************************************
	function createSiteConf() {
		$workingDir = $this->sitesDir."/".$this->siteName;
		mkdir($workingDir, 0777, true);
		chmod($workingDir, 0777);
		$conf_file = ($workingDir."/conf.php");
		$handle = fopen($conf_file, "w");
		fwrite($handle, $this->newConf);
		fclose($handle);
		chmod($conf_file, 0644);
		if(!file_exists($conf_file)){
			echo '{"success":false,"msg":"Error - The conf.php file for <b>'.$this->siteName.'</b> could not be created."}';
			exit;
		}
        return;
	}

	//*****************************************************************************************
	// Create Admin User and AES Encrypted Password Using Site AESkey
	//*****************************************************************************************
	function adminUser(){
		require_once("classes/AES.php");
		$admin = $this->adminUser;
		$aes = new AES($this->AESkey);
		$ePass = $aes->encrypt($this->adminPass);
		$this->conn->exec("INSERT INTO users
							  	   SET username 	='".$admin."',
							  	       fname		='Adminstrator',
							  	  	   password 	='".$ePass."',
							  	       authorized 	='1'");
		return $this->displayError();
	}

	function installationCk() {
		if (!$this->displayError()){
			$error = array('success' => true, 'msg' => 'Congratulation! GaiaEHR is installed please click Ok to Login.');
			echo json_encode($error, JSON_FORCE_OBJECT);
			exit;
		}
	}
	//*****************************************************************************************
	// Method to Install a Site With Root Access and Creating Database
	//*****************************************************************************************
	function rootInstall(){
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
	function dbInstall(){
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
