<?php
/**
 * @brief       Database Helper Class.
 * @details     A PDO helper for GaiaEHR, contains custom function to manage the
 * database
 *              in GaiaEHR. PDO is new in PHP v5.
 *
 *              The PHP Data Objects (PDO) extension defines a lightweight,
 *              consistent interface for accessing databases in PHP.
 *              Each database driver that implements the PDO interface can expose
 * database-specific
 *              features as regular extension functions. Note that you cannot
 * perform any database
 *              functions using the PDO extension by itself;
 *              you must use a database-specific PDO driver to access a database
 * server.
 *
 *              PDO provides a data-access abstraction layer, which means that,
 *              regardless of which database you're using, you use the same
 * functions to issue queries
 *              and fetch data. PDO does not provide a database abstraction; it
 * does not rewrite
 *              SQL or emulate missing features.
 *              You should use a full-blown abstraction layer if you need that
 * facility.
 *
 *              PDO ships with PHP 5.1, and is available as a PECL extension for
 * PHP 5.0;
 *              PDO requires the new OO features in the core of PHP 5, and so
 * will not
 *              run with earlier versions of PHP.
 *
 * @author      Gino Rivera (Certun) <grivera@certun.com>
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.2
 * @copyright   Gnu Public License (GPLv3)
 *
 */
if (!isset($_SESSION))
{
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

ini_set('max_input_time', '1500');
ini_set('max_execution_time', '1500');
$timezone = (isset($_SESSION['site']['timezone']) ? $_SESSION['site']['timezone'] : 'UTC');
date_default_timezone_set($timezone);
include_once ($_SESSION['root'] . '/classes/Time.php');

class dbHelper
{

	/**
	 * @var
	 */
	public $sql_statement;
	/**
	 * @var
	 */
	public $lastInsertId;
	/**
	 * @var PDO
	 */
	public $conn;
	/**
	 * @var string
	 */
	private $err;

	/**
	 * MicroORM related variables
	 */
	private $workingTable;
	private $workingFields;
	private $workingDatabase;

	/**
	 * @brief       dbHelper constructor.
	 * @details     This method starts the connection with mysql server using
	 * $_SESSION values
	 *              during the login process.
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 */
	function __construct()
	{
		error_reporting(0);
		if (isset($_SESSION['site']['db']))
		{
			$host = (string)$_SESSION['site']['db']['host'];
			$port = (int)$_SESSION['site']['db']['port'];
			$dbName = (string)$_SESSION['site']['db']['database'];
			$dbUser = (string)$_SESSION['site']['db']['username'];
			$dbPass = (string)$_SESSION['site']['db']['password'];
			try
			{
				$this->conn = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbName, $dbUser, $dbPass, array(
					PDO::MYSQL_ATTR_LOCAL_INFILE => 1,
					PDO::ATTR_PERSISTENT => true
				));
			}
			catch(PDOException $e)
			{
				$this->err = $e->getMessage();
			}
		}
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
	 * @param       array  $BindFieldsArray  containing a key that has to be the
	 * exact field on the data base, and it's value
	 * @param       string $Table            A valid database table to make the SQL
	 * statement
	 * @param       string $InsertOrUpdate   Insert or Update parameter. This has to
	 * options I = Insert, U = Update
	 * @param              $Where
	 * @return      string constructed SQL string
	 */
	public function sqlBind($BindFieldsArray, $Table, $InsertOrUpdate = 'I', $Where = null)
	{
		if (isset($BindFieldsArray['__utma']))
			unset($BindFieldsArray['__utma']);
		if (isset($BindFieldsArray['__utmz']))
			unset($BindFieldsArray['__utmz']);
		if (isset($BindFieldsArray['GaiaEHR']))
			unset($BindFieldsArray['GaiaEHR']);
		/**
		 * Step 1 -  Create the INSERT or UPDATE Clause
		 */
		$InsertOrUpdate = strtolower($InsertOrUpdate);
		if ($InsertOrUpdate == 'i')
		{
			$sql = 'INSERT INTO `' . $Table . '`';
		}
		elseif ($InsertOrUpdate == 'u')
		{
			$sql = 'UPDATE `' . $Table . '`';
		}
		else
			return "No update or insert command.";
		/**
		 * Step 2 -  Create the SET clause
		 */
		$sql .= ' SET ';
		foreach ($BindFieldsArray as $key => $value)
		{
			$value = addslashes($value);
			if (isset($Where) && is_array($Where))
			{
				if (!array_key_exists($key, $Where))
				{
					if ($value == null || $value === 'null')
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
					return array(
						'success' => false,
						'error' => 'Where value can not be updated. please make sure to unset it from the array'
					);
				}
			}
			else
			{
				// TODO: remove this... after new version (above) is implemented throughout the
				// application
				if ($Where <> ($key . "='$value'") && $Where <> ($key . '=' . $value) && $Where <> ($key . '="' . $value . '"'))
				{
					if ($value == null || $value === 'null')
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
					return array(
						'success' => false,
						'error' => 'Where value can not be updated. please make sure to unset it from the array'
					);
				}
			}

		}
		$sql = substr($sql, 0, -2);
		/**
		 * Step 3 - Create the WHERE clause, if applicable
		 */
		if ($InsertOrUpdate == 'u' && $Where != null)
		{
			$sql .= ' WHERE ';
			if (is_array($Where))
			{
				$count = 0;
				foreach ($Where as $key => $val)
				{
					$and = ($count == 0) ? '' : ' AND ';
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
	 * @brief    SQL Select Builder.
	 * @details  This method is used to build Select statements for MySQL.
	 *
	 * @author   Gino Rivera (Certun) <grivera@certun.com>
	 * @version  Vega 1.0
	 *
	 * @param       $Table
	 * @param array $Fields
	 * @param null  $Where
	 * @param null  $Order
	 * @internal param $ (array)$Fields
	 * @internal param $ (array)$Order
	 * @internal param $ (array)$Where
	 * @return string
	 */
	public function sqlSelectBuilder($Table, $Fields = array('*'), $Where = null, $Order = null)
	{
		// Step 1 - Select clause and wrote down the fields
		$sqlReturn = 'SELECT ';
		foreach ($Fields as $key => $value)
			$sqlReturn .= $value . ', ';
		$sqlReturn = substr($sqlReturn, 0, -2);
		// Step 2 - From clause, table
		$sqlReturn .= ' FROM ' . $Table . ' ';
		// Step 3 - Having clause, filter the records
		if ($Where != null)
		{
			$sqlReturn .= ' HAVING ';
			foreach ($Where as $key => $value)
			{
				$sqlReturn .= '(' . $value . ')';
				$sqlReturn .= (is_int($key)) ? ' AND ' : ' ' . $key . ' ';
			}
			$sqlReturn = substr($sqlReturn, 0, -5);
		}
		// Step 4 - Order clause, sort the results
		if ($Order != null)
		{
			$sqlReturn .= ' ORDER BY ';
			foreach ($Order as $key => $value)
			{
				$sqlReturn .= (!is_int($key)) ? $value . ' ' . $key . ', ' : $value . ', ';
			}
			$sqlReturn = substr($sqlReturn, 0, -2);
		}
		return $sqlReturn;
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
		$this->conn->query($this->sql_statement);
		if ($setLastInsertId)
		{
			$this->lastInsertId = $this->conn->lastInsertId();
		}
        $err = $this->conn->errorInfo();
        if($err[2]){
            return $this->conn->errorInfo();
        }else{
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
		$this->conn->query($this->sql_statement);
		if (stristr($this->sql_statement, 'INSERT') || stristr($this->sql_statement, 'DELETE') || stristr($this->sql_statement, 'UPDATE') || stristr($this->sql_statement, 'LOAD') || stristr($this->sql_statement, 'ALTER'))
		{
			$this->lastInsertId = $this->conn->lastInsertId();
			$eventLog = "Event triggered but never defined.";
			if (stristr($this->sql_statement, 'INSERT'))
				$eventLog = 'Record insertion';
			if (stristr($this->sql_statement, 'DELETE'))
				$eventLog = 'Record deletion';
			if (stristr($this->sql_statement, 'UPDATE'))
				$eventLog = 'Record update';
			if (stristr($this->sql_statement, 'ALTER'))
				$eventLog = 'Table alteration';
			if (stristr($this->sql_statement, 'LOAD'))
				$eventLog = 'Record load';
			/**
			 * Using the same, internal functions.
			 */
			$data['date'] = Time::getLocalTime('Y-m-d H:i:s');
			$data['event'] = $eventLog;
			$data['comments'] = $this->sql_statement;
			$data['user'] = $_SESSION['user']['name'];
			$data['checksum'] = crc32($this->sql_statement);
			$data['facility'] = $_SESSION['site']['dir'];
			$data['patient_id'] = $_SESSION['patient']['pid'];
			$data['ip'] = $_SESSION['server']['REMOTE_ADDR'];
			$sqlStatement = $this->sqlBind($data, 'log', 'I');
			$this->setSQL($sqlStatement);
			$this->execOnly(false);

		}
		return $this->conn->errorInfo();
	}

	/**
	 * @brief       Execute Event
	 * @details     This method is used to Inject directly to the event log
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @param       string $eventLog event data to log
	 * @return      array Connection error info if any
	 */
	function execEvent($eventLog)
	{
		$data['date'] = Time::getLocalTime('Y-m-d H:i:s');
		$data['event'] = $eventLog;
		$data['comments'] = $this->sql_statement;
		$data['user'] = $_SESSION['user']['name'];
		$data['patient_id'] = $_SESSION['patient']['id'];
		$sqlStatement = $this->sqlBind($data, 'log', 'I');
		$this->setSQL($sqlStatement);
		$this->fetchRecords();
		return $this->conn->errorInfo();
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
		// Get all the records
		$recordSet = $this->conn->query($this->sql_statement);
		$err = $this->conn->errorInfo();
		if (!$err[2])
		{
			return $recordSet->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			return $err;
		}

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
		$recordSet = $this->conn->query($this->sql_statement);
		if (stristr($this->sql_statement, 'SELECT'))
		{
			$this->lastInsertId = $this->conn->lastInsertId();
		}
		$err = $this->conn->errorInfo();
		if (!$err[2])
		{
			return $recordSet->fetchAll($fetchStyle);
		}
		else
		{
			return $err;
		}
	}

	/**
	 * @brief       Fetch the last error.
	 * @details     If there was a problem with the connection it will return
	 *              the error message, if the was not a connection problem, it will
	 *              return a array with the code and message.
	 *
	 * @author      Gino Rivera (Certun) <grivera@certun.com>
	 * @version     Vega 1.0
	 *
	 * @return      array|string
	 */
	function getError()
	{
		if (!$this->err)
		{
			return $this->conn->errorInfo();
		}
		else
		{
			return $this->err;
		}
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
		$recordSet = $this->conn->query($this->sql_statement);
		return $recordSet->rowCount();
	}

	/**
	 * dbHelper MicroORM
	 * -----------------
	 * This new set of method inside the dbHelper will help the exchange of
	 * data between the database and the software, witch means that the developer
	 * will have to mess with the database anymore.
	 */

	/*
	 * MySQL Field Types
	 MySQL supports a number of column types, which may be grouped into three
	categories: numeric types, date and time types, and string (character) types.
	This section first gives an overview of the types available. Please refer to the
	MySQL manuals for more details.
	 TINYINT
	 A very small integer
	 The signed range is –128 to 127. The unsigned range is 0 to 255.
	 SMALLINT
	 A small integer
	 The signed range is –32768 to 32767. The unsigned range is 0 to 65535
	 MEDIUMINT
	 A medium-size integer
	 The signed range is –8388608 to 8388607. The unsigned range is 0 to 16777215
	 INT or INTEGER
	 A normal-size integer
	 The signed range is –2147483648 to 2147483647. The unsigned range is 0 to
	4294967295
	 BIGINT
	 A large integer
	 The signed range is –9223372036854775808 to 9223372036854775807. The unsigned
	range is 0 to 18446744073709551615
	 FLOAT
	 A small (single-precision) floating-point number. Cannot be unsigned
	 Ranges are –3.402823466E+38 to –1.175494351E-38, 0 and 1.175494351E-38 to
	3.402823466E+38. If the number of Decimals is not set or <= 24 it is a
	single-precision floating point number
	 DOUBLE,
	 DOUBLE PRECISION,
	 REAL
	 A normal-size (double-precision) floating-point number. Cannot be unsigned
	 Ranges are -1.7976931348623157E+308 to -2.2250738585072014E-308, 0 and
	2.2250738585072014E-308 to 1.7976931348623157E+308. If the number of Decimals is
	not set or 25 <= Decimals <= 53 stands for a double-precision floating point
	number
	 DECIMAL,
	 NUMERIC
	 An unpacked floating-point number. Cannot be unsigned
	 Behaves like a CHAR column: “unpacked” means the number is stored as a string,
	using one character for each digit of the value. The decimal point, and, for
	negative numbers, the ‘-‘ sign is not counted in Length. If Decimals is 0, values
	will have no decimal point or fractional part. The maximum range of DECIMAL
	values is the same as for DOUBLE, but the actual range for a given DECIMAL column
	may be constrained by the choice of Length and Decimals. If Decimals is left out
	it’s set to 0. If Length is left out it’s set to 10. Note that in MySQL 3.22 the
	Length includes the sign and the decimal point
	 DATE
	 A date
	 The supported range is ‘1000-01-01’ to ‘9999-12-31’. MySQL displays DATE values
	in ‘YYYY-MM-DD’ format
	 DATETIME
	 A date and time combination
	 The supported range is ‘1000-01-01 00:00:00’ to ‘9999-12-31 23:59:59’. MySQL
	displays DATETIME values in ‘YYYY-MM-DD HH:MM:SS’ format
	 TIMESTAMP
	 A timestamp
	 The range is ‘1970-01-01 00:00:00’ to sometime in the year 2037. MySQL displays
	TIMESTAMP values in YYYYMMDDHHMMSS, YYMMDDHHMMSS, YYYYMMDD or YYMMDD format,
	depending on whether M is 14 (or missing), 12, 8 or 6, but allows you to assign
	values to TIMESTAMP columns using either strings or numbers. A TIMESTAMP column
	is useful for recording the date and time of an INSERT or UPDATE operation
	because it is automatically set to the date and time of the most recent operation
	if you don’t give it a value yourself
	 TIME
	 A time
	 The range is ‘-838:59:59’ to ‘838:59:59’. MySQL displays TIME values in
	‘HH:MM:SS’ format, but allows you to assign values to TIME columns using either
	strings or numbers
	 YEAR
	 A year in 2- or 4- digit formats (default is 4-digit)
	 The allowable values are 1901 to 2155, and 0000 in the 4 year format and
	1970-2069 if you use the 2 digit format (70-69). MySQL displays YEAR values in
	YYYY format, but allows you to assign values to YEAR columns using either strings
	or numbers. (The YEAR type is new in MySQL 3.22.)
	 CHAR
	 A fixed-length string that is always right-padded with spaces to the specified
	length when stored
	 The range of Length is 1 to 255 characters. Trailing spaces are removed when the
	value is retrieved. CHAR values are sorted and compared in case-insensitive
	fashion according to the default character set unless the BINARY keyword is given
	 VARCHAR
	 A variable-length string. Note: Trailing spaces are removed when the value is
	stored (this differs from the ANSI SQL specification)
	 The range of Length is 1 to 255 characters. VARCHAR values are sorted and
	compared in case-insensitive fashion unless the BINARY keyword is given
	 TINYBLOB,
	 TINYTEXT
	 A BLOB or TEXT column with a maximum length of 255 (2^8 - 1) characters
	 BLOB,
	 TEXT
	 A BLOB or TEXT column with a maximum length of 65535 (2^16 - 1) characters
	 MEDIUMBLOB,
	 MEDIUMTEXT
	 A BLOB or TEXT column with a maximum length of 16777215 (2^24 - 1) characters
	 LONGBLOB,
	 LONGTEXT
	 A BLOB or TEXT column with a maximum length of 4294967295 (2^32 - 1) characters
	 ENUM
	 An enumeration
	 A string object that can have only one value, chosen from the list of values
	‘value1’, ‘value2’, ..., or NULL. An ENUM can have a maximum of 65535 distinct
	values.
	 SET
	 A set
	 A string object that can have zero or more values, each of which must be chosen
	from the list of values ‘value1’, ‘value2’, ... A SET can have a maximum of 64
	members
	 */

	/**
	 * setField
	 */
	public function setField($fieldProperties)
	{
		// if the value of $fieldProperties is not an array, it will assume that is JSON.
		if( !is_array($fieldProperties) ) 
		{
			$newField = json_decode($fieldProperties, true);
			$this->workingFields[] = $newField;
		}
		else 
		{
			$this->workingFields[] = $fieldProperties;
		}
	}

	/**
	 * setTable
	 */
	public function setTable($tableName)
	{
		$this->workingTable = (string)$tableName;
	}
	
	/**
	 * createDatabase
	 */
	public function createDatabase($databaseName)
	{
		try
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'CREATE DATABASE IF NOT EXISTS ' . $databaseName;
			$this->conn->exec($sqlStatement);
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on createDatabase method: ' . $err->getMessage() );
			return false;
		}
	}
	
	/**
	 * dropDatabase
	 */
	public function dropDatabase($databaseName)
	{
		try
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'DROP DATABASE IF NOT EXISTS ' . $databaseName;
			$this->conn->exec($sqlStatement);
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on dropDatabase method: ' . $err->getMessage() );
			return false;
		}
	}

	/**
	 * createField
	 */
	private function createField($fieldProperties)
	{
		try
		{
			// if the value of $fieldProperties is not an array, it will assume that is JSON.
	        if( !is_array($fieldProperties) ) 
	        {
	        	$field = (array)json_decode($fieldProperties, true);
			}
			else 
			{
				$field = (array)$fieldProperties;
			}
			
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'ALTER TABLE ' . $this->workingTable . ' ADD ';
			$sqlStatement .= (string)$this->columnsProperties($field);
			$this->conn->exec($sqlStatement);
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on createField method: ' . $err->getMessage() );
			return false;
		}
	}

	/**
	 * modifyField
	 */
	private function modifyField($fieldProperties)
	{
		try
		{
			// if the value of $fieldProperties is not an array, it will assume that is JSON.
	        if( !is_array($fieldProperties) ) 
	        {
	        	$field = (array)json_decode($fieldProperties, true);
			}
			else 
			{
				$field = (array)$fieldProperties;
			}
			
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'ALTER TABLE ' . $this->workingTable . ' MODIFY ';
			$sqlStatement .= (string)$this->columnsProperties($field);
			$this->conn->exec($sqlStatement);
			return true;
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on modifyField method: ' . $err->getMessage() );
			return false;
		}
	}
	
	/**
	 * renameField
	 */
	public function renameField($oldName, $newName)
	{
		try
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'ALTER TABLE ' . $this->workingTable . ' CHANGE COLUMN ' . $oldName . ' ' . $newName . ';';
			$this->conn->exec($sqlStatement);
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on renameField method: ' . $err->getMessage() );
			return false;
		}
	}
	
	/**
	 * renameTable
	 */
	public function renameTable($oldName, $newName)
	{
		try
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'ALTER TABLE ' . $oldName . ' RENAME TO ' . $newName . ';';
			$this->conn->exec($sqlStatement);
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on renameTable method: ' . $err->getMessage() );
			return false;
		}
	}
	
	/**
	 * createTable
	 */
	private function createTable($tableName)
	{
		// these are mandatory fields for all tables.
		try
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'CREATE TABLE IF NOT EXISTS ' . $tableName . '( ';
			$sqlStatement .= 'id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY';
			$sqlStatement .= ' ) AUTO_INCREMENT=1;';
			$this->conn->exec($sqlStatement);
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on createTable method: ' . $err->getMessage() );
			return false;
		}
	}

	/**
	 * dropTable
	 */
	public function dropTable()
	{
		try
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'DROP TABLE IF EXISTS ' . $this->workingTable . ';';
			$this->conn->exec($sqlStatement);
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on dropTable method: ' . $err->getMessage() );
			return false;
		}
	}

	/**
	 * dropField
	 */
	private function dropField($fieldName)
	{
		// check if the field contains data.
		try
		{
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sqlStatement = (string)'SELECT ' . $fieldName . ' FROM ' . $this->workingTable . ' LIMIT 1;';
			$recordSet = (object)$this->conn->query($sqlStatement);
			$fieldsRecords = (array)$recordSet->fetchAll(PDO::FETCH_ASSOC);
			if ( count($fieldsRecords) <= 0 )
			{
				// drop the field
				$sqlStatement = 'ALTER TABLE ' . $this->workingTable . ' DROP COLUMN ' . $fieldName . ';';
				$this->conn->exec($sqlStatement);
			}
		}
		catch(PDOException $err)
		{
			error_log('dbHelper - error on dropField method: ' . $err->getMessage() );
			return false;
		}
	}

	/**
	 * executeORM
	 * The main brain of the microORM.
	 */
	public function executeORM()
	{
		$recordSet = $this->conn->query('SHOW columns FROM ' . $this->workingTable);
		$error = (array)$this->conn->errorInfo();
		
		// Table does not exist and needs to be created
		if( $error[1] == 1146) 
		{
			$this->createTable($this->workingTable);
			$this->executeORM();
			return;
		}
		elseif($error[0] == 00000)
		{
			// these are default fields by the orm.
			$this->setField('{"NAME":"id", "TYPE":"BIGINT", "LENGTH":20, "NULL":false, "PRIMARY":true, "DEFAULT":"", "COMMENT":"", "AUTO_INCREMENT":true}');
			$fieldsRecords = $recordSet->fetchAll(PDO::FETCH_ASSOC);
			
			// check is the returned results are actually an array
			if ( is_array($fieldsRecords) )
			{
				// browse the workingFields array
				foreach ($this->workingFields as $compareField)
				{
					
					// if field is found compare it's properties.
					$key = $this->recursiveArraySearch($compareField['NAME'], $fieldsRecords);
					if ( is_numeric( $key ) )
					{
						
						$changes = false;
						if($compareField['TYPE'] != strtoupper( substr($fieldsRecords[$key]['Type'], 0, strlen(strstr($fieldsRecords[$key]['Type'], '(', true)) ) ) ) 
						{
							$changes = true;
						}
						if( $fieldsRecords[$key]['Null'] != ($compareField['NULL'] ? 'YES' : 'NO') ) 
						{
							$changes = true;
						}
						if( $fieldsRecords[$key]['Key'] != ($compareField['PRIMARY'] ? 'PRI' : '') ) 
						{
							$changes = true;
						}
						if( filter_var($fieldsRecords[$key]['Type'], FILTER_SANITIZE_NUMBER_INT) != $compareField['LENGTH']) 
						{
							$changes = true;
						}
						if( $fieldsRecords[$key]['Default'] != $compareField['DEFAULT']) 
						{
							$changes = true;
						}

						if ( $changes )
						{
						
							// Modify the column because there are not equal
							// and execute the orm again.
							$renderArray = array(
								'NAME' => $compareField['NAME'], 
								'TYPE' => $compareField['TYPE'], 
								'LENGTH' => $compareField['LENGTH'], 
								'NULL' => $compareField['NULL'],
								'PRIMARY' => $compareField['PRIMARY'],
								'DEFAULT' => $compareField['DEFAULT'],
								'COMMENT' => $compareField['COMMENT'],
								'AUTO_INCREMENT' => $compareField['AUTO_INCREMENT']);
							if($this->modifyField($renderArray)) $this->executeORM();
							return;
						}

					}
					else
					{
						// Create the field
						$renderArray = array(
							'NAME' => $compareField['NAME'], 
							'TYPE' => $compareField['TYPE'], 
							'LENGTH' => $compareField['LENGTH'], 
							'NULL' => $compareField['NULL'],
							'PRIMARY' => $compareField['PRIMARY'],
							'DEFAULT' => $compareField['DEFAULT'],
							'COMMENT' => $compareField['COMMENT'],
							'AUTO_INCREMENT' => $compareField['AUTO_INCREMENT']);
						$this->createField($renderArray);
					}
				}
				// routine to drop fields
				foreach ($fieldsRecords as $dropField)
				{
					if( !is_numeric($this->recursiveArraySearch($dropField['Field'], $this->workingFields)) ) $this->dropField($dropField['Field']);
				}
			}
		}
	}

	private function recursiveArraySearch($needle, $haystack)
	{
		foreach ($haystack as $key => $value)
		{
			$current_key = $key;
			if ($needle === $value OR (is_array($value) && $this->recursiveArraySearch($needle, $value) !== false))
			{
				return $current_key;
			}
		}
		return false;
	}
	
	/**
	 *  columnsProperties
	 * Renders the columns properties by the array
	 */
	private function columnsProperties($field)
	{
        $sqlStatement = '';
		switch($field['TYPE'])
		{
			case 'BIT'; case 'BINARY'; case 'VARBINARY':
				$sqlStatement .= (string)$field['NAME'] . ' ' . 
				$field['TYPE'] . '(' . $field['LENGTH'] . ')' . 
				'COMMENT "' . $field['COMMENT'] . '";';
			break;
				
			case 'INT'; case 'TINYINT'; case 'MEDIUMINT'; case 'INTEGER'; case 'BIGINT':
				$sqlStatement .= (string)$field['NAME'] . ' ' . 
				$field['TYPE'] . '(' . $field['LENGTH'] . ')' . 
				($field['NULL'] ? ' NULL ' : ' NOT NULL ') .
				($field['PRIMARY'] ? ' PRIMARY KEY ' : '') .
				'DEFAULT' . ($field['DEFAULT'] ? ' "'.$field['DEFAULT'].'" ' : ' NULL ') .
				'COMMENT "' . $field['COMMENT'] . '";';
			break;
			
			case 'REAL'; case 'DOUBLE'; case 'FLOAT'; case 'DECIMAL'; case 'NUMERIC':
				$sqlStatement .= (string)$field['NAME'] . ' ' . 
				$field['TYPE'] . '(' . $field['LENGTH'] . ', ' . $field['DECIMALS'] .')' . 
				($field['NULL'] ? ' NULL ' : ' NOT NULL ') .
				($field['PRIMARY'] ? ' PRIMARY KEY ' : '') .
				'DEFAULT' . ($field['DEFAULT'] ? ' "'.$field['DEFAULT'].'" ' : ' NULL ') .
				'COMMENT "' . $field['COMMENT'] . '";';
			break;
			
			case 'DATE'; case 'TIME'; case 'TIMESTAMP'; case 'DATETIME'; case 'YEAR';
				$sqlStatement .= (string)$field['NAME'] . ' ' . 
				$field['TYPE'] .  
				($field['NULL'] ? ' NULL ' : ' NOT NULL ') .  
				($field['PRIMARY'] ? ' PRIMARY KEY ' : '') .
				'DEFAULT' . ($field['DEFAULT'] ? ' "'.$field['DEFAULT'].'" ' : ' NULL ') . 
				'COMMENT "' . $field['COMMENT'] . '";';
			break;
			
			case 'CHAR'; case 'VARCHAR'; case 'TINYTEXT '; case 'TEXT '; case 'MEDIUMTEXT'; case 'LONGTEXT';
				$sqlStatement .= (string)$field['NAME'] . ' ' . 
				$field['TYPE'] . '(' . $field['LENGTH'] . ')' . 
				'CHARACTER SET ' . $field['CHARACTER_SET'] . ' ' .
				'COLLATE ' . $field['COLLATE'] . ' ' .
				($field['NULL'] ? ' NULL ' : ' NOT NULL ') .  
				($field['PRIMARY'] ? ' PRIMARY KEY ' : '') .
				'DEFAULT' . ($field['DEFAULT'] ? ' "'.$field['DEFAULT'].'" ' : ' NULL ') . 
				'COMMENT "' . $field['COMMENT'] . '";';
			break;
			
			case 'TINYBLOB'; case 'BLOB'; case 'MEDIUMBLOB'; case 'LONGBLOB':
				$sqlStatement .= (string)$field['NAME'] . ' ' . 
				$field['TYPE'] . ' ' . 
				'COMMENT "' . $field['COMMENT'] . '";';
			break;
			
			default:
				$sqlStatement .= (string)$field['NAME'] . ' ' . 
				$field['TYPE'] . '(' . $field['LENGTH'] . ')' . 
				($field['NULL'] ? ' NULL ' : ' NOT NULL ') . 
				($field['PRIMARY'] ? ' PRIMARY KEY ' : '') .
				'DEFAULT' . ($field['DEFAULT'] ? ' "'.$field['DEFAULT'].'" ' : ' NULL ') .
				'COMMENT "' . $field['COMMENT'] . '";';
			break;
		}
		return $sqlStatement;
	}

}