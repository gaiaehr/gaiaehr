<?php
/**
* Matcha::connect microORM v0.0.1
* This would be a complete set of methods to manage the database
* creation and data exchange.
* 
* In the future this will replace the entire old class methods. 
*/

class Matcha
{
	 
	/**
	 * This would be a Sencha Model parsed by getSenchaModel method
	 */
	public $Relation;
	public $currentRecord;
	private $__id;
	private $__total;
	private $__freeze = false;
	private $__senchaModel;
	private $__conn;
	 
	 /**
	  * connect:
	  */
	 public function connect($databaseObject)
	 {
		$this->__conn = $databaseObject;
	 }

	/**
	 * store: (part of CRUD)
	 * Create & Update
	 * store the record as array into the working table
	 */
	public function store($record = array())
	{
		try
		{
			// update a record
			if(isset($record['id']))
			{
				$storeField = (string)'';
				foreach($record as $key => $value) ($key=='id' ? $storeField .= '' : $storeField .= $key."='".$value."'");
				$sql = (string)'UPDATE '.$this->__senchaModel['table'].' SET '.$storeField . " WHERE id='".$record['id']."';";
				$this->__conn->query($sql);
				$this->__auditLog($sql);
				$this->__id = $record['id'];
			}
			// create a record
			else
			{
				$fields = (string)implode(', ', array_keys($record));
				$values = (string)implode(', ', array_values($record));
				$sql = (string)'INSERT INTO '.$this->__senchaModel['table'].' ('.$fields.') VALUES ('.$values.');';
				$this->__conn->query($sql);
				$this->__auditLog($sql);
				$this->__id = $this->__conn->lastInsertId();
			}
			return true;
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	}
	
	/**
	 * trash: (part of CRUD)
	 * Delete
	 * will delete the record indicated by an id
	 */
	public function trash($record = array())
	{
		try
		{
			$sql = "DELETE FROM ".$this->__senchaModel['table']."WHERE id='".$record['id']."';";
			$this->__conn->query($sql);
			$this->__auditLog($sql);
			$this->__total = (int)count($records)-1;
			if($this->__id == $record['id']) unset($this->__id);
			return true;
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	}

	/**
	 * load: (part of CRUD)
	 * Read
	 * Load all records, load one record if a ID is passed,
	 * load all records with some columns determined by an array,
	 * load one record with some columns determined by an array, or any combination.  
	 */
	public function load($id = NULL, $columns = array())
	{
		try
		{
			$selectedColumns = (string)'';
			if(count($columns)) $selectedColumns = implode(', '.$this->__senchaModel['table'].'.', $columns);
			$recordSet = $this->__conn->query("SELECT ".($selectedColumns ? $this->__senchaModel['table'].".".$selectedColumns : '*')." FROM ".$this->__senchaModel['table'].($id ? " WHERE ".$this->__senchaModel['table'].".id='".$id."'" : "").";");
			$records = (array)$recordSet->fetchAll(PDO::FETCH_ASSOC);
			$this->__total = (int)count($records);
			return $records;
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	}
	
	/**
	 * __auditLog:
	 * Every store has to be logged into the database.
	 * Also generate the table if does not exist.
	 */
	private function __auditLog($sqlStatement = '')
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
			$recordSet = $this->__conn->query("SHOW TABLES LIKE '".$this->__senchaModel['table']."';");
			if( $recordSet->fetch(PDO::FETCH_ASSOC) ) $this->__createTable('log');
			unset($recordSet);
			
			//check for the available fields
			$recordSet = $this->__conn->query("SHOW COLUMNS IN ".$this->__senchaModel['table'].";");
			if( $recordSet->fetchAll(PDO::FETCH_ASSOC) ) $this->__logModel();
			unset($recordSet);
				
			// insert the event log
			$fields = (string)implode(', ', array_keys($eventData));
			$values = (string)implode(', ', array_values($eventData));
			$this->__conn->query('INSERT INTO log ('.$fields.') VALUES ('.$values.');');
			return $this->__conn->lastInsertId();
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	}

	/**
	 * __logModel:
	 * Method to create the log table columns
	 */
	private function __logModel()
	{
		try
		{
			$this->__conn->query("CREATE TABLE IF NOT EXISTS `log` (
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
			return $this->__errorProcess($e);
		}
	}
	
	/**
	 * getLastId:
	 * Get the last insert ID of an insert
	 * this is automatically updated by the store method
	 */
	public function getLastId()
	{
		return (int)$this->__id;
	}
	
	/**
	 * getTotal:
	 * Get the total records in a select statement
	 * this is automatically updated by the load method
	 */
	public function getTotal()
	{
		return (int)$this->__total;
	}
	
	/**
	 * freeze:
	 * freeze the database and tables alteration by the SenchaPHP microORM
	 */
	public function freeze($onoff = false)
	{
		$this->__freeze = $onoff;
	}
	
	/**
	 * SechaModel method: 
	 * This method will create the table and fields if does not exist in the database
	 * also this is the brain of the micro ORM.
	 */
	public function SenchaModel($fileModel)
	{
		// skip this entire routine if freeze option is true
		if($this->__freeze) return true;
		try
		{
			// get the the model of the table from the sencha .js file
			$this->__senchaModel = $this->__getSenchaModel($fileModel);
			if(!$this->__senchaModel['fields']) return false;
		
			// verify the existence of the table if it does not exist create it
			$recordSet = $this->__conn->query("SHOW TABLES LIKE '".$this->__senchaModel['table']."';");
			if( isset($recordSet) ) $this->__createTable($this->__senchaModel['table']);
			
			// Remove from the model those fields that are not meant to be stored
			// on the database and remove the id from the workingModel.
			$workingModel = (array)$this->__senchaModel['fields'];
			unset($workingModel[$this->__recursiveArraySearch('id', $workingModel)]);
			foreach($workingModel as $key => $SenchaModel) if(isset($SenchaModel['store']) && $SenchaModel['store'] == false) unset($workingModel[$key]); 
			
			// get the table column information and remove the id column
			$recordSet = $this->__conn->query("SHOW FULL COLUMNS IN ".$this->__senchaModel['table'].";");
			$tableColumns = $recordSet->fetchAll(PDO::FETCH_ASSOC);
			unset($tableColumns[$this->__recursiveArraySearch('id', $tableColumns)]);
			
			// check if the table has columns, if not create them.
			// we start with 1 because the microORM always create the id.
			if( count($tableColumns) <= 1 ) 
			{
				$this->__createAllColumns($workingModel);
				return true;
			}
			// Also check if there is difference between the model and the 
			// database table in terms of number of fields.
			elseif(count($workingModel) != (count($tableColumns)))
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
							if($change == 'true') $this->__modifyColumn($SenchaModel);
						}
					}
				}
			}
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	}
	
	/**
	 * __getSenchaModel:
	 * This method is used by SechaModel method to get all the table and column
	 * information inside the Sencha Model .js file 
	 */
	private function __getSenchaModel($fileModel)
	{
		try
		{
			// Getting Sencha model as a namespace
			$fileModel = str_replace('App', 'app', $fileModel);
			$fileModel = str_replace('.', '/', $fileModel);
			$senchaModel = (string)file_get_contents($_SESSION['root'] . '/' . $fileModel . '.js');
			
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
			return $this->__errorProcess($e);
		}
	}

	/**
	 * __getRelationFromModel:
	 * Method to get the relation from the model if has any
	 */
	private function __getRelationFromModel()
	{
		try
		{
			// first check if the sencha model object has some value
			$this->Relation = 'none';
			if(isset($this->__senchaModel)) throw new Exception("Sencha Model is not configured.");
			
			// check if the model has the associations property 
			if(isset($this->__senchaModel['associations']))
			{
				$this->Relation = 'associations';
				// load all the models.
				foreach($this->__senchaModel['associations'] as $relation)
				{ 
					$this->SenchaModel($this->__senchaModel['associations']);
					$this->RelationStatement[] = $this->__leftJoin(
					array(
						'fromId'=>(isset($this->__senchaModel['associations']['primaryKey']) ? $this->__senchaModel['associations']['foreignKey'] : 'id'),
						'toId'=>$this->__senchaModel['associations']['foreignKey']
					));
				}
			}
			
			// check if the model has the associations property 
			if(isset($this->__senchaModel['hasOne']))
			{
				$this->Relation = 'hasOne';
				$this->RelationStatement[] = $this->__leftJoin(
				array(
					'fromId'=>(isset($this->__senchaModel['associations']['primaryKey']) ? $this->__senchaModel['associations']['foreignKey'] : 'id'),
					'toId'=>$this->__senchaModel['associations']['foreignKey']
				));
			}
			
			// check if the model has the associations property 
			if(isset($this->__senchaModel['hasMany']))
			{
				$this->Relation = 'hasMany';
				$this->RelationStatement[] = $this->__leftJoin(
				array(
					'fromId'=>(isset($this->__senchaModel['associations']['primaryKey']) ? $this->__senchaModel['associations']['foreignKey'] : 'id'),
					'toId'=>$this->__senchaModel['associations']['foreignKey']
				));
			}
			
			// check if the model has the associations property 
			if(isset($this->__senchaModel['belongsTo']))
			{
				$this->Relation = 'belongsTo';
				$this->RelationStatement[] = $this->__leftJoin(
				array(
					'fromId'=>(isset($this->__senchaModel['associations']['primaryKey']) ? $this->__senchaModel['associations']['foreignKey'] : 'id'),
					'toId'=>$this->__senchaModel['associations']['foreignKey']
				));
			}
			
			return true;
		}
		catch(Exception $e)
		{
			return $this->__errorProcess($e);
		}
	}

	/**
	 * __leftJoin:
	 * A left join returns all the records in the “left” table (T1) whether they 
	 * have a match in the right table or not. If, however, they do have a match 
	 * in the right table – give me the “matching” data from the right table as well. 
	 * If not – fill in the holes with null.
	 */
	private function __leftJoin($joinParameters = array())
	{
		return (string)' LEFT JOIN ' . $joinParameters['relateTable'].' ON ('.$this->__senchaModel['table'].'.'.$joinParameters['fromId'].' = '.$joinParameters['relateTable'].'.'.$joinParameters['toId'].') ';
	}
	
	/**
	 * __innerJoin:
	 * An inner join only returns those records that have “matches” in both tables. 
	 * So for every record returned in T1 – you will also get the record linked by 
	 * the foreign key in T2. In programming logic – think in terms of AND.
	 */
	private function __innerJoin($joinParameters = array())
	{
		return (string)' INNER JOIN ' . $joinParameters['relateTable'].' ON ('.$this->__senchaModel['table'].'.'.$joinParameters['fromId'].' = '.$joinParameters['relateTable'].'.'.$joinParameters['toId'].') ';
	}

	/**
	 * __setSenchaModel:
	 * Set the Sencha Model by an object
	 * Useful to pass the model via an object, instead of using the .js file
	 * it can be constructed dynamically.
	 * TODO: Finish me!
	 */
	private function __setSenchaModel($senchaModelObject)
	{
		
	}
	
	/**
	 * __createTable:
	 * Method to create a table if does not exist
	 */
	 private function __createTable()
	 {
	 	try
	 	{
			$this->__conn->query('CREATE TABLE IF NOT EXISTS '.$this->__senchaModel['table'].' (id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY);');
			return true;
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	 }
	 
	/**
	 * __createColumn:
	 * This method will create the column inside the table of the database
	 * method used by SechaModel method
	 */
	private function __createAllColumns($paramaters = array())
	{
		try
		{
			foreach($paramaters as $column) $this->__createColumn($column);
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
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
			$this->__conn->query('ALTER TABLE '.$this->__senchaModel['table'].' ADD '.$column['name'].' '.$this->__renderColumnSyntax($column) . ';');
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}		
	}
	
	/**
	 * __modifyColumn:
	 * Method to modify the column properties
	 */
	private function __modifyColumn($SingleParamater = array())
	{
		try
		{
			$this->__conn->query('ALTER TABLE '.$this->__senchaModel['table'].' MODIFY '.$SingleParamater['name'].' '.$this->__renderColumnSyntax($SingleParamater) . ';');
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	}
	
	/**
	 * __createDatabase
	 * Method that will create a database
	 */
	public function createDatabase($databaseName)
	{
		try
		{
			$this->__conn->query('CREATE DATABASE IF NOT EXISTS '.$databaseName.';');
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
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
			$this->__conn->query("ALTER TABLE ".$this->__senchaModel['table']." DROP COLUMN `".$column."`;");
		}
		catch(PDOException $e)
		{
			return $this->__errorProcess($e);
		}
	}
	
	/**
	 * __renderColumnSyntax:
	 * Method that will render the correct syntax for the addition or modification
	 * of a column.
	 */
	private function __renderColumnSyntax($column = array())
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
	
	/**
	 * __errorProcess:
	 * Handle the error of an exception
	 * TODO: It could be more elaborated and handle other things.
	 * for example log file for GaiaEHR.
	 */
	private function __errorProcess($errorException)
	{
		error_log('Matcha::connect microORM: ' . $errorException->getMessage() );
		return $errorException;
	}
}