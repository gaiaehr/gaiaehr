<?php
/**
 * Matcha::connect microORM v0.0.1
 * This set of classes will help Sencha ExtJS and PHP developers deliver fast and powerful application fast and easy to develop.
 * If Sencha ExtJS is a GUI Framework of the future, think Matcha micrORM as the bridge between the Client-Server
 * GAP. 
 * 
 * Matcha will read and parse a Sencha Model .js file and then connect to the database and produce a compatible database-table
 * from your model. Also will provide the basic functions for the CRUD. If you are familiar with Sencha ExtJS, and know 
 * about Sencha Models, you will need this PHP Class. You can use it in any way you want, in MVC like pattern, your own pattern, 
 * or just playing simple. It's compatible with all your coding style. 
 * 
 * Taking some ideas from diferent microORM's and full featured ORM's we bring you this cool Class. 
 * 
 * History:
 * Born in the fields of GaiaEHR we needed a way to develop the application more faster, Gino Rivera suggested the use of an
 * microORM for fast development and the development began. We tried to use some already developed and well known ORM's on the 
 * space of PHP, but none satisfied our needs. So Gino Rivera sugested the development of our own microORM (a long way to run).
 * 
 * But despite the long run, it returned to be more logical to get ideas from the well known ORM's and how Sensha manage their models
 * so this is the result. 
 *  
 */
 
class MatchaCUP extends Matcha
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
			$sql = (string)"DELETE FROM ".Matcha::$__senchaModel['table']."WHERE id='".$record['id']."';";
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