<?php
/**
* Matcha::connect microORM v0.0.1
* This would be a complete set of methods to manage the database
* creation and data exchange.
* 
* In the future this will replace the entire old class methods. 
*/


//
//--- Matcha Class --------------------------------------------------------------------------------------------------------------
//
class Matcha
{
	 
	/**
	 * This would be a Sencha Model parsed by getSenchaModel method
	 */
	public public $Relation;
	public public $currentRecord;
	public static $__id;
	public static $__total;
	public static $__freeze = false;
	public static $__senchaModel;
	public static $__conn;
	public static $__root;
	 
	 /**
	  * function connect($databaseObject, $rootPath, $senchaModel)
	  * The first thing to do, to begin using Matcha
	  * This will load the Sencha Model to Matcha and do it's magic.
	  */
	 static public function connect($databaseObject, $rootPath = NULL, $senchaModel = array())
	 {
	 	try
	 	{
	 		if(!is_object($databaseObject) && !isset($rootPath) && !is_array($senchaModel)) throw new Exception('Matcha::connect databaseObject, rootPath or senchaModel is not set.');
	 		self::$__conn = $databaseObject;
			self::$__root = $rootPath;
			self::__SenchaModel($senchaModel);
			$matcha = new MatchaCRUD();
			return $matcha;
		}
		catch(Exception $e)
		{
			return self::__errorProcess($e);
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
		self::$__freeze = $onoff;
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
			if(!self::$__senchaModel['fields']) return false;
		
			// verify the existence of the table if it does not exist create it
			$recordSet = self::$__conn->query("SHOW TABLES LIKE '".self::$__senchaModel['table']."';");
			if( isset($recordSet) ) self::__createTable(self::$__senchaModel['table']);
			
			// Remove from the model those fields that are not meant to be stored
			// on the database and remove the id from the workingModel.
			$workingModel = (array)self::$__senchaModel['fields'];
			unset($workingModel[self::__recursiveArraySearch('id', $workingModel)]);
			foreach($workingModel as $key => $SenchaModel) if(isset($SenchaModel['store']) && $SenchaModel['store'] == false) unset($workingModel[$key]); 
			
			// get the table column information and remove the id column
			$recordSet = self::$__conn->query("SHOW FULL COLUMNS IN ".self::$__senchaModel['table'].";");
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
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
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
			$fileModel = str_replace('App', 'app', $fileModel);
			$fileModel = str_replace('.', '/', $fileModel);
			$senchaModel = (string)file_get_contents(self::$__root . '/' . $fileModel . '.js');
			
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
			if(!count($model)) throw new Exception("Ops something whent wrong converting it to an array.");
			
			// get the table from the model
			if(!isset($model['table'])) throw new Exception("Table property is not defined on Sencha Model. 'table:'");

			if(!isset($model['fields'])) throw new Exception("Fields property is not defined on Sencha Model. 'fields:'");
			return $model;
		}
		catch(Exception $e)
		{
			return self::__errorProcess($e);
		}
	}

	/**
	 * function __getRelationFromModel():
	 * Method to get the relation from the model if has any
	 */
	static private function __getRelationFromModel()
	{
		try
		{
			// first check if the sencha model object has some value
			self::$Relation = 'none';
			if(isset(self::$__senchaModel)) throw new Exception("Sencha Model is not configured.");
			
			// check if the model has the associations property 
			if(isset(self::$__senchaModel['associations']))
			{
				self::$Relation = 'associations';
				// load all the models.
				foreach(self::$__senchaModel['associations'] as $relation)
				{ 
					self::SenchaModel(self::$__senchaModel['associations']);
					self::$RelationStatement[] = self::__leftJoin(
					array(
						'fromId'=>(isset(self::$__senchaModel['associations']['primaryKey']) ? self::$__senchaModel['associations']['foreignKey'] : 'id'),
						'toId'=>self::$__senchaModel['associations']['foreignKey']
					));
				}
			}
			
			// check if the model has the associations property 
			if(isset(self::$__senchaModel['hasOne']))
			{
				self::$Relation = 'hasOne';
				self::$RelationStatement[] = self::__leftJoin(
				array(
					'fromId'=>(isset(self::$__senchaModel['associations']['primaryKey']) ? self::$__senchaModel['associations']['foreignKey'] : 'id'),
					'toId'=>self::$__senchaModel['associations']['foreignKey']
				));
			}
			
			// check if the model has the associations property 
			if(isset(self::$__senchaModel['hasMany']))
			{
				self::$Relation = 'hasMany';
				self::$RelationStatement[] = self::__leftJoin(
				array(
					'fromId'=>(isset(self::$__senchaModel['associations']['primaryKey']) ? self::$__senchaModel['associations']['foreignKey'] : 'id'),
					'toId'=>self::$__senchaModel['associations']['foreignKey']
				));
			}
			
			// check if the model has the associations property 
			if(isset(self::$__senchaModel['belongsTo']))
			{
				self::$Relation = 'belongsTo';
				self::$RelationStatement[] = self::__leftJoin(
				array(
					'fromId'=>(isset(self::$__senchaModel['associations']['primaryKey']) ? self::$__senchaModel['associations']['foreignKey'] : 'id'),
					'toId'=>self::$__senchaModel['associations']['foreignKey']
				));
			}
			
			return true;
		}
		catch(Exception $e)
		{
			return self::__errorProcess($e);
		}
	}

	/**
	 * function __leftJoin($joinParameters = array()):
	 * A left join returns all the records in the “left” table (T1) whether they 
	 * have a match in the right table or not. If, however, they do have a match 
	 * in the right table – give me the “matching” data from the right table as well. 
	 * If not – fill in the holes with null.
	 */
	static private function __leftJoin($joinParameters = array())
	{
		return (string)' LEFT JOIN ' . $joinParameters['relateTable'].' ON ('.self::$__senchaModel['table'].'.'.$joinParameters['fromId'].' = '.$joinParameters['relateTable'].'.'.$joinParameters['toId'].') ';
	}
	
	/**
	 * function __innerJoin($joinParameters = array()):
	 * An inner join only returns those records that have “matches” in both tables. 
	 * So for every record returned in T1 – you will also get the record linked by 
	 * the foreign key in T2. In programming logic – think in terms of AND.
	 */
	static private function __innerJoin($joinParameters = array())
	{
		return (string)' INNER JOIN ' . $joinParameters['relateTable'].' ON ('.self::$__senchaModel['table'].'.'.$joinParameters['fromId'].' = '.$joinParameters['relateTable'].'.'.$joinParameters['toId'].') ';
	}

	/**
	 * function __setSenchaModel($senchaModelObject):
	 * Set the Sencha Model by an object
	 * Useful to pass the model via an object, instead of using the .js file
	 * it can be constructed dynamically.
	 * TODO: Finish me!
	 */
	static private function __setSenchaModel($senchaModelObject)
	{
		
	}
	
	/**
	 * function __createTable():
	 * Method to create a table if does not exist
	 */
	 static private function __createTable()
	 {
	 	try
	 	{
			self::$__conn->query('CREATE TABLE IF NOT EXISTS '.self::$__senchaModel['table'].' (id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY);');
			return true;
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
		}
	 }
	 
	/**
	 * function __createAllColumns($paramaters = array()):
	 * This method will create the column inside the table of the database
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
			return self::__errorProcess($e);
		}
	}
	
	/**
	 * function __createColumn($column = array()):
	 * Method that will create the column into the table
	 */
	static private function __createColumn($column = array())
	{
		try
		{
			self::$__conn->query('ALTER TABLE '.self::$__senchaModel['table'].' ADD '.$column['name'].' '.self::__renderColumnSyntax($column) . ';');
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
		}		
	}
	
	/**
	 * function __modifyColumn($SingleParamater = array()):
	 * Method to modify the column properties
	 */
	static private function __modifyColumn($SingleParamater = array())
	{
		try
		{
			self::$__conn->query('ALTER TABLE '.self::$__senchaModel['table'].' MODIFY '.$SingleParamater['name'].' '.self::__renderColumnSyntax($SingleParamater) . ';');
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
		}
	}
	
	/**
	 * function createDatabase($databaseName):
	 * Method that will create a database
	 */
	static public function createDatabase($databaseName)
	{
		try
		{
			self::$__conn->query('CREATE DATABASE IF NOT EXISTS '.$databaseName.';');
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
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
			self::$__conn->query("ALTER TABLE ".self::$__senchaModel['table']." DROP COLUMN `".$column."`;");
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
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
		if(isset($column['dataType'])) 
		{
			$columnType = strtoupper($column['dataType']);
		}
		elseif($column['type'] == 'string' )
		{
			$columnType = 'VARCHAR';
		}
		elseif($column['type'] == 'int')
		{
			$columnType = 'INT';
			$column['len'] = (isset($column['len']) ? $column['len'] : 11);
		}
		elseif($column['type'] == 'bool' || $column['type'] == 'boolean')
		{
			$columnType = 'TINYINT';
			$column['len'] = (isset($column['len']) ? $column['len'] : 1);
		}
		elseif($column['type'] == 'date')
		{
			$columnType = 'DATE';
		}
		elseif($column['type'] == 'float')
		{
			$columnType = 'FLOAT';
		}
		else
		{
			return false;
		}
		
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
	 * __recursiveArraySearch($needle,$haystack):
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
	
	/**
	 * function __errorProcess($errorException):
	 * Handle the error of an exception
	 * TODO: It could be more elaborated and handle other things.
	 * for example log file for GaiaEHR.
	 */
	static private function __errorProcess($errorException)
	{
		error_log('self::connect microORM: ' . $errorException->getMessage() );
		return $errorException;
	}
}

//
//--- MatchaAudit Class --------------------------------------------------------------------------------------------------------------
//
class MatchaAudit extends Matcha
{
	/**
	 * function __auditLog($sqlStatement = ''):
	 * Every store has to be logged into the database.
	 * Also generate the table if does not exist.
	 */
	static public function __auditLog($sqlStatement = '')
	{
		// generate the appropriate event log comment 
		$record = array();
		$eventLog = (string)"Event triggered but never defined.";
		if (stristr($sqlStatement, 'INSERT')) $eventLog = 'Record insertion';
		if (stristr($sqlStatement, 'DELETE')) $eventLog = 'Record deletion';
		if (stristr($sqlStatement, 'UPDATE')) $eventLog = 'Record update';

		// allocate the event data
		$eventData['date'] = date('Y-m-d H:i:s', time());
		$eventData['event'] = $eventLog;
		$eventData['comments'] = $sqlStatement;
		$eventData['user'] = $_SESSION['user']['name'];
		$eventData['checksum'] = crc32($sqlStatement);
		$eventData['facility'] = $_SESSION['site']['dir'];
		$eventData['patient_id'] = $_SESSION['patient']['pid'];
		$eventData['ip'] = $_SESSION['server']['REMOTE_ADDR'];
		
		try
		{
			//check if the table exist
			$recordSet = self::$__conn->query("SHOW TABLES LIKE '".self::$__senchaModel['table']."';");
			if( $recordSet->fetch(PDO::FETCH_ASSOC) ) self::__createTable('log');
			unset($recordSet);
			
			//check for the available fields
			$recordSet = self::$__conn->query("SHOW COLUMNS IN ".self::$__senchaModel['table'].";");
			if( $recordSet->fetchAll(PDO::FETCH_ASSOC) ) self::__logModel();
			unset($recordSet);
				
			// insert the event log
			$fields = (string)implode(', ', array_keys($eventData));
			$values = (string)implode(', ', array_values($eventData));
			self::$__conn->query('INSERT INTO log ('.$fields.') VALUES ('.$values.');');
			return self::$__conn->lastInsertId();
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
		}
	}

	/**
	 * function __logModel():
	 * Method to create the log table columns
	 */
	static public function __logModel()
	{
		try
		{
			self::$__conn->query("CREATE TABLE IF NOT EXISTS `log` (
						`id` bigint(20) NOT NULL AUTO_INCREMENT,
						`date` datetime DEFAULT NULL,
						`event` varchar(255) DEFAULT NULL,
						`user` varchar(255) DEFAULT NULL,
						`facility` varchar(255) NOT NULL,
						`comments` longtext,
						`user_notes` longtext,
						`patient_id` bigint(20) DEFAULT NULL,
						`success` tinyint(1) DEFAULT '1',
						`checksum` longtext,
						`crt_user` varchar(255) DEFAULT NULL,
						`ip` varchar(50) DEFAULT NULL,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
			return true;
		}
		catch(PDOException $e)
		{
			return self::__errorProcess($e);
		}
	}
}


//
//--- MatchaCRUD Class --------------------------------------------------------------------------------------------------------------
//
class MatchaCRUD extends Matcha
{
	/**
	 * function store($record = array()): (part of CRUD)
	 * Create & Update
	 * store the record as array into the working table
	 */
	static public function store($record = array())
	{
		try
		{
			// update a record
			if(isset($record['id']))
			{
				$storeField = (string)'';
				foreach($record as $key => $value) ($key=='id' ? $storeField .= '' : $storeField .= $key."='".$value."'");
				$sql = (string)'UPDATE '.Matcha::$__senchaModel['table'].' SET '.$storeField . " WHERE id='".$record['id']."';";
				Matcha::$__conn->query($sql);
				MatchaAudit::__auditLog($sql);
				Matcha::$__id = $record['id'];
			}
			// create a record
			else
			{
				$fields = (string)implode(', ', array_keys($record));
				$values = (string)implode(', ', array_values($record));
				$sql = (string)'INSERT INTO '.Matcha::$__senchaModel['table'].' ('.$fields.') VALUES ('.$values.');';
				Matcha::$__conn->query($sql);
				MatchaAudit::__auditLog($sql);
				Matcha::$__id = $__conn->lastInsertId();
			}
			return true;
		}
		catch(PDOException $e)
		{
			return Matcha::__errorProcess($e);
		}
	}
	
	/**
	 * function trash($record = array()): (part of CRUD)
	 * Delete
	 * will delete the record indicated by an id
	 */
	static public function trash($record = array())
	{
		try
		{
			$sql = "DELETE FROM ".Matcha::$__senchaModel['table']."WHERE id='".$record['id']."';";
			Matcha::$__conn->query($sql);
			MatchaAudit::__auditLog($sql);
			Matcha::$__total = (int)count($records)-1;
			if(Matcha::$__id == $record['id']) unset(self::$__id);
			return true; // success
		}
		catch(PDOException $e)
		{
			return Matcha::__errorProcess($e);
		}
	}

	/**
	 * function load($id = NULL, $columns = array()) (part of CRUD)
	 * Read from table
	 * Load all records, load one record if a ID is passed,
	 * load all records with some columns determined by an array,
	 * load one record with some columns determined by an array, or any combination.  
	 */
	static public function load($id = NULL, $columns = array())
	{
		try
		{
			$selectedColumns = (string)'';
			if(count($columns)) $selectedColumns = implode(', '.Matcha::$__senchaModel['table'].'.', $columns);
			$recordSet = Matcha::$__conn->query("SELECT ".($selectedColumns ? Matcha::$__senchaModel['table'].".".$selectedColumns : '*').
				" FROM ".Matcha::$__senchaModel['table'].
				($id ? " WHERE ".Matcha::$__senchaModel['table'].".id='".$id."'" : "").";");
			$records = (array)$recordSet->fetchAll(PDO::FETCH_ASSOC);
			Matcha::$__total = (int)count($records);
			return $records;
		}
		catch(PDOException $e)
		{
			return Matcha::__errorProcess($e);
		}
	}
}