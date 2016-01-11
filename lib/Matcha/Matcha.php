<?php
/**
 * Matcha::connect
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

define('MATCHA_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
include_once(MATCHA_ROOT . 'MatchaAudit.php');
include_once(MATCHA_ROOT . 'MatchaCUP.php');
include_once(MATCHA_ROOT . 'MatchaErrorHandler.php');
include_once(MATCHA_ROOT . 'MatchaModel.php');
include_once(MATCHA_ROOT . 'MatchaUtils.php');
include_once(MATCHA_ROOT . 'MatchaMemory.php');
include_once(MATCHA_ROOT . 'MatchaSession.php');
include_once(MATCHA_ROOT . 'MatchaRouter.php');

// Include the Matcha Threads if the PHP Thread class exists
if(class_exists('Thread'))
	include_once(MATCHA_ROOT . 'MatchaThreads.php');

class Matcha {

	/**
	 * This would be a Sencha Model parsed by getSenchaModel method
	 */
	public static $Relation;

	public static $currentRecord;
	/**
	 * @var int
	 */
	public static $__id;
	/**
	 * @var int
	 */
	public static $__total;
	/**
	 * @var bool
	 */
	public static $__freeze = false;
	/**
	 * @var PDO
	 */
	public static $__conn;
	/**
	 * @var string
	 */
	public static $__app;
	/**
	 * @var string
	 */
	public static $__secretKey = 'CryptSecretKey';
	/**
	 * @var int
	 */
	public static $__installationNumber = 1;

	/**
	 * function connect($databaseParameters = array()):
	 * Method that make the connection to the database
	 * @param array $databaseParameters
	 * @return bool|PDO
	 */
	static public function connect($databaseParameters = []){
		try{

			if(self::$__conn === null){

				// check for properties first.
				if(!isset($databaseParameters['host']) && !isset($databaseParameters['name']) && !isset($databaseParameters['user']) && !isset($databaseParameters['pass']) && !isset($databaseParameters['app']))
					throw new Exception('These parameters are obligatory: host="database ip or hostname", name="database name", user="database username", pass="database password", app="path of your sencha application"');

				// Connect using regular PDO Matcha::connect Abstraction layer.
				// but make only a connection, not to the database.
				// and then the database
				self::$__app = $databaseParameters['app'];
				$host = (string)(isset($databaseParameters['host']) ? $databaseParameters['host'] : 'localhost');
				$port = (int)(isset($databaseParameters['port']) ? $databaseParameters['port'] : '3306');
				$dbName = (string)$databaseParameters['name'];
				$dbUser = (string)$databaseParameters['user'];
				$dbPass = (string)$databaseParameters['pass'];

				self::$__conn = new PDO('mysql:host=' . $host . ';port=' . $port . ';', $dbUser, $dbPass, array(
					//PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
					PDO::MYSQL_ATTR_LOCAL_INFILE => 1,
					PDO::ATTR_PERSISTENT => false,
				));

				self::$__conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$__conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				self::$__conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

				// check if the database exist.
				self::__createDatabase($dbName);
				self::$__conn->exec('USE ' . $dbName . ';');

				// set the encryption secret key if provided
				if(isset($databaseParameters['key']))
					self::$__secretKey = $databaseParameters['key'];
			}

			return self::$__conn;
		} catch(Exception $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function getLastId():
	 * Get the last insert ID of an insert
	 * this is automatically updated by the store method
	 */
	static public function getLastId(){
		return (int)self::$__id;
	}

	/**
	 * getTotal:
	 * Get the total records in a select statement
	 * this is automatically updated by the load method
	 */
	static public function getTotal(){
		return (int)self::$__total;
	}

	/**
	 * freeze($freeze = false):
	 * freeze the database and tables alteration by the Matcha microORM
	 * @param bool $freeze
	 */
	static public function freeze($freeze = false){
		self::$__freeze = (bool)$freeze;
	}

	/**
	 * function __createTable():
	 * Method to create a table if does not exist with a BIGINT as id
	 * also if the sencha model has an array on the table go ahead and
	 * process the table options.
	 * @param null $forcedTable
	 * @return bool
	 */
	static protected function __createTable($forcedTable = NULL){
		try{
			if($forcedTable){
				$table = (string)$forcedTable;
			} else{
				$table = (string)(is_array(MatchaModel::$__senchaModel['table']) ? MatchaModel::$__senchaModel['table']['name'] : MatchaModel::$__senchaModel['table']);
			}

			$idProperties = MatchaModel::$tableIdProperties;

			if(
				(isset(MatchaModel::$__senchaModel['table']['uuid']) && MatchaModel::$__senchaModel['table']['uuid']) ||
				(isset($idProperties['type']) && $idProperties['type'] == 'string')
			){
				$tableIdProperties = 'VARCHAR(60) NOT NULL PRIMARY KEY';
			}else{
				$tableIdProperties = 'BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY';
			}
			$tableId = MatchaModel::$tableId;
			self::$__conn->exec("CREATE TABLE IF NOT EXISTS  `{$table}` ({$tableId} {$tableIdProperties}) " . self::__renderTableOptions() . ';');

			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __renderTableOptions():
	 * Render and return a well formed Table Options for creating table.
	 * some default properties of the table are:
	 * engine: InnoDB
	 * charset: utf8
	 * collate: utf8_bin
	 */
	static protected function __renderTableOptions(){
		$tableOptions = (string)'';
		if(!is_array(MatchaModel::$__senchaModel['table']))
			return false;

		// set the engine of the table, if it is not set go and set it for InnoDB
		if(isset(MatchaModel::$__senchaModel['table']['ENGINE'])):$tableOptions .= 'ENGINE = ' . MatchaModel::$__senchaModel['table']['engine'] . ' ';
		else:$tableOptions .= 'ENGINE = InnoDB ';
		endif;

		// set the auto_increment, if is not set the table property to 1.
		if(isset(MatchaModel::$__senchaModel['table']['autoIncrement'])): $tableOptions .= 'AUTO_INCREMENT = ' . MatchaModel::$__senchaModel['table']['autoIncrement'] . ' ';
		else: $tableOptions .= 'AUTO_INCREMENT = 1 ';
		endif;

		// set character set of the table, if is not set the default
		// would be UTF-8
		if(isset(MatchaModel::$__senchaModel['table']['charset'])): $tableOptions .= 'CHARACTER SET = ' . MatchaModel::$__senchaModel['table']['charset'] . ' ';
		else: $tableOptions .= 'CHARACTER SET = utf8 ';
		endif;

		// set the collate of the table, if is not set the default
		// would be utf8_bin
		if(isset(MatchaModel::$__senchaModel['table']['collate'])): $tableOptions .= 'COLLATE = ' . MatchaModel::$__senchaModel['table']['collate'] . ' ';
		else: $tableOptions .= 'COLLATE = utf8_general_ci ';
		endif;

		// set the comment for a table, if it is not set don't set it.
		if(isset(MatchaModel::$__senchaModel['table']['comment']))
			$tableOptions .= "COMMENT = '" . MatchaModel::$__senchaModel['table']['comment'] . "' ";

		return $tableOptions;
	}

	/**
	 * function __createAllColumns($parameters = array()):
	 * This method will create all the columns inside the table of the database
	 * method used by SenchaModel method
	 */
	static protected function __createAllColumns($parameters = array()){
		foreach($parameters as $column)
			if(!self::__createColumn($column))
				return false;
		return true;
	}

	/**
	 * function __createColumn($column = array()):
	 * Method that will create a single column into the table
	 */
	static protected function __createColumn($column = array(), $table = NULL, $index = false){
		try{
			if(!$table)
				$table = (string)(is_array(MatchaModel::$__senchaModel['table']) ? MatchaModel::$__senchaModel['table']['name'] : MatchaModel::$__senchaModel['table']);

			$colName = isset($column['mapping']) ? $column['mapping'] : $column['name'];
			if(self::__rendercolumnsyntax($column) == true)
				self::$__conn->query('ALTER TABLE `' . $table . '` ADD `' . $colName . '` ' . self::__rendercolumnsyntax($column) . ';');
			if($index)
				self::__createIndex($table, $colName);
			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __modifyColumn($column = array(), $table = NULL):
	 * Method to modify a single column properties
	 */
	static protected function __modifyColumn($column = array(), $table = NULL, $index = false){
		try{
			if($table == null)
				$table = (string)(is_array(MatchaModel::$__senchaModel['table']) ? MatchaModel::$__senchaModel ['table']['name'] : MatchaModel::$__senchaModel['table']);

			$colName = isset($column['mapping']) ? $column['mapping'] : $column['name'];
			if(self::__rendercolumnsyntax($column) == true)
				self::$__conn->query('ALTER TABLE  `' . $table . '` MODIFY `' . $colName . '` ' . self::__renderColumnSyntax($column) . ';');
			if($index)
				self::__createIndex($table, $colName);
			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function createDatabase($databaseName):
	 * Method that will create a database, but will create it if
	 * it does not exist.
	 */
	static protected function __createDatabase($databaseName){
		try{
			self::$__conn->query('CREATE DATABASE IF NOT EXISTS ' . $databaseName . ';');
			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __dropColumn($column, $table = NULL):
	 * Method to drop column in a table
	 */
	static protected function __dropColumn($column, $table = NULL){
		try{
			if(!$table)
				$table = (string)(is_array(MatchaModel::$__senchaModel['table']) ? MatchaModel::$__senchaModel['table']['name'] : MatchaModel::$__senchaModel['table']);
			self::$__conn->query('ALTER TABLE `' . $table . '` DROP COLUMN `' . $column . '`;');
			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __createIndex($table, $column):
	 * Method to create an new index to table if index does not exist
	 * @param $table
	 * @param $columns
	 * @return bool
	 */
	static public function __createIndex($table, $columns){
		try{
			if(is_string($columns)) $columns = array($columns);

			$keyName = 'IK_'. implode('_', $columns);
			$columns = implode(',', $columns);

			$sth = self::$__conn->prepare('SHOW INDEX FROM ' . $table . ' WHERE `Key_name` = \'' . $keyName . '\';');
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);

			if($result === false)	self::$__conn->query('ALTER TABLE `' . $table . '` ADD INDEX ' . $keyName . '(' . $columns . ');');
			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __renameColumn($oldColumn, $newColumn, $table = NULL):
	 * Rename a column with new passed column name
	 * @param $oldColumn
	 * @param $newColumn
	 * @param null $table
	 * @internal param array $column
	 * @return bool
	 */
	static protected function __renameColumn($oldColumn, $newColumn, $table = NULL){
		try{
			if(!$table)
				$table = (string)(is_array(MatchaModel::$__senchaModel['table']) ? MatchaModel::$__senchaModel['table']['name'] : MatchaModel::$__senchaModel['table']);
			self::$__conn->query("ALTER TABLE " . $table . " CHANGE COLUMN " . $oldColumn . " " . $newColumn . ";");
			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __getTableSize($databaseName = NULL, $databaseTable = NULL, $measure = 'MEGABYTES'):
	 * Method to get the size of a table, you can choose the measure 'BYTES', 'MEGABYTES', 'GIGABYTES'.
	 * @param null $databaseName
	 * @param null $databaseTable
	 * @param string $measure = 'BYTES', 'MEGABYTES', 'GIGABYTES'
	 * @return bool
	 * @throws Exception
	 */
	static protected function __getTableSize($databaseName = NULL, $databaseTable = NULL, $measure = 'MEGABYTES'){
		try{
			switch($measure){
				case 'BYTES':
					$Calculation = '';
					break;
				case 'MEGABYTES':
					$Calculation = '/power(1024,1)';
					break;
				case 'GIGABYTES':
					$Calculation = '/power(1024,2)';
					break;
			}
			if($databaseName == NULL || $databaseTable == NULL)
				throw new Exception('No database or table name provided."');
			$size = self::$__conn->query("SELECT (data_length+index_length) $Calculation tablesize FROM information_schema.tables WHERE table_schema='$databaseName' and table_name='$databaseTable';");
			return $size['tablesize'];
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __renderColumnSyntax($column = array()):
	 * Method that will render the correct syntax for the addition or modification
	 * of a column.
	 */
	static protected function __renderColumnSyntax($column = array()){
		try{
			// parse some properties on Sencha model.
			// and do the defaults if properties are not set.
			if(isset($column['dataType'])){
				$columnType = (string)strtoupper($column['dataType']);
			} elseif($column['type'] == 'string'){
				$columnType = (string)'VARCHAR';
			} elseif($column['type'] == 'int'){
				$columnType = (string)'INT';
				$column['len'] = (isset($column['len']) ? $column['len'] : 11);
			} elseif($column['type'] == 'bool' || $column['type'] == 'boolean'){
				$columnType = (string)'TINYINT';
				$column['len'] = (isset($column['len']) ? $column['len'] : 1);
			} elseif($column['type'] == 'date'){
				$columnType = (string)'DATETIME';
			} elseif($column['type'] == 'float'){
				$columnType = (string)'FLOAT';
			} elseif($column['type'] == 'array'){
				$columnType = (string)'MEDIUMTEXT';
			} else{
				return false;
			}

			// render the rest of the sql statement
			switch($columnType){
				case 'BIT';
				case 'TINYINT';
				case 'SMALLINT';
				case 'MEDIUMINT';
				case 'INT';
				case 'INTEGER';
				case 'BIGINT':
					return $columnType . (isset($column['len']) ? ($column['len'] ? '(' . $column['len'] . ') ' : '') : '') . (isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string((string)$column['defaultValue']) ? "DEFAULT '" . $column['defaultValue'] . "' " : '') : '') . (isset($column['comment']) ? ($column['comment'] ? "COMMENT '" . $column['comment'] . "' " : '') : '') . (isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '') . (isset($column['autoIncrement']) ? ($column['autoIncrement'] ? 'AUTO_INCREMENT ' : '') : '') . (isset($column['primaryKey']) ? ($column['primaryKey'] ? 'PRIMARY KEY ' : '') : '');
					break;
				case 'REAL';
				case 'DOUBLE';
				case 'FLOAT';
				case 'DECIMAL';
				case 'NUMERIC':
					return $columnType . (isset($column['len']) ? ($column['len'] ? '(' . $column['len'] . ')' : '(10,2)') : '(10,2)') . (isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '" . $column['defaultValue'] . "' " : '') : '') . (isset($column['comment']) ? ($column['comment'] ? "COMMENT '" . $column['comment'] . "' " : '') : '') . (isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '') . (isset($column['autoIncrement']) ? ($column['autoIncrement'] ? 'AUTO_INCREMENT ' : '') : '') . (isset($column['primaryKey']) ? ($column['primaryKey'] ? 'PRIMARY KEY ' : '') : '');
					break;
				case 'DATE';
				case 'TIME';
				case 'TIMESTAMP';
				case 'DATETIME';
				case 'YEAR':
					return $columnType . ' ' . (isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '" . $column['defaultValue'] . "' " : '') : '') . (isset($column['comment']) ? ($column['comment'] ? "COMMENT '" . $column['comment'] . "' " : '') : '') . (isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '');
					break;
				case 'CHAR';
				case 'VARCHAR':
					return $columnType . ' ' . (isset($column['len']) ? ($column['len'] ? '(' . $column['len'] . ') ' : '(255)') : '(255)') . (isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '" . $column['defaultValue'] . "' " : '') : '') . (isset($column['comment']) ? ($column['comment'] ? "COMMENT '" . $column['comment'] . "' " : '') : '') . (isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '');
					break;
				case 'BINARY';
				case 'VARBINARY':
					return $columnType . ' ' . (isset($column['len']) ? ($column['len'] ? '(' . $column['len'] . ') ' : '(1000)') : '(1000)') . (isset($column['allowNull']) ? ($column['allowNull'] ? '' : 'NOT NULL ') : '') . (isset($column['comment']) ? ($column['comment'] ? "COMMENT '" . $column['comment'] . "'" : '') : '');
					break;
				case 'TINYBLOB';
				case 'BLOB';
				case 'MEDIUMBLOB';
				case 'LONGBLOB';
				case 'TINYTEXT';
				case 'TEXT';
				case 'MEDIUMTEXT';
				case 'LONGTEXT':
					return $columnType . ' ' . (isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '') . (isset($column['comment']) ? ($column['comment'] ? "COMMENT '" . $column['comment'] . "'" : '') : '');
					break;
				default:
					throw new Exception('No data type is defined.');
					break;
			}
		} catch(Exception $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * @param int $number
	 */
	public static function setInstallationNumber($number){
		self::$__installationNumber = $number;
	}

	/**
	 * @return int
	 */
	public static function getInstallationNumber(){
		return self::$__installationNumber;
	}

	/**
	 * @param string $key
	 */
	public static function setSecretKey($key){
		self::$__secretKey = $key;
	}

	/**
	 * @param string $dir
	 */
	public static function setAppDir($dir){
		self::$__app = $dir;
	}

	/**
	 * @param string $freeze
	 */
	public static function setFreeze($freeze){
		self::$__freeze = $freeze;
	}

	/**
	 *
	 */
	public static function getFreeze(){
		return self::$__freeze;
	}

	/**
	 * @return PDO
	 */
	public static function getConn(){
		return self::$__conn;
	}

	/**
	 * @param bool $pause True to pause the audit log
	 * @return bool
	 */
	public static function pauseLog($pause){
		return MatchaAudit::$__audit = !$pause;
	}


}
