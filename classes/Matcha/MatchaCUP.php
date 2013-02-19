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
	private static $instance = false;
	/**
	 * @var array Model array
	 */
	public static $model;
	public static $table;
	public static $nolimitsql = '';
	public static $sql = '';
	public static $rowsAffected;
	public static $lastInsertId;

	static private function thisCUP(){
		if(self::$instance === false){
			self::$instance = new MatchaCUP;
		}
		return self::$instance;
	}
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
			self::$sql = '';
			$table = self::$model->table->name;
			if(!is_object($where)){
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
				// sql build
				self::$sql = "SELECT $columnsx FROM `$table` $wherex";
			}else{
				// limits
				$limits = '';
				if(isset($where->limit) || isset($where->start)){
					$limits = array();
					if(isset($where->start)) $limits[] = $where->start;
					if(isset($where->limit)) $limits[] = $where->limit;
					$limits = 'LIMIT '.implode(',', $limits);
				}

				// sort
				$sortx = '';
				if(isset($where->sort)){
					$sortx = array();
					foreach($where->sort as $sort){
						$sort = get_object_vars($sort);
						$sortx[] = implode(' ',$sort);
					}
					$sortx = 'ORDER BY '.implode(', ',$sortx);
				}
				// group
				$groupx = '';
				if(isset($where->group)){
					$property = $where->group[0]->property;
					$direction = $where->group[0]->direction;
					$groupx = "GROUP BY $property $direction";
				}
				// filter/where
				$wherex = '';
				if(isset($where->filter)){
					$wherex = array();
					foreach($where->filter as $foo){
						$wherex[] = "`$foo->property`='$foo->value'";
					}
					$wherex = 'WHERE '.implode(' AND ',$wherex);
				}
				self::$nolimitsql = "SELECT * FROM `$table` $groupx $wherex $sortx";
				self::$sql = "SELECT * FROM `$table` $groupx $wherex $sortx $limits";
			}
			return self::thisCUP();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public static function all(){
		try{
			return Matcha::$__conn->query(self::$sql)->fetchAll();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function one(){
		try{
			return Matcha::$__conn->query(self::$sql)->fetch();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function column(){
		try{
			return Matcha::$__conn->query(self::$sql)->fetchColumn();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function rowCount(){
		try{
			return Matcha::$__conn->query(self::$sql)->rowCount();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function columnCount(){
		try{
			return Matcha::$__conn->query(self::$sql)->columnCount();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function limit($start = null, $limit = 25){
		try{
			self::$sql = preg_replace("(LIMIT[ 0-9,]*)",'',self::$sql);
			if($start == null){
				self::$sql = self::$sql." LIMIT $limit";
			}else{
				self::$sql = self::$sql." LIMIT $start, $limit";
			}
			return Matcha::$__conn->query(self::$sql)->fetchAll();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
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
			$table = self::$table;
			// create a record
			if(!isset($record['id']))
			{
				$columns = array_keys($record);
				$columns = '(`'.implode('`,`',$columns).'`)';
				$values  = array_values($record);
				$values  = '(\''.implode('\',\'',$values).'\')';
				self::$rowsAffected = Matcha::$__conn->exec("INSERT INTO `$table` $columns VALUES $values");
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
				self::$rowsAffected = Matcha::$__conn->exec("UPDATE `$table` SET $values WHERE id='$id'");
			}
			return self::$rowsAffected;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
	
	/**
	 * function trash($record = array()): (part of CRUD)
	 * Delete
	 * will delete the record indicated by an id
	 */
	static public function trash($record)
	{
		try
		{
			$record = (is_object($record) ? get_object_vars($record) : $record);
			$id = $record['id'];
			$table = self::$table;
			self::$rowsAffected = Matcha::$__conn->exec("DELETE FROM $table WHERE id='$id'");
			return self::$rowsAffected;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * This method will set the model array as an object within MatchaCUP scope
	 * @param $model
	 */
	static public function setModel($model){
		self::$model = self::ArrayToObject($model);
		self::$table = self::$model->table->name;
	}

	/**
	 * convert Array to Object recursively
	 * @param array $array
	 * @param stdClass $parent
	 * @return stdClass
	 */
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

	/**
	 * This method will parse the where array and return the SQL string
	 * @param $array
	 * @return string
	 */
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