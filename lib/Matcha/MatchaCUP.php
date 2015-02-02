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
class MatchaCUP {
	/**
	 * @var array|object
	 */
	private $model;
	/**
	 * @var string
	 */
	private $table;
	/**
	 * @var string
	 */
	private $primaryKey;
	/**
	 * @var string
	 */
	private $nolimitsql = '';
	/**
	 * @var string
	 */
	public $sql = '';
	/**
	 * @var array
	 */
	private $record;
	/**
	 * @var int
	 */
	public $rowsAffected;
	/**
	 * @var int
	 */
	public $lastInsertId; // There is already a lastInsertId in Matcha::Class
	/**
	 * @var array
	 */
	public $fields = array();
	/**
	 * @var array|bool array of encrypted fields or bool false
	 */
	public $encryptedFields = false;
	/**
	 * @var array|bool
	 */
	public $phantomFields = false;
	/**
	 * @var array|bool
	 */
	public $arrayFields = false;
	/**
	 * @var bool
	 */
	private $isSenchaRequest = true;
	/**
	 * @var array
	 */
	private $bindedValues = array();
	/**
	 * @var
	 */
	private $date;

	/**
	 * set to true if want to trim values
	 * during parseValues function
	 * @var bool
	 */
	public $autoTrim = true;

	/**
	 * function sql($sql = NULL):
	 * Method to pass SQL statement without sqlBuilding process
	 * this is not the preferred way, but sometimes you need to do it.
	 *
	 * @param null $sql
	 *
	 * @return MatchaCUP
	 */
	public function sql($sql = null) {
		try {
			$this->isSenchaRequest = false;

			if($sql == null){
				throw new Exception("Error the SQL statement is not set.");
			} else {
				$this->sql = $sql;
			}
			return $this;
		} catch(Exception $e) {
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	public function exec() {
		$statement = Matcha::$__conn->prepare($this->sql);
		$result = $statement->execute();
		$statement->closeCursor();
		return $result;
	}

	/**
	 * Method to build a a SQL statement using tru MatchaCUP objects.
	 * this is the preferred way to build complex SQL statements that will
	 * use MatchaCUP objects
	 */
	public function buildSQL($sqlArray = null) {
		try {
			if($sqlArray == null || !is_array($sqlArray))
				throw new Exception("Error the argument passed are empty, null or is not an array.");
			if(empty($sqlArray['SELECT']))
				throw new Exception("Error the select statement is mandatory.");
			$SQLStatement = 'SELECT ' . $sqlArray['SELECT'] . chr(13);
			$SQLStatement .= 'FROM ' . $this->table . chr(13);
			if(!empty($sqlArray['LEFTJOIN'])){
				if(count($sqlArray['LEFTJOIN']) > 1){
					foreach($sqlArray['LEFTJOIN'] as $LJoin)
						$SQLStatement .= 'LEFT JOIN ' . $LJoin . chr(13);
				} else {
					$SQLStatement .= 'LEFT JOIN ' . $sqlArray['LEFTJOIN'] . chr(13);
				}
			}
			$SQLStatement .= (!empty($sqlArray['WHERE']) ? 'WHERE ' . $sqlArray['WHERE'] . chr(13) : '');
			$SQLStatement .= (!empty($sqlArray['HAVING']) ? 'HAVING ' . $sqlArray['HAVING'] . chr(13) : '');
			$SQLStatement .= (!empty($sqlArray['ORDER']) ? 'ORDER BY ' . $sqlArray['ORDER'] . chr(13) : '');
			$this->sql = $SQLStatement;
			return $this;
		} catch(Exception $e) {
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * Method to set PDO statement.
	 * if first argument is an object, then the method will
	 * handle the request using sencha standards. If not then
	 * here are few examples.
	 *
	 * $users->load()->all();                                    = SELECT * FROM users WHERE id = 5
	 * $users->load(5)->all();                                   = SELECT * FROM users WHERE id = 5
	 * $users->load(5, array('name','last'))->all();             = SELECT name, last FROM users WHERE id = 5
	 * $users->load(array('name'=>'joe'))->all();                = SELECT * FROM users WHERE name = joe
	 * $users->load(array('name'=>'joe'), array('id'))->all();   = SELECT id FROM users WHERE name = joe
	 * OR
	 * $users->load($params)->all()  $params = to object || array sent by sencha store
	 *
	 * @param null $where
	 * @param null $columns
	 *
	 * @return MatchaCUP
	 */
	public function load($where = null, $columns = null) {

		try {
			$this->sql = '';
			if(!is_object($where)){
				$this->isSenchaRequest = false;
				// columns
				if($columns == null){
					$columnsx = '*';
				} elseif(is_array($columns)) {
					$columnsx = '`' . implode('`,`', $columns) . '`';
				} else {
					$columnsx = $columns;
				}
				// where
				if(is_numeric($where)){
					$where = $this->ifDataEncrypt($this->primaryKey, $where);
					$wherex = "`$this->primaryKey`='$where'";
				} elseif(is_array($where)) {
					$wherex = self::parseWhereArray($where);
				} else {
					$wherex = $where;
				}
				if($where != null){
					$wherex = ' WHERE ' . $wherex;
				}
				// sql build
				$this->sql = "SELECT $columnsx FROM `" . $this->table . "` $wherex";
			} else {
				$this->isSenchaRequest = true;
				// if App.model.Example.load(4)
				$isModelLoadRequest = isset($where->{$this->primaryKey});

				// limits
				if($isModelLoadRequest){
					$_limits = ' LIMIT 1';
				} elseif(isset($where->limit) || isset($where->start)) {
					$_limits = array();
					if(isset($where->start))
						$_limits[] = $where->start;
					if(isset($where->limit))
						$_limits[] = $where->limit;
					$_limits = ' LIMIT ' . implode(',', $_limits);
				} else {
					$_limits = '';
				}

				// sort
				if(isset($where->sort)){
					$sortArray = array();
					foreach($where->sort as $sort){

						if(!isset($sort->property)) continue;
						if(is_array($this->phantomFields) && in_array($sort->property, $this->phantomFields)) continue;

						$sortDirection = (isset($sort->direction) ? $sort->direction : '');
						$sortArray[] = $sort->property . ' ' . $sortDirection;

					}
					if(!empty($sortArray)){
						$_sort = ' ORDER BY ' . implode(', ', $sortArray);
					} else {
						$_sort = '';
					}
				} else {
					$_sort = '';
				}

				// group
				if( isset($where->group) &&
					isset($where->group[0]) &&
					isset($where->group[0]->property) &&
					in_array($where->group[0]->property, $this->fields)){

					$property = $where->group[0]->property;
					$direction = isset($where->group[0]->direction) ? $where->group[0]->direction : '';
					$_group = " GROUP BY `$property` $direction";
				} else {
					$_group = '';
				}

				// filter/where
				if($isModelLoadRequest){
					$_where = ' WHERE ' . $this->primaryKey . ' = \'' . $where->{$this->primaryKey} . '\'';
				} elseif(isset($where->filter) && isset($where->filter[0]->property)) {
					$whereArray = array();
					foreach($where->filter as $foo){
						if(isset($foo->property) && (isset($foo->value) || is_null($foo->value))){
							if(is_null($foo->value)){
								$operator = (isset($foo->operator) && $foo->operator != '=') ? 'IS NOT' : 'IS';
								$whereArray[] = "`$foo->property` $operator NULL";
							} else {
								$operator = isset($foo->operator) ? $foo->operator : '=';
								$whereArray[] = "`$foo->property` $operator '$foo->value'";
							}
						}
					}
					if(count($whereArray) > 0){
						$_where = 'WHERE ' . implode(' AND ', $whereArray);
					} else {
						$_where = '';
					}
				} else {
					$_where = '';
				}

				$this->nolimitsql = "SELECT * FROM `" . $this->table . "` $_where $_group $_sort";
				$this->sql = "SELECT * FROM `" . $this->table . "` $_where $_group $_sort $_limits";
			}
			return $this;
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * function nextId()
	 * Method to get the next ID from a table
	 * @return mixed
	 */
	public function nextId() {
		try {
			$r = Matcha::$__conn->query("SELECT MAX($this->primaryKey) AS lastID FROM $this->table")->fetch();
			return $r['lastID'] + 1;
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * This is create a sequenced unique ID {string} of 38 characters
	 * @return mixed
	 */
	public function newId() {
		try {
			return strtoupper(str_replace('.', '', (uniqid(date('Uu'), true)))) . Matcha::getInstallationNumber();
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * returns multiple rows of records
	 * @return mixed
	 */
	public function all() {
		//		return $this->sql;
		try {
			$this->record = Matcha::$__conn->query($this->sql)->fetchAll();
			$this->dataDecryptWalk();
			$this->dataUnSerializeWalk();
			$this->builtRoot();
			self::callBackMethod(array(
				'crc32' => crc32($this->sql),
				'event' => 'SELECT',
				'sql' => $this->sql,
				'data' => '',
				'table' => $this->table
			));
			return $this->record;
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * returns one record
	 * @return mixed
	 */
	public function one() {
		//		return $this->sql;
		try {
			$this->record = Matcha::$__conn->query($this->sql)->fetch();
			$this->dataDecryptWalk();
			$this->dataUnSerializeWalk();
			$this->builtRoot();
			self::callBackMethod(array(
				'crc32' => crc32($this->sql),
				'event' => 'SELECT',
				'sql' => $this->sql,
				'data' => '',
				'table' => $this->table
			));
			return $this->record;
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * return an specific column
	 * @return mixed
	 */
	public function column() {
		try {
			return Matcha::$__conn->query($this->sql)->fetchColumn();
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * @return mixed
	 */
	public function rowCount() {
		try {
			return Matcha::$__conn->query($this->sql)->rowCount();
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * @return mixed
	 */
	public function columnCount() {
		try {
			return Matcha::$__conn->query($this->sql)->columnCount();
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * @param $params
	 *
	 * @return $this
	 */
	public function sort($params) {
		if(isset($params->sort)){
			$sortArray = array();
			foreach($params->sort as $sort){
				if(isset($sort->property) && (!is_array($this->phantomFields) || (is_array($this->phantomFields) && in_array($sort->property, $this->phantomFields)))){
					$sortDirection = (isset($sort->direction) ? $sort->direction : '');
					$sortArray[] = $sort->property . ' ' . $sortDirection;
				}
			}
			if(!empty($sortArray)){
				$this->sql = $this->sql . ' ORDER BY ' . implode(', ', $sortArray);
			}
		}
		return $this;
	}

	/**
	 * @param $params
	 *
	 * @return $this
	 */
	public function group($params) {
		if(isset($params->group)){
			$property = $params->group[0]->property;
			$direction = isset($params->group[0]->direction) ? $params->group[0]->direction : '';
			$this->sql = $this->sql . " GROUP BY `$property` $direction";
		}
		return $this;
	}

	/**
	 * LIMIT method
	 *
	 * @param null $start
	 * @param int  $limit
	 *
	 * @return mixed
	 */
	public function limit($start = null, $limit = 25) {
		try {
			$this->sql = preg_replace("(LIMIT[ 0-9,]*)", '', $this->sql);
			if($start == null){
				$this->sql = $this->sql . " LIMIT $limit";
			} else {
				$this->sql = $this->sql . " LIMIT $start, $limit";
			}
			$this->record = Matcha::$__conn->query($this->sql)->fetchAll();
			$this->dataDecryptWalk();
			$this->dataUnSerializeWalk();
			return $this->record;
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * function save($record): (part of CRUD) Create & Update
	 * store the record as array into the working table
	 *
	 * @param array|object $record
	 * @param array        $where
	 *
	 * @return array
	 */
	public function save($record, $where = array()) {
		try {
			if(!empty($where)){
				$this->isSenchaRequest = false;
				$data = get_object_vars($record);
				$this->sql = $this->buildUpdateSqlStatement($data, $where);
				$this->rowsAffected = Matcha::$__conn->exec($this->sql);
				self::callBackMethod(array(
					'crc32' => crc32($this->sql),
					'event' => 'UPDATE',
					'sql' => $this->sql
				));
				$this->record = $data;

				// single object handler
			} elseif(is_object($record)) {
				$this->isSenchaRequest = true;
				$this->record = $this->saveRecord($record);

				// multiple objects handler
			} else {
				$this->isSenchaRequest = true;
				// record
				$this->record = array();
				foreach($record as $index => $rec){
					$this->record[$index] = $this->saveRecord($rec);
				}
			}

			$this->builtRoot();
			return (array)$this->record;

		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	private function saveRecord($record) {
		$data = $this->parseValues(get_object_vars($record));
		$isInsert = (!isset($data[$this->primaryKey]) || (isset($data[$this->primaryKey]) && ($data[$this->primaryKey] == 0 || $data[$this->primaryKey] == '')));

		if($isInsert){
			if(isset($this->model->table->uuid) && $this->model->table->uuid){
				$data[$this->primaryKey] = $this->newId();
			} else {
				unset($data[$this->primaryKey]);
			}
			$this->sql = $this->buildInsetSqlStatement($data);
		} else {
			$this->sql = $this->buildUpdateSqlStatement($data);
		}

		$this->bindedValues = array();

		$insert = Matcha::$__conn->prepare($this->sql);
		foreach($data as $key => $value){

			$this->bindedValues[] = array(":$key" => $value);

			if(is_int($value)){
				$param = PDO::PARAM_INT;
			} elseif(is_bool($value)) {
				$param = PDO::PARAM_BOOL;
			} elseif(is_null($value)) {
				$param = PDO::PARAM_NULL;
			} elseif(is_string($value)) {
				$param = PDO::PARAM_STR;
			} else {
				$param = false;
			}

			$insert->bindValue(":$key", $value, $param);
		}

		$insert->execute();

		if($isInsert){
			if($this->isUUID()){
				if(is_array($record)){
					$record[$this->primaryKey] = $data[$this->primaryKey];
				} else {
					$record->{$this->primaryKey} = $data[$this->primaryKey];
				}
			} else {
				if(is_array($record)){
					$record[$this->primaryKey] = $this->lastInsertId = Matcha::$__conn->lastInsertId();
				} else {
					$record->{$this->primaryKey} = $this->lastInsertId = Matcha::$__conn->lastInsertId();
				}
			}

			self::callBackMethod(array(
				'crc32' => crc32($this->sql),
				'event' => 'INSERT',
				'sql' => $this->sql,
				'data' => $data,
				'table' => $this->table
			));
		} else {
			self::callBackMethod(array(
				'crc32' => crc32($this->sql),
				'event' => 'UPDATE',
				'sql' => $this->sql,
				'data' => $data,
				'table' => $this->table
			));
		}

		return $record;
	}

	/**
	 * function destroy($record): (part of CRUD) delete
	 * will delete the record indicated by an id
	 *
	 * @param      $record
	 * @param null $filter
	 *
	 * @return mixed
	 */
	public function destroy($record, $filter = null) {
		try {
			if(is_object($record)){
				$this->destroyRecord($record, $filter);
			} else {
				foreach($record as $rec){
					$this->destroyRecord($rec, $filter);
				}
			}
			return array('success' => $this->rowsAffected > 0);
		} catch(PDOException $e) {
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * @param $record
	 * @param $filter
	 */
	private function destroyRecord($record, $filter) {
		$record = (array)$record;
		$sql = "DELETE FROM `{$this->table}` ";
		$where = '';

		if(!isset($filter)){
			$where = "WHERE `{$this->primaryKey}` = '{$record[$this->primaryKey]}'";
		} elseif(isset($filter->filter) && isset($filter->filter[0]->property)) {

			$whereArray = array();
			foreach($filter->filter as $foo){
				if(isset($foo->property) && (isset($foo->value) || is_null($foo->value))){
					if(is_null($foo->value)){
						$operator = (isset($foo->operator) && $foo->operator != '=') ? 'IS NOT' : 'IS';
						$whereArray[] = "`$foo->property` $operator NULL";
					} else {
						$operator = isset($foo->operator) ? $foo->operator : '=';
						$whereArray[] = "`$foo->property` $operator '$foo->value'";
					}
				}
			}

			if(count($whereArray) > 0)
				$where = 'WHERE ' . implode(' AND ', $whereArray);
		}

		$sql .= $where;
		if(strpos($sql, 'WHERE') === false)
			return;

		$this->rowsAffected = Matcha::$__conn->exec($sql);
		if($this->rowsAffected > 0){
			self::callBackMethod(array(
				'crc32' => crc32($sql),
				'event' => 'DELETE',
				'sql' => $sql,
				'table' => $this->table
			));
		}
	}

	/**
	 * @param $params
	 *
	 * @return MatchaCUP
	 */
	public function search($params) {

		$sql = "SELECT * FROM `$this->table` ";

		$filter = '';

		if(isset($params->filter) && isset($params->filter[0]->property)){
			$whereArray = array();
			foreach($params->filter as $foo){
				if(isset($params->query) && isset($foo->property)){
					$operator = isset($foo->operator) ? $foo->operator : ' LIKE ';
					$whereArray[] = "`$foo->property` $operator '$params->query%'";
				}
			}
			if(count($whereArray) > 0)
				$filter = 'WHERE ' . implode(' OR ', $whereArray);
		}
		$this->nolimitsql = $sql . $filter;
		$limits = '';
		if(isset($params->limit) || isset($params->start)){
			$limits = array();
			if(isset($params->start))
				$limits[] = $params->start;
			if(isset($params->limit))
				$limits[] = $params->limit;
			$limits = 'LIMIT ' . implode(',', $limits);
		}

		$this->sql = $this->nolimitsql . $limits;
		return $this;
	}

	/**
	 * function callBackMethod($dataInjectArray = array()):
	 * Method to do the callback function, and also inject the event data
	 * it depends on MatchaAudit, if MatchaAudit is not configured this will not
	 * execute.
	 *
	 * @param array $dataInjectArray
	 */
	public function callBackMethod($dataInjectArray = array()) {
		if(method_exists(MatchaAudit::$hookClass, MatchaAudit::$hookMethod) && MatchaAudit::$__audit)
			call_user_func_array(array(
				MatchaAudit::$hookClass,
				MatchaAudit::$hookMethod
			), array($dataInjectArray));
	}

	/**
	 * function setModel($model):
	 * This method will set the model array as an object within MatchaCUP scope
	 *
	 * @param $model
	 *
	 * @return bool|\MatchaCUP
	 */
	public function setModel($model) {
		$this->model = (is_array($model) ? MatchaUtils::__arrayToObject($model) : $model);

		if(isset($this->model->table)){
			if(is_string($this->model->table)){
				$this->table = $this->model->table;
			} else {
				$this->table = $this->model->table->name;
			}
		} else {
			$this->table = false;
		}
		$this->date = new DateTime();
		$this->primaryKey = MatchaModel::__getTablePrimaryKeyColumnName($this->table);
		$this->fields = MatchaModel::__getFields($this->model);
		$this->encryptedFields = MatchaModel::__getEncryptedFields($this->model);
		$this->phantomFields = MatchaModel::__getPhantomFields($this->model);
		$this->arrayFields = MatchaModel::__getArrayFields($this->model);
	}

	/**
	 * function parseWhereArray($array):
	 * This method will parse the where array and return the SQL string
	 *
	 * @param $array
	 *
	 * @return string
	 */
	private function parseWhereArray($array) {
		$whereStr = '';
		$prevArray = false;
		foreach($array as $key => $val){
			if(is_string($key)){
				if($prevArray)
					$whereStr .= 'AND ';
				$val = $this->ifDataEncrypt($key, $val);
				$whereStr .= "`$key`='$val' ";
				$prevArray = true;
			} elseif(is_array($val)) {
				if($prevArray)
					$whereStr .= 'AND ';
				$whereStr .= '(' . self::parseWhereArray($val) . ')';
				$prevArray = true;
			} else {
				$whereStr .= $val . ' ';
				$prevArray = false;
			}
		}
		return $whereStr;
	}

	/**
	 * function buildInsetSqlStatement($data):
	 * Method to build the insert sql statement
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	private function buildInsetSqlStatement($data) {
		$fields = array_keys($data);
		$placeholders = array();
		// array(':field1', ':field2', ':field3')
		foreach($fields as $field){
			$placeholders[] = ':' . $field;
		}
		$query = 'INSERT INTO `' . $this->table . '` ';
		$query .= '(`' . implode('`, `', $fields) . '`)';
		$query .= ' VALUES (' . implode(', ', $placeholders) . ')';
		return $query;
	}

	/**
	 * function buildUpdateSqlStatement($data):
	 * Method to build the update sql statement
	 *
	 * @param       $data
	 * @param array $where
	 *
	 * @return mixed
	 */
	private function buildUpdateSqlStatement($data, $where = array()) {

		if(!empty($where)){
			$primaryKey = current(array_keys($where));
		} else {
			$primaryKey = $this->primaryKey;
		}

		unset($data[$this->primaryKey]);
		$fields = array_keys($data);
		$placeholders = array();

		// array('`field1` = :field1', '`field2` = :field2', '`field3` = :field3')
		foreach($fields as $field){
			$placeholders[] = "`$field` = :$field";
		}
		$query = "UPDATE `{$this->table}` SET ";
		$query .= implode(', ', $placeholders) . ' ';
		$query .= "WHERE `$primaryKey` = :$primaryKey";

		return $query;
	}

	/**
	 * function parseValues($data):
	 * Parse the data and if some values met the type correct them.
	 *
	 * @param $data
	 *
	 * @return array
	 */
	private function parseValues($data) {
		$record = array();
		$columns = array_keys($data);
		$values = array_values($data);

		foreach($columns as $index => $column){
			if(!in_array($column, $this->fields)){
				unset($columns[$index], $values[$index]);
			}
		}

		foreach($columns as $col){
			$properties = (array)MatchaModel::__getFieldProperties($col, $this->model);
			/**
			 * Don't parse the value (skip it) if...
			 * $properties['store'] is set and is not true OR
			 * $properties['persist'] is set and is not true OR
			 */
			if((!isset($properties['store']) || $properties['store']) && (!isset($properties['persist']) || $properties['persist'])){
				$type = MatchaModel::__getFieldType($col, $this->model);
				if($this->encryptedFields !== false && in_array($col, $this->encryptedFields)){
					$data[$col] = $this->dataEncrypt($data[$col]);
				} else {
					if($type == 'date'){
						$data[$col] = ($data[$col] == '' ? null : $data[$col]);
					} elseif($type == 'array') {
						$data[$col] = ($data[$col] == '' ? null : serialize($data[$col]));
					}
				}
				/**
				 * do not trim bool values
				 */
				if($this->autoTrim && $type != 'bool'){
					$record[$col] = trim($data[$col]);
				} else {
					$record[$col] = $data[$col];
				}
			}
		}

		return $record;
	}

	/**
	 * @param $value
	 *
	 * @return string
	 */
	private function dataEncrypt($value) {
		return MatchaUtils::__encrypt($value);
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return string
	 */
	private function ifDataEncrypt($key, $value) {
		if(is_array($this->encryptedFields) && in_array($key, $this->encryptedFields)){
			$value = MatchaUtils::__encrypt($value);
		}
		return $value;
	}

	/**
	 * @param $item
	 * @param $key
	 * @param $encryptedFields
	 */
	private function dataDecrypt(&$item, $key, $encryptedFields) {
		if(in_array($key, $encryptedFields)){
			$item = MatchaUtils::__decrypt($item);
		}
	}

	/**
	 *
	 */
	private function dataDecryptWalk() {
		if(is_array($this->record) && is_array($this->encryptedFields)){
			array_walk_recursive($this->record, 'self::dataDecrypt', $this->encryptedFields);
		}
	}

	private function dataUnSerialize(&$item, $key, $arrayFields) {
		if(in_array($key, $arrayFields)){
			$item = unserialize($item);
		}
	}

	private function dataUnSerializeWalk() {
		if(is_array($this->record) && is_array($this->arrayFields)){
			array_walk_recursive($this->record, 'self::dataUnSerialize', $this->arrayFields);
		}
	}

	/**
	 *
	 */
	private function builtRoot() {
		if($this->isSenchaRequest && isset($this->model->proxy) && isset($this->model->proxy->reader) && isset($this->model->proxy->reader->root)
		){
			$record = array();
			$total = ($this->nolimitsql != '' ? Matcha::$__conn->query($this->nolimitsql)->rowCount() : false);

			if($total !== false){
				if(isset($this->model->proxy->reader->totalProperty)){
					$record[$this->model->proxy->reader->totalProperty] = $total;
				} else {
					$record['total'] = $total;
				}
			}

			$record[$this->model->proxy->reader->root] = $this->record;
			$this->record = $record;
		}

	}

	private function isUUID() {
		return (isset($this->model->table->uuid) && $this->model->table->uuid);
	}
}
