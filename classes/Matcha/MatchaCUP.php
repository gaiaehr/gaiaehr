<?php
/**
 * Matcha::connect microORM v0.0.1
 * This class will help Sencha ExtJS and PHP developers deliver fast and powerful application fast and easy to develop.
 * If Sencha ExtJS is a GUI Framework of the future, think Matcha micrORM as the bridge between the Client-Server
 * GAP. 
 * 
 * Matcha will read and parse a Sencha Model .js file and then connect to the database and produce a compatible database-table
 * from your model. Also will provide the basic functions for the CRUD. If you are familiar with Sencha ExtJS, and know 
 * about Sencha Models, you will need this PHP Class. You can use it in any way you want, in MVC like pattern, your own pattern, 
 * or just playing simple. It's compatible with all your coding stile. 
 * 
 * Taking some ideas from diferent microORM's and full featured ORM's we bring you this super Class. 
 * 
 * History:
 * Born in the fields of GaiaEHR we needed a way to develop the application more faster, Gino Rivera suggested the use of an
 * microORM for fast development, and the development began. We tried to use some already developed and well known ORM on the 
 * space of PHP, but none satisfied our purposes. So Gino Rivera sugested the development of our own microORM (a long way to run).
 * 
 * But despite the long run, it returned to be more logical to get ideas from the well known ORM's and how Sensha manage their models
 * so this is the result. 
 *  
 */
 
class MatchaCUP
{
	/**
	 * @var db connection
	 */
	public static $conn;

	/**
	 * @var array Model array
	 */
	public static $model;

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

			if($wherex != '') $wherex = 'WHERE '.$wherex;

			// table
			$table = self::$model->table->name;
			// sql build
			$sql = "SELECT $columnsx FROM `$table` $wherex";
			print $sql;

		}
		catch(PDOException $e)
		{
//			return Matcha::__errorProcess($e);
		}
	}


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
//				$storeField = (string)'';
//				foreach($record as $key => $value) ($key=='id' ? $storeField .= '' : $storeField .= $key."='".$value."'");
//				$sql = (string)'UPDATE '.Matcha::$__senchaModel['table'].' SET '.$storeField . " WHERE id='".$record['id']."';";
//				Matcha::$__conn->query($sql);
//				MatchaAudit::__auditLog($sql);
//				Matcha::$__id = $record['id'];
			}
			// create a record
			else
			{
//				$fields = (string)implode(', ', array_keys($record));
//				$values = (string)implode(', ', array_values($record));
//				$sql = (string)'INSERT INTO '.Matcha::$__senchaModel['table'].' ('.$fields.') VALUES ('.$values.');';
//				Matcha::$__conn->query($sql);
//				MatchaAudit::__auditLog($sql);
//				Matcha::$__id = $__conn->lastInsertId();
			}
			return true;
		}
		catch(PDOException $e)
		{
//			return Matcha::__errorProcess($e);
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
print '<pre>';
$t = new MatchaCUP();
$t->setModel(Array(
	'extend' => 'Ext.data.Model',
	'table' => Array(
		'name' => 'accvoucher',
		'engine' => 'InnoDB',
		'autoIncrement' => 1,
		'charset' => 'utf8',
		'collate' => 'utf8_bin',
		'comment' => 'Voucher / Receipt'
	),
	'fields' => Array(
		Array(
			'name' => 'id',
			'type' => 'int'
		),
		Array(
			'name' => 'voucherId',
			'type' => 'int',
			'comment' => 'Voucher'
		),
		Array(
			'name' => 'accountId',
			'type' => 'int',
			'comment' => 'Account'
		)
	),
	'associations' => Array(
		Array(
			'type' => 'belongsTo',
			'model' => 'App.model.account.Voucher',
			'foreignKey' => 'voucherId',
			'setterName' => 'setVoucher',
			'getterName' => 'getVoucher'
		)
	)
));
$t->setConn($t->db->conn);

$t::load();    						                // fetch all
print '<br>';
print '<br>';
$t::load(5);    						            // fetch all columns where id = 5
print '<br>';
print '<br>';
$t::load(5,array('id','name'));    			        // fetch id and name where id = 5
print '<br>';
print '<br>';
$t::load(array('voucherId'=>3));    			    // fetch all columns where voucherId = 5
print '<br>';
print '<br>';
$t::load(array('voucherId'=>3),array('id','name'));	// fetch id and name where voucherId = 5
print '<br>';
print '<br>';
$t::load('col = 4, sdsdi=5',array('id','name'));	// fetch id and name where voucherId = 5
print '<br>';
print '<br>';
$t::load(array('voucherId'=>3, 'OR', 'userId'=>7),array('id','name'));	// fetch id and name where voucherId = 5


//
//print '<br>';
//print_r($t::$model->table->name);


//	SELECT `id`,`name` FROM `accvoucher` WHERE `voucherId`='3' AND `userId`='7' OR (`hello`='4' AND `hello2`='5' )