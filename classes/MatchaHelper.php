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
    session_cache_limiter('private');
    session_cache_expire(1);
    session_regenerate_id(false);
    session_name('GaiaEHR');
    session_start();
    setcookie(session_name(),session_id(),time()+86400, '/', null, false, true);
}
ini_set('max_input_time', '1500');
ini_set('max_execution_time', '1500');

require_once(ROOT . '/classes/Time.php');
require_once(ROOT . '/lib/Matcha/Matcha.php');

if(defined(('site_timezone'))) date_default_timezone_set(site_timezone);

class MatchaHelper extends Matcha {

	/**
	 * @var
	 */
	public $sql_statement;
	/**
	 * @var
	 */
	public $lastInsertId;
	/**
	 * @var string
	 */
	private $err;

	/**
	 * @brief       MatchaHelper constructor.
	 * @details     This method starts the connection with mysql server using
	 * $_SESSION values
	 *              during the login process.
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 */
	function __construct() {
		self::$__freeze = false;
		// Connect to the database
		// This is compatible with the old methods
		if(defined('site_db_type'))
        {
			self::connect([
				'host' => site_db_host,
				'port' => site_db_port,
				'name' => site_db_database,
				'user' => site_db_username,
				'pass' => site_db_password,
				'app' => ROOT . '/app'
			]);
		}

		self::$__secretKey = defined('site_aes_key') ? site_aes_key : '';

		if(isset(self::$__conn))
        {
			MatchaAudit::$__audit = true;
			MatchaAudit::$hookTable = 'audit_transaction_log';
			MatchaAudit::$hookClass = 'MatchaHelper';
			MatchaAudit::$hookMethod = 'storeAudit';
			MatchaModel::setSenchaModel('App.model.administration.TransactionLog');
		}
	}

	function __destruct() {
		//		self::$__conn = null;
	}

	/**
	 * function storeAudit($saveParams = array()):
	 * This method is (optional) will be called automatically by MatchaCUP
	 * to store the event log into the database.
	 *
	 * Basically when MatchaCUP->save or MatchaCUP->destroy is used it will look
	 * for this method and execute it and pass all the SQL statement and
	 * other parameters into a array to this method. Here you can execute the MatchaAudit
	 * class to save the event log or anything you want.
	 *
	 * The method should be established PUBLIC STATIC, this way it will not take more
	 * memory.
	 * @param array $saveParams
	 */
	public static function storeAudit($saveParams = [])
    {
        // Set the array index, even it exist or not.
        $saveParams['sql'] = isset($saveParams['sql']) ? $saveParams['sql'] : '';

		// get pid...
		if(isset($saveParams['data']['pid']))
        {
			$pid = $saveParams['data']['pid'];
		}
        else
        {
			$match = [];
			preg_match('/`pid`.*:W(\d*)/', $saveParams['sql'], $match);

			if(!empty($match))
            {
				preg_match('/:W(\d*)/', $match[0], $match);
				$pid = $saveParams['data'][$match[0]];
			}
            else
            {
				$pid = '0';
			}
		}

		// get eid...
		if(isset($saveParams['data']['eid']))
        {
			$eid = $saveParams['data']['eid'];
		}
        else
        {
			$match = [];
			preg_match('/`eid`.*:W(\d*)/', $saveParams['sql'], $match);

			if(!empty($match))
            {
				preg_match('/:W(\d*)/', $match[0], $match);
				$eid = $saveParams['data'][$match[0]];
			}
            else
            {
				$eid = '0';
			}
		}

		$uid = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '0';
		$fid = isset($_SESSION['user']['facility']) ? $_SESSION['user']['facility'] : '0';
		$date = Time::getLocalTime('Y-m-d H:i:s');
		$table = isset($saveParams['table']) ? $saveParams['table'] : '';
		$sql = $saveParams['sql'];
		$data = isset($saveParams['data']) ? serialize($saveParams['data']) : '';

	    $IP = self::getUserIP();

        if($IP == '::1'){
            $IP = '127.0.0.1';
        }

		MatchaAudit::$eventLogData = [
			'date' => $date,
			'pid' => $pid,
			'eid' => $eid,
			'uid' => $uid,
			'fid' => $fid,
			'event' => $saveParams['event'],
			'table_name' => $table,
			'sql_string' => $sql,
			'data' => $data,
			'ip' => $IP,
			'checksum' => sha1($date.$pid.$eid.$uid.$fid.$saveParams['event'].$table.$sql.$data.$IP)
		];
		MatchaAudit::auditSaveLog();

	}

	public static function getUserIP() {
		$client = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : false;
		$forward = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : false;
		$remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;

		if($client !== false && filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		} elseif($forward !== false && filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} elseif($remote !== false) {
			$ip = $remote;
		}else{
			return '0.0.0.0';
		}
		return $ip;
	}

	/**
	 * @brief       Set the SQL Statement.
	 * @details     This method set the SQL statement in
	 *              $this->sql_statement for other methods to use it
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @see         Logs::getLogs() for basic example and
	 * Patient::patientLiveSearch() for advance example.
	 *
	 * @param       $sql string statement to set
	 */
	public function setSQL($sql)
    {
		$this->sql_statement = $sql;
	}

	public function exec($sql)
    {
		return self::$__conn->exec($sql);
	}

	public function conn()
    {
		return self::$__conn;
	}

	/**
	 * @brief       SQL Bind.
	 * @details     This method is used to INSERT and UPDATE the database.
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @note        To eliminate fields that are not in the database you can use
	 * unset($b_array['field']);
	 * @warning     To UPDATE you can NOT pass the ID in the $b_array.
	 *              Make user to unset the ID before calling this method.
	 *
	 * @see         User::addUser() for Add example and  User::updateUser() for
	 * Update example.
	 *
	 * @param       array $BindFieldsArray containing a key that has to be the
	 * exact field on the data base, and it's value
	 * @param       string $Table A valid database table to make the SQL
	 * statement
	 * @param       string $InsertOrUpdate Insert or Update parameter. This has to
	 * options I = Insert, U = Update
	 * @param              $Where
	 * @return      string constructed SQL string
	 */
	public function sqlBind($BindFieldsArray, $Table, $InsertOrUpdate = 'I', $Where = null)
    {
		//		print '<pre>';
		if(isset($BindFieldsArray['__utma']))
			unset($BindFieldsArray['__utma']);
		if(isset($BindFieldsArray['__utmz']))
			unset($BindFieldsArray['__utmz']);
		if(isset($BindFieldsArray['GaiaEHR']))
			unset($BindFieldsArray['GaiaEHR']);
		/**
		 * Step 1 -  Create the INSERT or UPDATE Clause
		 */
		$InsertOrUpdate = strtolower($InsertOrUpdate);
		if($InsertOrUpdate == 'i')
        {
			$sql = 'INSERT INTO `' . $Table . '`';
		}
        elseif($InsertOrUpdate == 'u')
        {
			$sql = 'UPDATE `' . $Table . '`';
		}
        else
        {
            return "No update or insert command.";
        }

		/**
		 * Step 2 -  Create the SET clause
		 */
		$sql .= ' SET ';

		foreach($BindFieldsArray as $key => $value)
        {
			$value = (is_string($value) ? addslashes($value) : $value);
			if(is_array($Where))
            {
				if(!array_key_exists($key, $Where))
                {
					if($value == null || $value === 'null')
                    {
						$sql .= '`' . $key . '`' . '=NULL, ';
					}
                    else
                    {
						$value = preg_replace('/([0-9]{4}-[0-9]{2}-[0-9]{2})T([0-9]{2}:[0-9]{2}:[0-9]{2})/i', '${1} ${2}', trim($value));
						$sql .= '`' . $key . '`' . "='$value', ";
					}
				}
                else
                {
					return [
						'success' => false,
						'error' => 'Where value can not be updated. please make sure to unset it from the array'
					];
				}
			}
            else
            {
				if($value == null || $value === 'null')
                {
					$sql .= '`' . $key . '`' . '=NULL, ';
				}
                else
                {
					if(is_string($value))
                    {
						//$value = htmlspecialchars($value);
					}
					$sql .= '`' . $key . '`' . "='$value', ";
				}
			}
		}
		$sql = substr($sql, 0, -2);
		/**
		 * Step 3 - Create the WHERE clause, if applicable
		 */
		if($InsertOrUpdate == 'u' && $Where != null)
        {
			$sql .= ' WHERE ';
			if(is_array($Where))
            {
				$count = 0;
				foreach($Where as $key => $val)
                {
					$and = ($count == 0) ? '' : ' AND ';
					if(is_string($val))
                    {
						//$val = htmlspecialchars($val);
					}
					$sql .= $and . $key . '=\'' . $val . '\'';
					$count++;
				}
			}
            else
            {
				$sql .= $Where;
			}
		}
		/**
		 * Step 4 - return the sql statement
		 */
		return $sql;
	}

	/**
	 * @brief       Execute Statement "WITHOUT" returning records
	 * @details     Simple exec SQL Statement, with no Event LOG injection.
	 *              For example to execute an ALTER a table.
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @param       bool $setLastInsertId
	 * @return      array Connection error info if any
	 */
	public function execOnly($setLastInsertId = true)
    {
		if(!isset(self::$__conn))
			return [];
		self::$__conn->query($this->sql_statement);
		if($setLastInsertId)
			$this->lastInsertId = self::$__conn->lastInsertId();
		$err = self::$__conn->errorInfo();
		if($err[2])
        {
			return self::$__conn->errorInfo();
		}
        else
        {
			return $this->lastInsertId;
		}
	}

	/**
	 * @brief       Execute Log.
	 * @details     This method is used to INSERT, UPDATE, DELETE, and ALTER the
	 * database.
	 *              with a event log injection.
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @note        The Log Injection is automatic It tries to detect an insert,
	 * delete, alter and log the event
	 *
	 * @see         User::addUser() for Add example.
	 *
	 * @return      array Connection error info if any
	 */
	function execLog()
    {
		/**
		 * Execute the sql statement
		 */
		self::$__conn->query($this->sql_statement);
		if(stristr($this->sql_statement, 'INSERT') ||
            stristr($this->sql_statement, 'DELETE') ||
            stristr($this->sql_statement, 'UPDATE') ||
            stristr($this->sql_statement, 'LOAD') ||
            stristr($this->sql_statement, 'ALTER'))
        {
			$this->lastInsertId = self::$__conn->lastInsertId();
			$eventLog = "UNDEFINED";
			if(stristr($this->sql_statement, 'INSERT'))
				$eventLog = 'INSERT';
			if(stristr($this->sql_statement, 'DELETE'))
				$eventLog = 'DELETE';
			if(stristr($this->sql_statement, 'UPDATE'))
				$eventLog = 'UPDATE';
			if(stristr($this->sql_statement, 'ALTER'))
				$eventLog = 'ALTER';
			if(stristr($this->sql_statement, 'LOAD'))
				$eventLog = 'LOAD';
			/**
			 * Using the same, internal functions.
			 */
			$data['date'] = Time::getLocalTime('Y-m-d H:i:s');
			$data['uid'] = ((isset($_SESSION['user']) && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : '0');
			$data['fid'] = $_SESSION['user']['facility'];
			$data['event'] = $eventLog;
			$data['sql_string'] = $this->sql_statement;
			$data['checksum'] = crc32($this->sql_statement);
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$sqlStatement = $this->sqlBind($data, 'audit_transaction_log', 'I');
			$this->setSQL($sqlStatement);
			$this->execOnly(false);

		}
		return self::$__conn->errorInfo();
	}

	/**
	 * @brief       Fetch
	 * @details     This method is used to fetch only one record from the database
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @return      array of record or error if any
	 */
	function fetchRecord()
    {
		if(!isset(self::$__conn))
			return [];
		// Get all the records
		$recordSet = self::$__conn->query($this->sql_statement);
		$record = $recordSet->fetch(PDO::FETCH_ASSOC);
		$err = $recordSet->errorInfo();
		if($err[2])
			return $err;
		return $record;

	}

	/**
	 * @brief       Execute Statement.
	 * @details     This method is a simple SQL Statement, with no Event LOG
	 * injection
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @see         Logs::getLogs() for basic example and
	 * Patient::patientLiveSearch() for advance example.
	 *
	 * @param       int default to (PDO::FETCH_BOTH) Please see Fetch
	 *                  Style docs at <a
	 * href="http://php.net/manual/en/pdostatement.fetch.php">PDO Statement Fetch</a>
	 * @return      array of records, if error occurred return the error instead
	 */
	public function fetchRecords($fetchStyle = PDO::FETCH_BOTH)
    {
		if(!isset(self::$__conn))
			return [];
		$recordSet = self::$__conn->query($this->sql_statement);
		if(stristr($this->sql_statement, 'SELECT'))
        {
			$this->lastInsertId = self::$__conn->lastInsertId();
		}
		$records = $recordSet->fetchAll($fetchStyle);
		$err = $recordSet->errorInfo();
		if($err[2])
			return $err;
		return $records;
	}

	/**
	 * @brief       Row Count
	 * @details     This methods is used to query an statement and return the rows
	 * coount using PDO
	 *
	 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
	 * @version     Vega 1.0
	 *
	 * @note        count($sql) should be use instead of this method.
	 *              please refer to @ref Logs::getLogs() to see an example
	 *              of how to use count();
	 *
	 * @return      int The number of rows in a table
	 */
	function rowCount()
    {
		$recordSet = self::$__conn->query($this->sql_statement);
		return $recordSet->rowCount();
	}

}

$conn = Matcha::getConn();
if(!isset($conn)){
	new MatchaHelper();
}
