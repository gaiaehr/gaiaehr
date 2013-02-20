<?php
 /**
  * Matcha::connect (Main Class)
  * Matcha.php
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

include_once('MatchaAudit.php');
include_once('MatchaCUP.php');
include_once('MatchaErrorHandler.php');
include_once('MatchaInject.php');

class Matcha
{
	 
	/**
	 * This would be a Sencha Model parsed by getSenchaModel method
	 */
	public static $Relation;
	public static $currentRecord;
	public static $__id;
	public static $__total;
	public static $__freeze = false;
	public static $__senchaModel;
	public static $__conn;
	public static $__app;
	public static $__audit;
	
	/**
	 * function connect($databaseParameters = array()):
	 * Method that make the connection to the database
	 */
	static public function connect($databaseParameters = array())
	{
		try
		{		
			// check for properties first.
			if(!isset($databaseParameters['host']) && 
				!isset($databaseParameters['name']) &&
				!isset($databaseParameters['user']) && 
				!isset($databaseParameters['pass']) &&
				!isset($databaseParameters['app'])) 
				throw new Exception('These parameters are obligatory: host="database ip or hostname", name="database name", user="database username", pass="database password", app="path of your sencha application"');
				
			// Connect using regular PDO Matcha::connect Abstraction layer.
			// but make only a connection, not to the database.
			// and then the database
			self::$__app = $databaseParameters['app'];
			$host = (string)$databaseParameters['host'];
			$port = (int)(isset($databaseParameters['port']) ? $databaseParameters['port'] : '3306');
			$dbName = (string)$databaseParameters['name'];
			$dbUser = (string)$databaseParameters['user'];
			$dbPass = (string)$databaseParameters['pass'];
			self::$__conn = new PDO('mysql:host='.$host.';port='.$port.';', $dbUser, $dbPass, array(
				PDO::MYSQL_ATTR_LOCAL_INFILE => 1,
				PDO::ATTR_PERSISTENT => true
			));
			self::$__conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$__conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			// check if the database exist.
			self::__createDatabase($dbName);
			self::$__conn->query('USE '.$dbName.';');
			return self::$__conn;
		}
		catch(Exception $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
	 
	 /**
	  * function connect($databaseObject, $rootPath, $senchaModel)
	  * The first thing to do, to begin using Matcha
	  * This will load the Sencha Model to Matcha and do it's magic.
	  */
	 static public function setSenchaModel($senchaModel = array())
	 {
	 	try
	 	{
	 		if(self::__SenchaModel($senchaModel))
			{
				$MatchaCUP = new MatchaCUP;
				$MatchaCUP->setModel(self::$__senchaModel);
				return $MatchaCUP;
			}
		}
		catch(Exception $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	 }

	/**
	 * function getLastId():
	 * Get the last insert ID of an insert
	 * this is automatically updated by the store method
	 */
	static public function getLastId()
	{
		return (int)self::$__id;
	}
	
	/**
	 * getTotal:
	 * Get the total records in a select statement
	 * this is automatically updated by the load method
	 */
	static public function getTotal()
	{
		return (int)self::$__total;
	}
	
	/**
	 * freeze($onoff = false):
	 * freeze the database and tables alteration by the Matcha microORM
	 */
	static public function freeze($onoff = false)
	{
		self::$__freeze = (bool)$onoff;
	}
	
	/**
	 * function audit($onoff = true):
	 * Method to enable the audit log process.
	 * This will write a log every time it INSERT, UPDATE, DELETE a record.
	 */
	static public function audit($onoff = true)
	{
		self::$__audit = (bool)$onoff;
	}
	
	/**
	 * function SenchaModel($fileModel): 
	 * This method will create the table and fields if does not exist in the database
	 * also this is the brain of the micro ORM.
	 */
	static private function __SenchaModel($fileModel)
	{
		// skip this entire routine if freeze option is true
		if(self::$__freeze) return true;
		try
		{
			// get the the model of the table from the sencha .js file
			self::$__senchaModel = self::__getSenchaModel($fileModel);
			if(!self::$__senchaModel['fields']) throw new Exception('There are no fields set.');
			
			// check if the table property is an array, if not get back the array is a table string.
			$table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);
		
			// verify the existence of the table if it does not exist create it
			$recordSet = self::$__conn->query("SHOW TABLES LIKE '".$table."';");
			if(isset($recordSet)) self::__createTable($table);
			
			// Remove from the model those fields that are not meant to be stored
			// on the database-table and remove the id from the workingModel.
			$workingModel = (array)self::$__senchaModel['fields'];
			unset($workingModel[self::__recursiveArraySearch('id', $workingModel)]);
			foreach($workingModel as $key => $SenchaModel) if(isset($SenchaModel['store']) && $SenchaModel['store'] === false) unset($workingModel[$key]); 
			
			// get the table column information and remove the id column
			$recordSet = self::$__conn->query("SHOW FULL COLUMNS IN ".$table.";");
			$tableColumns = $recordSet->fetchAll(PDO::FETCH_ASSOC);
			unset($tableColumns[self::__recursiveArraySearch('id', $tableColumns)]);
			
			// check if the table has columns, if not create them.
			// we start with 1 because the microORM always create the id.
			if( count($tableColumns) <= 1 ) 
			{
				self::__createAllColumns($workingModel);
				return true;
			}
			// Also check if there is difference between the model and the 
			// database table in terms of number of fields.
			elseif(count($workingModel) != (count($tableColumns)))
			{
				// remove columns from the table
				foreach($tableColumns as $column) if( !is_numeric(self::__recursiveArraySearch($column['Field'], $workingModel)) ) self::__dropColumn($column['Field']);
				// add columns to the table
				foreach($workingModel as $column) if( !is_numeric(self::__recursiveArraySearch($column['name'], $tableColumns)) ) self::__createColumn($column);
			}
			// if everything else passes, check for differences in the columns.
			else
			{
				// modify the table columns if is not equal to the Sencha Model
				foreach($tableColumns as $column)
				{
					$change = 'false';
					foreach($workingModel as $SenchaModel)
					{
						// if the field is found, start the comparison
						if($SenchaModel['name'] == $column['Field'])
						{
							// the following code will check if there is a dataType property if not, take the type instead 
							// on the model and parse it too.
							$modelDataType = (isset($SenchaModel['dataType']) ? $SenchaModel['dataType'] : $SenchaModel['type']);
							if($modelDataType == 'string') $modelDataType = 'varchar';
							if($modelDataType == 'bool' && $modelDataType == 'boolean') $modelDataType = 'tinyint';
							
							// check for changes on the field type is a obligatory thing
							if(strripos($column['Type'], $modelDataType) === false) $change = 'true'; // Type 
							
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
							// but first check if the auto increment is used on the sencha model.
							if(isset($SenchaModel['autoIncrement'])) if($column['Extra'] != ($SenchaModel['autoIncrement'] ? 'auto_increment' : '') ) $change = 'true'; // auto increment
							
							// check if the comment is changed on the model,
							// but first check if the comment is used on the sencha model.
							if(isset($SenchaModel['comment'])) if($column['Comment'] != $SenchaModel['comment']) $change = 'true';
							
							// Modify the column on the database							
							if($change == 'true') self::__modifyColumn($SenchaModel);
						}
					}
				}
			}
			return true;
		}
		catch(PDOException $e)
		{
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}
	
	/**
	 * __getSenchaModel($fileModel):
	 * This method is used by SechaModel method to get all the table and column
	 * information inside the Sencha Model .js file 
	 */
	static private function __getSenchaModel($fileModel)
	{
		try
		{
			// Getting Sencha model as a namespace
			$senchaModel = self::__getFileContent($fileModel);
			// clean comments and unnecessary Ext.define functions
			$senchaModel = preg_replace("((/\*(.|\n)*?\*/|//(.*))|([ ](?=(?:[^\'\"]|\'[^\'\"]*\')*$)|\t|\n|\r))", '', $senchaModel);
			$senchaModel = preg_replace("(Ext.define\('[A-Za-z0-9.]*',|\);|\"|proxy(.|\n)*},)", '', $senchaModel); 
			// wrap with double quotes to all the properties
			$senchaModel = preg_replace('/(,|\{)(\w*):/', "$1\"$2\":", $senchaModel);
			// wrap with double quotes float numbers
			$senchaModel = preg_replace("/([0-9]+\.[0-9]+)/", "\"$1\"", $senchaModel);
			// replace single quotes for double quotes
			// TODO: refine this to make sure doesn't replace apostrophes used in comments. example: don't
			$senchaModel = preg_replace("(')", '"', $senchaModel);

			$model = (array)json_decode($senchaModel, true);
			if(!count($model)) throw new Exception("Something whent wrong converting it to an array, a bad lolo.");
			
			// check if there are a defined table from the model
			if(!isset($model['table'])) throw new Exception("Table property is not defined on Sencha Model. 'table:'");
			
			// check if there are a defined fields from the model
			if(!isset($model['fields'])) throw new Exception("Fields property is not defined on Sencha Model. 'fields:'");
			return $model;
		}
		catch(Exception $e)
		{
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __getFileContent($file, $type = 'js'):
	 * Load a Sencha Model from .js file
	 */
	static private function __getFileContent($file, $type = 'js')
	{
		try
		{
			$file = (string)str_replace('App.', '', $file);
			$file = str_replace('.', '/', $file);
			if(!file_exists(self::$__app.'/'.$file.'.'.$type)) throw new Exception('Sencha file "'.self::$__app.'/'.$file.'.'.$type.'" not found.');
			return (string)file_get_contents(self::$__app.'/'.$file.'.'.$type);
		}
		catch(Exception $e)
		{
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function __setSenchaModelData($fileData):
	 * Method to grab data and insert it into the table.
	 * it uses pcntl_fork to do batches of 500 records at the same
	 * time.
	 * TODO: Needs more work.
	 */
	static public function __setSenchaModelData($fileData)
	{
		try
		{
			$dataArray = json_decode(self::__getFileContent($fileData, 'json'), true);
			if(!count($dataArray)) throw new Exception("Something whent wrong converting it to an array, a bad lolo.");
			$table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);
			$columns = 'INSERT INTO `'.$table.'` (`'.implode('`,`', array_keys($dataArray[0]) ).'`) VALUES ';
			
			$rowCount = (int)0;
			$valuesEncapsulation = (string)'';
			foreach($dataArray as $key => $data)
			{
				$values  = array_values($data);
				foreach($values as $index => $val) if($val == null) $values[$index] = 'NULL';
				$valuesEncapsulation  .= '(\''.implode('\',\'',$values).'\')';
				if( $rowCount == 500 || $key == end(array_keys($dataArray)))
				{
					$pid = pcntl_fork();
					if ($pid == -1) 
					{
						throw new Exception("Could not fork the proccess.");
					} 
					else if ($pid) 
					{
						// we are the parent
						//Protect against Zombie children
						pcntl_wait($status);
					} 
					else 
					{
						Matcha::$__conn->query($columns.$valuesEncapsulation.';');
						exit($rowCount);
					}
					$valuesEncapsulation = '';
					$rowCount = 0;
				}
				else 
				{
					$valuesEncapsulation .= ', ';
					$rowCount++;
				}
			}
			return true;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * function __createTable():
	 * Method to create a table if does not exist with a BIGINT as id
	 * also if the sencha model has an array on the table go ahead and
	 * proccess the table options. 
	 */
	static private function __createTable()
	{
	    try
	    {
	    	$table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);
			self::$__conn->exec('CREATE TABLE IF NOT EXISTS '.$table.' (id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY) '.self::__renderTableOptions().';');
		    
			// if $__senchaModel['table']['data'] is set and there is data upload the data to the table. 
		    if(is_array(self::$__senchaModel['table']['data']))
			{
			    $rec = self::$__conn->prepare('SELECT * FROM '.$table);
			    if($rec->rowCount() == 0 && isset(self::$__senchaModel['table']['data']))
			    {
					self::__setSenchaModelData(self::$__senchaModel['table']['data']);
				}
			}
			return true;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
	
	/**
	 * function __renderTableOptions():
	 * Render and return a well formed Table Options for the creating table.
	 */
	static private function __renderTableOptions()
	{
		$tableOptions = (string)'';
		if(!is_array(self::$__senchaModel['table'])) return false;
		if( isset(self::$__senchaModel['table']['InnoDB']) ) $tableOptions .= 'ENGINE = '.self::$__senchaModel['table']['InnoDB'].' ';
		if( isset(self::$__senchaModel['table']['autoIncrement']) ) $tableOptions .= 'AUTO_INCREMENT = '.self::$__senchaModel['table']['autoIncrement'].' ';
		if( isset(self::$__senchaModel['table']['charset']) ) $tableOptions .= 'CHARACTER SET = '.self::$__senchaModel['table']['charset'].' ';
		if( isset(self::$__senchaModel['table']['collate']) ) $tableOptions .= 'COLLATE = '.self::$__senchaModel['table']['collate'].' ';
		if( isset(self::$__senchaModel['table']['comment']) ) $tableOptions .= "COMMENT = '".self::$__senchaModel['table']['comment']."' ";
		return $tableOptions;
	}
	 
	/**
	 * function __createAllColumns($paramaters = array()):
	 * This method will create all the columns inside the table of the database
	 * method used by SechaModel method
	 */
	static private function __createAllColumns($paramaters = array())
	{
		try
		{
			foreach($paramaters as $column) self::__createColumn($column);
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
	
	/**
	 * function __createColumn($column = array()):
	 * Method that will create a single column into the table
	 */
	static private function __createColumn($column = array())
	{
		try
		{
			$table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);
			self::$__conn->query('ALTER TABLE '.$table.' ADD '.$column['name'].' '.self::__renderColumnSyntax($column) . ';');
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}		
	}
	
	/**
	 * function __modifyColumn($SingleParamater = array()):
	 * Method to modify a single column properties
	 */
	static private function __modifyColumn($SingleParamater = array())
	{
		try
		{
			$table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);
			self::$__conn->query('ALTER TABLE '.$table.' MODIFY '.$SingleParamater['name'].' '.self::__renderColumnSyntax($SingleParamater) . ';');
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
	
	/**
	 * function createDatabase($databaseName):
	 * Method that will create a database, but will create it if
	 * it does not exist.
	 */
	static private function __createDatabase($databaseName)
	{
		try
		{
			self::$__conn->query('CREATE DATABASE IF NOT EXISTS '.$databaseName.';');
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
	
	/**
	 * function __dropColumn($column):
	 * Method to drop column in a table
	 */
	static private function __dropColumn($column)
	{
		try
		{
			$table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);
			self::$__conn->query("ALTER TABLE ".$table." DROP COLUMN `".$column."`;");
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
	
	/**
	 * function __renderColumnSyntax($column = array()):
	 * Method that will render the correct syntax for the addition or modification
	 * of a column.
	 */
	static private function __renderColumnSyntax($column = array())
	{
		// parse some properties on Sencha model.
		// and do the defaults if properties are not set.
		$columnType = (string)'';
		if(isset($column['dataType'])): $columnType = strtoupper($column['dataType']);
		elseif($column['type'] == 'string' ): $columnType = 'VARCHAR';
		elseif($column['type'] == 'int'): 
			$columnType = 'INT';
			$column['len'] = (isset($column['len']) ? $column['len'] : 11);
		elseif($column['type'] == 'bool' || $column['type'] == 'boolean'):
			$columnType = 'TINYINT';
			$column['len'] = (isset($column['len']) ? $column['len'] : 1);
		elseif($column['type'] == 'date'): $columnType = 'DATETIME';
		elseif($column['type'] == 'float'): $columnType = 'FLOAT';
		else: return false;
		endif;
		
		// render the rest of the sql statement
		switch ($columnType)
		{
			case 'BIT'; case 'TINYINT'; case 'SMALLINT'; case 'MEDIUMINT'; case 'INT'; case 'INTEGER'; case 'BIGINT':
				return $columnType.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].') ' : '') : '').
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['comment']) ? ($column['comment'] ? "COMMENT '".$column['comment']."' " : '') : '' ).
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' ).
				( isset($column['autoIncrement']) ? ($column['autoIncrement'] ? 'AUTO_INCREMENT ' : '') : '' ).
				( isset($column['primaryKey']) ? ($column['primaryKey'] ? 'PRIMARY KEY ' : '') : '' );
				break;
			case 'REAL'; case 'DOUBLE'; case 'FLOAT'; case 'DECIMAL'; case 'NUMERIC':
				return $columnType.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].')' : '(10,2)') : '(10,2)').
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['comment']) ? ($column['comment'] ? "COMMENT '".$column['comment']."' " : '') : '' ).
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' ).
				( isset($column['autoIncrement']) ? ($column['autoIncrement'] ? 'AUTO_INCREMENT ' : '') : '' ).
				( isset($column['primaryKey']) ? ($column['primaryKey'] ? 'PRIMARY KEY ' : '') : '' );
				break;
			case 'DATE'; case 'TIME'; case 'TIMESTAMP'; case 'DATETIME'; case 'YEAR':
				return $columnType.' '.
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['comment']) ? ($column['comment'] ? "COMMENT '".$column['comment']."' " : '') : '' ).
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' );
				break;
			case 'CHAR'; case 'VARCHAR':
				return $columnType.' '.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].') ' : '(255)') : '(255)').
				( isset($column['defaultValue']) ? (is_numeric($column['defaultValue']) && is_string($column['defaultValue']) ? "DEFAULT '".$column['defaultValue']."' " : '') : '').
				( isset($column['comment']) ? ($column['comment'] ? "COMMENT '".$column['comment']."' " : '') : '' ).
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' );
				break;
			case 'BINARY'; case 'VARBINARY':
				return $columnType.' '.
				( isset($column['len']) ? ($column['len'] ? '('.$column['len'].') ' : '') : '').
				( isset($column['allowNull']) ? ($column['allowNull'] ? '' : 'NOT NULL ') : '' ).
				( isset($column['comment']) ? ($column['comment'] ? "COMMENT '".$column['comment']."'" : '') : '' );
				break;
			case 'TINYBLOB'; case 'BLOB'; case 'MEDIUMBLOB'; case 'LONGBLOB'; case 'TINYTEXT'; case 'TEXT'; case 'MEDIUMTEXT'; case 'LONGTEXT':
				return $columnType.' '.
				( isset($column['allowNull']) ? ($column['allowNull'] ? 'NOT NULL ' : '') : '' ).
				( isset($column['comment']) ? ($column['comment'] ? "COMMENT '".$column['comment']."'" : '') : '' );
				break;
		}
		return true;
	}
	
	/**
	 * function __recursiveArraySearch($needle,$haystack):
	 * An recursive array search method
	 */
	static private function __recursiveArraySearch($needle,$haystack) 
	{
	    foreach($haystack as $key=>$value) 
	    {
	        $current_key=$key;
	        if($needle===$value OR (is_array($value) && self::__recursiveArraySearch($needle,$value) !== false)) return $current_key;
	    }
	    return false;
	}
	
}
