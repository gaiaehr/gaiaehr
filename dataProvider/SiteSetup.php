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

include_once(ROOT . '/classes/FileManager.php');
include_once(ROOT . '/dataProvider/ACL.php');

class SiteSetup {
	/**
	 * @var PDO
	 */
	private $conn;
	private $err;

	function __construct() {
		chdir(ROOT);
        error_reporting(-1);
        ini_set('display_errors', 'On');
        if(file_exists(ROOT.'/log/install_error_log.txt'))
        {
            if(is_writable(ROOT.'/log/install_error_log.txt'))
            {
                ini_set('error_log', ROOT.'/log/install_error_log.txt');
            }
        }
	}

	/*
	 * Verify: checkDatabaseCredentials
	 */
	public function checkDatabaseCredentials(stdClass $params) {
		if(isset($params->rootUser)){
			$success = $this->rootDatabaseConn(
                $params->dbHost,
                $params->dbPort,
                $params->rootUser,
                $params->rootPass
            );

			if($success){
				$sth = $this->conn->prepare("USE $params->dbName");
				if($sth->execute() !== false){
					return [
						'success' => false,
						'error' => 'Database name is already in use. Please, use a different name. ' . $this->err
					];
				}
			}
		} else {
			$success = $this->databaseConn(
                $params->dbHost,
                $params->dbPort,
                $params->dbName,
                $params->dbUser,
                $params->dbPass
            );
		}

		if($success){
			$maxAllowPacket = $this->setMaxAllowedPacket();
			if($maxAllowPacket !== true){
				return [
					'success' => false,
					'error' => 'Could not set the MySQL <strong>max_allowed_packet</strong> variable.<br>GaiaEHR requires to set max_allowed_packet to 50M or more.<br>Please check my.cnf or my.ini, also you can install GaiaEHR using MySQL root user<br>max_allowed_packet = ' . $maxAllowPacket
				];
			}
			return [
				'success' => true,
				'dbInfo' => $params
			];
		} else {
			return [
				'success' => false,
				'error' => 'Could not connect to SQL server!!! Please, check database information and try again. ' . $this->err
			];
		}
	}

	function setMaxAllowedPacket() {
		$stm = $this->conn->prepare("SELECT @@global.max_allowed_packet AS size");
		$stm->execute();
		$pkg = $stm->fetch(PDO::FETCH_ASSOC);
		if($pkg['size'] < 209715200){
			$stm = $this->conn->prepare("SET @@global.max_allowed_packet = 52428800000");
			$stm->execute();
			$error = $this->conn->errorInfo();
			if(isset($error[2])){
				return $pkg['size'];
			} else {
				return true;
			}
		}
		return true;
	}

	function databaseConn($host, $port, $dbName, $dbUser, $dbPass) {
		try {
			$this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $dbUser, $dbPass, [
				PDO::MYSQL_ATTR_LOCAL_INFILE => 1,
				PDO::ATTR_PERSISTENT => false,
				PDO::ATTR_TIMEOUT => 7600
			]);
			return true;
		} catch(PDOException $e) {
			$this->err = $e->getMessage();
			return false;
		}
	}

	/*
	 * Make a connection to the database: rootDatabaseConn
	 * We use PDO to make the connection, PDO is a internal library of PHP that make
	 * connections
	 * to any database please refer to MatchaHelper.php to learn more about PHP PDO.
	 */
	public function rootDatabaseConn($host, $port, $rootUser, $rootPass) {
		try {
			$this->conn = new PDO("mysql:host=$host;port=$port", $rootUser, $rootPass);
			return true;
		} catch(PDOException $e) {
			$this->err = $e->getMessage();
			return false;
		}
	}

	function is__writable($path) {
		if ($path{strlen($path)-1}=='/'){
			return $this->is__writable($path . uniqid(mt_rand()) . '.tmp');
		}

		if (file_exists($path)) {
			if (!($f = @fopen($path, 'r+'))){
				return false;
			}

			fclose($f);
			return true;
		}

		if (!($f = @fopen($path, 'w'))){
			return false;
		}

		fclose($f);
		unlink($path);
		return true;
	}

	/*
	 * Verify: checkRequirements
	 */
	public function checkRequirements() {
		try {
			$row = [];
			$status = (version_compare(phpversion(), '5.4.0', '>=') ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'PHP 5.4 + installed',
				'status' => $status
			];
			// Check if get_magic_quotes_gpc is off
			$status = (get_magic_quotes_gpc() != 1 ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'get_magic_quotes_gpc off/disabled',
				'status' => $status
			];
			// try chmod sites folder and check chmod after that
			$status = ($this->is__writable(dirname(dirname(__FILE__)).'/sites/') ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'Sites dir writable by Web Server',
				'status' => $status
			];
			// check if safe_mode is off
			$status = (!ini_get('safe_mode') ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'PHP safe mode off',
				'status' => $status
			];
            // check if ZipArchive
            $status = (class_exists('ZipArchive') ? 'Ok' : 'Fail');
            $row[] = [
                'msg' => 'PHP class ZipArchive',
                'status' => $status
            ];
			// check if PDO
			$status = (class_exists('PDO') ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'PHP PDO installed',
				'status' => $status
			];
			// check if ZipArchive is enable
			$status = (function_exists("gzcompress") ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'PHP class zlib',
				'status' => $status
			];
			// check if ZipArchive is enable
			$status = (function_exists('curl_version') ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'PHP Curl installed',
				'status' => $status
			];
			// check for mcrypt is installed in PHP
			$status = (function_exists('mcrypt_encrypt') ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'PHP MCrypt installed',
				'status' => $status
			];
			// check if GD exists
			$status = (extension_loaded('gd') && function_exists('gd_info') ? 'Ok' : 'Fail');
			$row[] = [
				'msg' => 'PHP GD Installed',
				'status' => $status
			];
            // check if MYSQL_ATTR_MAX_BUFFER_SIZE parameter is available in PDO
//            $status = (defined( 'PDO::MYSQL_ATTR_MAX_BUFFER_SIZE' ) ? 'Ok' : 'Fail');
//            $row[] = [
//                'msg' => 'PHP PDO MYSQL_ATTR_MAX_BUFFER_SIZE Parameter is not available',
//                'status' => $status
//            ];

			return $row;
		}
        catch(Exception $Error)
        {
            error_log(print_r($Error->getMessage(), true));
			return $Error->getMessage();
		}

	}

    /**
     * Process to create the site directory and it's sub-directories
     *
     * @param $siteId
     * @return array
     */
	public function setSiteDirBySiteId($siteId) {
		$siteDir = "sites/$siteId";

        if(!$this->createDirectory($siteDir))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating site directory'
            ];
        }
        if(!$this->createDirectory("$siteDir/patients"))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating PATIENT directory'
            ];
        }
        if(!$this->createDirectory("$siteDir/documents"))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating DOCUMENT directory'
            ];
        }
        if(!$this->createDirectory("$siteDir/temp"))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating TEMP directory'
            ];
        }
        if(!$this->createDirectory("$siteDir/trash"))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating TRASH directory'
            ];
        }
        if(!$this->createDirectory("$siteDir/log"))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating LOG directory'
            ];
        }
        if(!$this->createDirectory("$siteDir/cert/"))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating CERT directory'
            ];
        }
        if(!$this->touch("$siteDir/log/error_log.txt"))
        {
            return [
                'success' => false,
                'error' => 'Something went wrong creating LOG file'
            ];
        }
        return ['success' => true];
	}

    /**
     * createDirectory
     * Tries to  create a directory with it's permissions, if not it will return false.
     *
     * @param $dirPath
     * @return bool
     */
    private function createDirectory($dirPath){
        try
        {
            if(!file_exists($dirPath)) mkdir($dirPath, 0755, true);
            if(!is_writable($dirPath)) chmod($dirPath, 0755);
            return true;
        }
        catch(Exception $Error)
        {
            error_log($Error->getMessage());
            return false;
        }
    }

    /**
     * touch
     * A custome version of touch function of PHP
     *
     * @param $file
     * @return bool
     */
    private function touch($file)
    {
        try
        {
            if(!file_exists($file)) touch($file);
            return true;
        }
        catch(Exception $Error)
        {
            return false;
        }
    }

	public function createDatabaseStructure(stdClass $params) {
        try{
            if(isset($params->rootUser) && $this->rootDatabaseConn(
					$params->dbHost,
					$params->dbPort,
					$params->rootUser,
					$params->rootPass))
			{
                $sth = $this->conn->prepare("
                CREATE DATABASE `$params->dbName`;
                GRANT ALL PRIVILEGES ON $params->dbName.* To '$params->dbUser'@'$params->dbHost' IDENTIFIED BY '$params->dbPass';
			    FLUSH PRIVILEGES;
			");
                $sth->execute();
                $error = $this->conn->errorInfo();

                if($this->databaseConn(
					$params->dbHost,
					$params->dbPort,
					$params->dbName,
					$params->dbUser,
                    $params->dbPass))
                {

                    if($this->loadDatabaseStructure()){
                        return ['success' => true];
                    } else {
                        FileManager::rmdir_recursive("sites/$params->siteId");
                        throw new Exception($this->conn->errorInfo());
                    }

                } else {

                    FileManager::rmdir_recursive("sites/$params->siteId");
                    throw new Exception($this->err);
                }
            } elseif($this->databaseConn(
                $params->dbHost,
                $params->dbPort,
                $params->dbName,
                $params->dbUser,
                $params->dbPass))
            {
                if($this->loadDatabaseStructure()){
                    return ['success' => true];
                } else {

                    FileManager::rmdir_recursive("sites/$params->siteId");
                    throw new Exception($this->conn->errorInfo());
                }
            } else {

                FileManager::rmdir_recursive("sites/$params->siteId");
                throw new Exception($this->err);
            }
        }
        catch(Exception $Error)
        {
            error_log(print_r($Error->getMessage(), true));
            return [
                'success' => false,
                'error' => $Error->getMessage()
            ];
        }

	}

	public function loadDatabaseStructure() {
		ini_set('memory_limit', '-1');
		if(file_exists($sqlFile = 'sql/gaiadb_install_structure.sql')){
			$query = file_get_contents($sqlFile);
			$sth = $this->conn->prepare($query);
			if($sth->execute() !== false){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function loadDatabaseData(stdClass $params) {
        try {
            ini_set('memory_limit', '-1');
            if ($this->databaseConn(
                $params->dbHost,
                $params->dbPort,
                $params->dbName,
                $params->dbUser,
                $params->dbPass))
            {
                if (file_exists($sqlFile = 'sql/gaiadb_install_data.sql')) {
                    $query = file_get_contents($sqlFile);
                    if ($this->conn->exec($query) !== false) {
                        return ['success' => true];
                    } else {
                        FileManager::rmdir_recursive("sites/$params->siteId");
                        if (isset($params->rootUser))
                            $this->dropDatabase($params->dbName);
                        throw new Exception($this->conn->errorInfo());
                    }

                } else {
                    FileManager::rmdir_recursive("sites/$params->siteId");
                    if (isset($params->rootUser))
                        $this->dropDatabase($params->dbName);
                    throw new Exception('Unable find installation data file');
                }
            } else {
                FileManager::rmdir_recursive("sites/$params->siteId");
                throw new Exception($this->err);
            }
        }
        catch(Exception $Error)
        {
            error_log(print_r($Error->getMessage(), true));
            return [
                'success' => false,
                'error' => $Error->getMessage()
            ];
        }
	}

	function dropDatabase($dbName) {
		$sth = $this->conn->prepare("DROP DATABASE $dbName");
		$sth->execute();
	}

	public function createConfigurationFile($params)
    {
        try
        {
            if (file_exists($conf = 'sites/conf.example.php'))
            {
                if (($params->AESkey = ACL::createRandomKey()) !== false) {
                    $buffer = file_get_contents($conf);
                    $search = [
                        '#host#',
                        '#user#',
                        '#pass#',
                        '#db#',
                        '#port#',
                        '#key#',
                        '#lang#',
                        '#theme#',
                        '#timezone#',
                        '#sitename#',
                        '#hl7Port#'
                    ];
                    $replace = [
                        $params->dbHost,
                        $params->dbUser,
                        $params->dbPass,
                        $params->dbName,
                        $params->dbPort,
                        $params->AESkey,
                        $params->lang,
                        $params->theme,
                        $params->timezone,
                        $params->siteId,
                        9100
                        // TODO check other sites and +1 to the highest port
                    ];
                    $newConf = str_replace($search, $replace, $buffer);
                    $siteDir = "sites/$params->siteId";
                    $conf_file = ("$siteDir/conf.php");
                    $handle = fopen($conf_file, 'w');
                    fwrite($handle, $newConf);
                    fclose($handle);
                    chmod($conf_file, 0644);
                    if (file_exists($conf_file)) {
                        return [
                            'success' => true,
                            'AESkey' => $params->AESkey
                        ];
                    } else {
                        throw new Exception("Unable to create $siteDir/conf.php file");
                    }
                } else {
                    throw new Exception('Unable to Generate AES 32 bit key');
                }
            } else {
                throw new Exception('Unable to Find sites/conf.example.php');
            }
        }
        catch(Exception $Error)
        {
            error_log(print_r($Error->getMessage(), true));
            return [
                'success' => false,
                'error' => $Error->getMessage()
            ];
        }
	}

	public function createSiteAdmin($params) {
        try
        {
            include_once(ROOT . '/sites/' . $params->siteId . '/conf.php');
            Matcha::connect([
                'host' => site_db_host,
                'port' => site_db_port,
                'name' => site_db_database,
                'user' => site_db_username,
                'pass' => site_db_password,
                'app' => ROOT . '/app'
            ]);

            Matcha::$__secretKey = defined('site_aes_key') ? site_aes_key : '';

            include_once(ROOT . '/dataProvider/User.php');

            $u = new User();
            $admin = new stdClass();
            $admin->title = 'Mr.';
            $admin->fname = 'Administrator';
            $admin->mname = '';
            $admin->lname = 'Administrator';
            $admin->username = $params->adminUsername;
            $admin->password = $params->adminPassword;
            $admin->authorized = 1;
            $admin->active = 1;
            $admin->role_id = 8;
            $admin->facility_id = 1;
            $u->addUser($admin);
            session_unset();
            session_destroy();
            return ['success' => true];
        }
        catch(Exception $Error)
        {
            error_log(print_r($Error->getMessage(), true));
            return [
                'success' => false,
                'error' => $Error->getMessage()
            ];
        }
	}

	public function loadCode($code) {
		include_once(ROOT . '/dataProvider/ExternalDataUpdate.php');
		$codes = new ExternalDataUpdate();
		$params = new stdClass();
		$params->codeType = $code;
		$foo = $codes->getCodeFiles($params);
		$params = new stdClass();
		$params->codeType = $foo[0]['codeType'];
		$params->version = $foo[0]['version'];
		$params->path = $foo[0]['path'];
		$params->date = $foo[0]['date'];
		$params->basename = $foo[0]['basename'];
		return $codes->updateCodes($params);
	}

}
