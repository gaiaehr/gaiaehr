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
	 * This would be a Sencha Model parsed by getSenchaModel method
	 */
	public $Model;
	public $Table;


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
		if (isset($_SESSION['site']['db']))
		{
			$host = (string)$_SESSION['site']['db']['host'];
			$port = (int)$_SESSION['site']['db']['port'];
			$dbName = (string)$_SESSION['site']['db']['database'];
			$dbUser = (string)$_SESSION['site']['db']['username'];
			$dbPass = (string)$_SESSION['site']['db']['password'];
			try
			{
				// Connect using regular PDO GaiaEHR Database Abstraction layer.
				$this->conn = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbName, $dbUser, $dbPass, array(
					PDO::MYSQL_ATTR_LOCAL_INFILE => 1,
					PDO::ATTR_PERSISTENT => true
				));
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
		if ($setLastInsertId) $this->lastInsertId = $this->conn->lastInsertId();
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
	 * Begin of the GaiaEHR microORM
	 * This would be a complete set of methods to manage the database
	 * creation and data exchange.
	 * 
	 * In the future this will replace the entire old class methods. 
	 */
	
	/**
	 * SechaModel method: 
	 * This method will create the table and fields if does not exist in the database
	 * also this is the brain of the micro ORM.
	 */
	public function SenchaModel($fileModel)
	{
		
		// get the the model of the table from the sencha .js file
		$this->__getSenchaModel($fileModel);
		
		try
		{
		
			// verify the existence of the table if it does not exist create it
			$recordSet = $this->conn->query("SHOW TABLES LIKE '".$this->Table."';");
			if( $recordSet->fetch(PDO::FETCH_ASSOC) ) $this->__createTable($this->Table);
			
			// Remove from the model those fields that are not meant to be stored
			// on the database.
			$workingModel = (array)$this->Model;
			foreach($workingModel as $key => $SenchaModel) if($SenchaModel['store'] == 'false') unset($workingModel[$key]);
			
			// get the table column information and
			$recordSet = $this->conn->query("SHOW COLUMNS IN " . $this->Table . ";");
			$tableColumns = $recordSet->fetchAll(PDO::FETCH_ASSOC);
			
			// check if the table has columns, if not create them.
			// we start with 1 because the microORM always create the id.
			if( count($tableColumns) <= 1 ) 
			{
				$this->__createAllColumns($workingModel);
				return true;
			}
			// Also check if there is difference between the model and the 
			// database table in terms of number of fields.
			elseif(count($workingModel) != count($tableColumns))
			{
				// remove columns from the table
				foreach($tableColumns as $column) if( !is_numeric($this->__recursiveArraySearch($column['Field'], $workingModel)) ) $this->__dropColumn($column['Field']);
				// add columns to the table
				foreach($workingModel as $column) if( !is_numeric($this->__recursiveArraySearch($column['name'], $tableColumns)) ) $this->__createColumn($column);
			}
			// if everything else passes check for differences in the columns.
			else
			{
				// Verify changes in the table 
				// modify the table columns if is not equal to the Sencha Model
				foreach($tableColumns as $column)
				{
					$change = 'false';
					foreach($workingModel as $SenchaModel)
					{
						// if the field is found, start the comparison
						if($SenchaModel['name'] == $column['Field'])
						{
							// check for changes on the field type is a obligatory
							if(strripos($column['Type'], $SenchaModel['dataType']) === false) $change = 'true'; // Type 
							
							// check if there changes on the allowNull property, 
							// but first check if it's used on the sencha model
							if(isset($SenchaModel['allowNull'])) if( $column['Null'] == ($SenchaModel['allowNull'] ? 'YES' : 'NO') ) $change = 'true'; // NULL
							
							// check the length of the field, 
							// but first check if it's used on the sencha model.
							if(isset($SenchaModel['len'])) if($SenchaModel['len'] != filter_var($column['Type'], FILTER_SANITIZE_NUMBER_INT)) $change = 'true'; // Length
							
							// check if the default value is changed on the model,
							// but first check if it's used on the sencha model
							if(isset($SenchaModel['defaultValue'])) if($column['Default'] != $SenchaModel['defaultValue']) $change = 'true'; // Default value
							
							// check if the primary key is changed on the model,
							// but first check if the primary key is used on the sencha model.
							if(isset($SenchaModel['primaryKey'])) if($column['Key'] != ($SenchaModel['primaryKey'] ? 'PRI' : '') ) $change = 'true'; // Primary key
							
							// check if the auto increment is changed on the model,
							// but first check if the auto incroment is used on the sencha model.
							if(isset($SenchaModel['autoIncrement'])) if($column['Extra'] != ($SenchaModel['autoIncrement'] ? 'auto_increment' : '') ) $change = 'true'; // auto increment
							
							// Modify the column on the database							
							if($change == 'true') $this->__modifyColumn($SenchaModel);
							
						}
					}

				}
			}
			
		}
		catch(PDOException $e)
		{
			error_log('dbHelper SenchaPHP microORM: ' . $e->getMessage() );
			return $e;
		}
	}
	
	/**
	 * __getSenchaModel:
	 * This method is used by SechaModel method to get all the table and column
	 * information inside the Sencha Model .js file 
	 */
	private function __getSenchaModel($fileModel)
	{
		// Getting Sencha model as a namespace
		$fileModel = str_replace('App', 'app', $fileModel);
		$fileModel = str_replace('.', '/', $fileModel);
		$senchaModel = (string)file_get_contents($_SESSION['root'] . '/' . $fileModel . '.js');

		// strip comments from the code
		$senchaModel = preg_replace('~#[^\r\n]*~', '', $senchaModel);
		$senchaModel = preg_replace('~//[^\r\n]*~', '', $senchaModel);
		$senchaModel = preg_replace('~/\*.*?\*/~s', '', $senchaModel);
		
		// get the table from the model
		preg_match("/table(.*?),/si", $senchaModel, $matches, PREG_OFFSET_CAPTURE, 3);
		preg_match("/[a-zA-Z_]+/", $matches[1][0], $matches, PREG_OFFSET_CAPTURE, 3);
		$this->__setTable( $matches[0][0] );
		
		// Extracting the necessary end-points for the fields
		unset($matches);
		preg_match("/fields(.*?)]/si", $senchaModel, $matches, PREG_OFFSET_CAPTURE, 3);

		// Removing all the unnecessary characters.
		$subject = str_replace(' ', '', $matches[1][0]);
		$subject = str_replace(chr(13), '', $subject);
		$subject = str_replace(chr(10), '', $subject);
		$subject = str_replace(chr(9), '', $subject);
		$subject = str_replace('[', '', $subject);
		$subject = str_replace("'", '', $subject);
		$subject = substr($subject, 1);
		
		// match any word on the string
		$subject = preg_replace('/[a-zA-Z0-9_]+/', '"$0"', $subject);
		
		//compose a valid json string.
		$subject = '{"fields": [' . $subject . ']}';
				
		// Return the decoded model of Sencha
		$model = (array)json_decode($subject, true);
		$this->Model = $model['fields'];
	}
	
	/**
	 * __createTable:
	 * Method to create a table if does not exist
	 */
	 private function __createTable()
	 {
	 	try
	 	{
			$this->conn->query('CREATE TABLE IF NOT EXISTS ' . $this->Table . ' (id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY);');
			return true;
		}
		catch(PDOException $e)
		{
			error_log('dbHelper SenchaPHP microORM: ' . $e->getMessage() );
			return $e;
		}
	 }
	 
	 /**
	  * __setTable:
	  * This will populate the Table class variable
	  */
	 private function __setTable($table)
	 {
	 	$this->Table = $table;
	 }
	
	/**
	 * __createColumn:
	 * This method will create the column inside the table of the database
	 * method used by SechaModel method
	 */
	private function __createAllColumns($paramaters = array())
	{
		foreach($paramaters as $column)
		{
			try
			{
				$this->__createColumn($column);
			}
			catch(PDOException $e)
			{
				error_log('dbHelper SenchaPHP microORM: ' . $e->getMessage() );
				return $e;
			}
		}
	}
	
	/**
	 * __createColumn:
	 * Method that will create the column into the table
	 */
	private function __createColumn($column = array())
	{
		try
		{
			$this->conn->query('ALTER TABLE '.$this->Table.' ADD '.$column['name'].' '.$this->__renderColumnSyntax($column) . ';');
		}
		catch(PDOException $e)
		{
			error_log('dbHelper SenchaPHP microORM: ' . $e->getMessage() );
			return $e;
		}		
	}
	
	/**
	 * __modifyColumn:
	 * Method to modify the column properties
	 */
	private function __modifyColumn($SingleParamaters = array())
	{
		try
		{
			$this->conn->query('ALTER TABLE '.$this->Table.' MODIFY '.$SingleParamaters['name'].' '.$this->__renderColumnSyntax($SingleParamaters) . ';');
		}
		catch(PDOException $e)
		{
			error_log('dbHelper SenchaPHP microORM: ' . $e->getMessage() );
			return $e;
		}
	}
	
	/**
	 * __createDatabase
	 * Method that will create a database
	 */
	private function __createDatabase($databaseName)
	{
		try
		{
			$this->conn->query('CREATE DATABASE IF NOT EXISTS '.$databaseName.';');
		}
		catch(PDOException $e)
		{
			error_log('dbHelper SenchaPHP microORM: ' . $e->getMessage() );
			return $e;
		}
	}
	
	/**
	 * __dropColumn:
	 * Method to drop column in a table
	 */
	private function __dropColumn($column)
	{
		try
		{
			$this->conn->query("ALTER TABLE ".$this->Table." DROP COLUMN `".$column."`;");
		}
		catch(PDOException $e)
		{
			error_log('dbHelper SenchaPHP microORM: ' . $e->getMessage() );
			return $e;
		}
	}
	
	/**
	 * __renderColumnSyntax:
	 * Method that will render the correct syntax for the addition or modification
	 * of a column.
	 */
	private function __renderColumnSyntax($column = array())
	{
		if(isset($column['dataType'])) 
		{
			$columnType = strtoupper($column['dataType']);
		}
		else
		{
			return false;
		}
		switch ($columnType)
		{
			case 'BIT'; case 'TINYINT'; case 'SMALLINT'; case 'MEDIUMINT'; case 'INT'; case 'INTEGER'; case 'BIGINT':
				return $columnType.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].') ' : '') : '').
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' ).
				( isset($column['autoIncrement']) ? ($column['autoIncrement'] ? 'AUTO_INCREMENT ' : '') : '' ).
				( isset($column['primaryKey']) ? ($column['primaryKey'] ? 'PRIMARY KEY ' : '') : '' );
				break;
			case 'REAL'; case 'DOUBLE'; case 'FLOAT'; case 'DECIMAL'; case 'NUMERIC':
				return $columnType.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].')' : '(10,2)') : '(10,2)').
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' ).
				( isset($column['autoIncrement']) ? ($column['autoIncrement'] ? 'AUTO_INCREMENT ' : '') : '' ).
				( isset($column['primaryKey']) ? ($column['primaryKey'] ? 'PRIMARY KEY ' : '') : '' );
				break;
			case 'DATE'; case 'TIME'; case 'TIMESTAMP'; case 'DATETIME'; case 'YEAR':
				return $columnType.' '.
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' );
				break;
			case 'CHAR'; case 'VARCHAR':
				return $columnType.' '.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].') ' : '(255)') : '(255)').
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' );
				break;
			case 'BINARY'; case 'VARBINARY':
				return $columnType.' '.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].') ' : '') : '').
				( isset($column['allowNull']) ? ($column['allowNull'] ? '' : 'NOT NULL ') : '' );
				break;
			case 'TINYBLOB'; case 'BLOB'; case 'MEDIUMBLOB'; case 'LONGBLOB'; case 'TINYTEXT'; case 'TEXT'; case 'MEDIUMTEXT'; case 'LONGTEXT':
				return $columnType.' '.
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' );
				break;
		}
		return true;
	}
	
	/**
	 * __recursive_array_search:
	 * An recursive array search method
	 */
	private function __recursiveArraySearch($needle,$haystack) 
	{
	    foreach($haystack as $key=>$value) 
	    {
	        $current_key=$key;
	        if($needle===$value OR (is_array($value) && $this->__recursiveArraySearch($needle,$value) !== false)) return $current_key;
	    }
	    return false;
	}
	
}
