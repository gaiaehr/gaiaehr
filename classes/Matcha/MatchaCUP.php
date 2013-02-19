<?php
 /**
  * Matcha::connect (MatchaCUP Class)
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
//include_once('Matcha.php');

class MatchaCUP
{
	/**
	 * @var array Model array
	 */
	private $model;
	private $table;
	private $nolimitsql = '';
	private $sql = '';

	public $rowsAffected;
	public $lastInsertId;

	/**
	 * function load($id = NULL, $columns = array()) (part of CRUD)
	 * Read from table
	 * Load all records, load one record if a ID is passed,
	 * load all records with some columns determined by an array,
	 * load one record with some columns determined by an array, or any combination.
	 */
	public function load($where = null, $columns = null)
	{
		try
		{
			$this->sql = '';
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
				$this->sql = "SELECT $columnsx FROM `".$this->model->table."` $wherex";
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
				$this->nolimitsql = "SELECT * FROM `".$this->model->table."` $groupx $wherex $sortx";
				$this->sql = "SELECT * FROM `".$this->model->table."` $groupx $wherex $sortx $limits";
			}
			return $this;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function all(){
		try{
			return Matcha::$__conn->query($this->sql)->fetchAll();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function one(){
		try{
			return Matcha::$__conn->query($this->sql)->fetch();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function column(){
		try{
			return Matcha::$__conn->query($this->sql)->fetchColumn();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function rowCount(){
		try{
			return Matcha::$__conn->query($this->sql)->rowCount();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function columnCount(){
		try{
			return Matcha::$__conn->query($this->sql)->columnCount();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	public function limit($start = null, $limit = 25){
		try{
			$this->sql = preg_replace("(LIMIT[ 0-9,]*)",'',$this->sql);
			if($start == null){
				$this->sql = $this->sql." LIMIT $limit";
			}else{
				$this->sql = $this->sql." LIMIT $start, $limit";
			}
			return Matcha::$__conn->query($this->sql)->fetchAll();
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
	public function store($record)
	{
		try
		{
			$data = (is_object($record) ? get_object_vars($record) : $record);
			// create record
			if(!isset($data['id']) || (isset($data['id']) && $data['id'] == 0))
			{
				$columns = array_keys($data);
				$columns = '(`'.implode('`,`',$columns).'`)';
				$values  = array_values($data);
				$values  = '(\''.implode('\',\'',$values).'\')';
				$this->rowsAffected = Matcha::$__conn->exec("INSERT INTO `".$this->model->table."` $columns VALUES $values");
				$this->lastInsertId = Matcha::$__conn->lastInsertId();
				$record['id'] = $this->lastInsertId;
			}
			// update a record
			else
			{
				$values = array();
				$id = $data['id'];
				unset($data['id']);
				foreach($data as $key => $val) $values[] = "`$key`='$val'";
				$values = implode(',',$values);
				$this->rowsAffected = Matcha::$__conn->exec("UPDATE `".$this->model->table."` SET $values WHERE id='$id'");
			}
			try
			{
				if($this->rowsAffected > 0)
				{
					return $record;
				}
				else
				{
					throw new Exception('No Record stored or modified');
				}
			}
			catch(ErrorException $e)
			{
				return MatchaErrorHandler::__errorProcess($e);
			}
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
	public function trash($record)
	{
		try
		{
			$record = (is_object($record) ? get_object_vars($record) : $record);
			$id = $record['id'];
			$table = $this->table;
			$this->rowsAffected = Matcha::$__conn->exec("DELETE FROM ".$this->model->table." WHERE id='$id'");
			return $this->rowsAffected;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * This method will set the model array as an object within MatchaCUP scope
	 * @param $model
	 * @return bool|\MatchaCUP
	 */
	public function setModel($model){
		$this->model = $this->ArrayToObject($model);
		$this->table = $this->model->table;
//		$this->table = $this->model->table->name;
	}

	/**
	 * convert Array to Object recursively
	 * @param array $array
	 * @param stdClass $parent
	 * @return stdClass
	 */
	private function ArrayToObject(array $array, stdClass $parent = null) {
		if ($parent === null) {
			$parent = new stdClass;
		}
		foreach ($array as $key => $val) {
			if (is_array($val)) {
				$parent->$key = $this->ArrayToObject($val, new stdClass);
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
	private function parseWhereArray($array){
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