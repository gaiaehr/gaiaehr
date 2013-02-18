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
include_once('Matcha.php');

class MatchaCUP
{
	/**
	 * @var array Model array
	 */
	public static $model;
	public static $rowsAffected;
	public static $lastInsertId;


	/**
	 * function load($id = NULL, $columns = array()) (part of CRUD)
	 * Read from table
	 * Load all records, load one record if a ID is passed,
	 * load all records with some columns determined by an array,
	 * load one record with some columns determined by an array, or any combination.
	 */
	static public function load($where = null, $columns = null)
	{
		try
		{
			// columns
			if($columns == null){
				$columnsx = '*';
			}elseif(is_array($columns)){
				$columnsx = '`'.implode('`,`',$columns).'`';
			}else{
				$columnsx = $columns;
			}
			// where
			if(is_integer($where)){
				$wherex = "`id`='$where'";
			}elseif(is_array($where)){
				$wherex = self::parseWhereArray($where);
			}else{
				$wherex = $where;
			}
			if($where != null) $wherex = 'WHERE '.$wherex;
			// table
			$table = self::$model->table->name;
			// sql build
			$sql = "SELECT $columnsx FROM `$table` $wherex";
			return Matcha::$__conn->query($sql);
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}


	/**
	 * function store($record = array()): (part of CRUD)
	 * Create & Update
	 * store the record as array into the working table
	 */
	static public function store($record)
	{
		try
		{
			$record = (is_object($record) ? get_object_vars($record) : $record);
			$table = self::$model->table->name;
			// create a record
			if(!isset($record['id']))
			{
				$columns = array_keys($record);
				$columns = '(`'.implode('`,`',$columns).'`)';
				$values  = array_values($record);
				$values  = '(\''.implode('\',\'',$values).'\')';
				$sql = "INSERT INTO `$table` $columns VALUES $values";
				self::$rowsAffected = Matcha::$__conn->exec($sql);
				self::$lastInsertId = Matcha::$__conn->lastInsertId();
			}
			// update a record
			else
			{
				$values = array();
				$id = $record['id'];
				unset($record['id']);
				foreach($record as $key => $val) $values[] = "`$key`='$val'";
				$values = implode(',',$values);
				$sql = "UPDATE `$table` SET $values WHERE id='$id'";
				self::$rowsAffected = Matcha::$__conn->exec($sql);
			}
			return true;
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
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
//			$sql = (string)"DELETE FROM ".Matcha::$__senchaModel['table']."WHERE id='".$record['id']."';";
//			Matcha::$__conn->query($sql);
//			MatchaAudit::__auditLog($sql);
//			Matcha::$__total = (int)count($records)-1;
//			if(Matcha::$__id == $record['id']) unset(self::$__id);
//			return true; // success
		}
		catch(PDOException $e)
		{
//			return Matcha::__errorProcess($e);
		}
	}


	static public function setModel($model){
		self::$model = self::ArrayToObject($model);
	}

	static private function ArrayToObject(array $array, stdClass $parent = null) {
		if ($parent === null) {
			$parent = new stdClass;
		}
		foreach ($array as $key => $val) {
			if (is_array($val)) {
				$parent->$key = self::ArrayToObject($val, new stdClass);
			} else {
				$parent->$key = $val;
			}
		}
		return $parent;
	}

	static private function parseWhereArray($array){
		$whereStr = '';
		$prevArray = false;
		foreach($array as $key => $val){
			if(is_string($key)){
				if($prevArray) $whereStr .= 'AND ';
				$whereStr .= "`$key`='$val' ";
				$prevArray = true;
			}elseif(is_array($val)){
				if($prevArray) $whereStr .= 'AND ';
				$whereStr .= '('.self::parseWhereArray($val).')';
				$prevArray = true;
			}else{
				$whereStr .= $val.' ';
				$prevArray = false;
			}
		}
		return $whereStr;
	}

}