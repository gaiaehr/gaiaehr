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

class MatchaCUP
{
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
     * function sql($sql = NULL):
     * Method to pass SQL statement without sqlBuilding process
     * this is not the preferred way, but sometimes you need to do it.
     *
     * @param null $sql
     * @return MatchaCUP
     */
    public function sql($sql = NULL)
    {
        try
        {
            $this->isSenchaRequest = false;

            if($sql == NULL){
                throw new Exception("Error the SQL statement is not set.");
            }else{
                $this->sql = $sql;
            }
            return $this;
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

	public function exec(){
		$statement = Matcha::$__conn->prepare($this->sql);
		$result  = $statement->execute();
		$statement->closeCursor();
		return $result;
	}

    /**
     * Method to build a a SQL statement using tru MatchaCUP objects.
     * this is the preferred way to build complex SQL statements that will
     * use MatchaCUP objects
     */
    public function buildSQL($sqlArray = NULL)
    {
        try
        {
            if($sqlArray == NULL || !is_array($sqlArray)) throw new Exception("Error the argument passed are empty, null or is not an array.");
            if(empty($sqlArray['SELECT'])) throw new Exception("Error the select statement is mandatory.");
            $SQLStatement = 'SELECT '.$sqlArray['SELECT'].chr(13);
            $SQLStatement .= 'FROM '.$this->table.chr(13);
            if(!empty($sqlArray['LEFTJOIN']))
            {
                if(count($sqlArray['LEFTJOIN']) > 1)
                {
                    foreach($sqlArray['LEFTJOIN'] as $LJoin) $SQLStatement .= 'LEFT JOIN '.$LJoin.chr(13);
                }
                else
                {
                    $SQLStatement .= 'LEFT JOIN '.$sqlArray['LEFTJOIN'].chr(13);
                }
            }
            $SQLStatement .= (!empty($sqlArray['WHERE']) ? 'WHERE '.$sqlArray['WHERE'].chr(13) : '');
            $SQLStatement .= (!empty($sqlArray['HAVING']) ? 'HAVING '.$sqlArray['HAVING'].chr(13) : '');
            $SQLStatement .= (!empty($sqlArray['ORDER']) ? 'ORDER BY '.$sqlArray['ORDER'].chr(13) : '');
            $this->sql = $SQLStatement;
            return $this;
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return $this;
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
	 * @return MatchaCUP
	 */
	public function load($where = NULL, $columns = NULL)
	{

		try
		{
			$this->sql = '';
			if (!is_object($where))
			{
				$this->isSenchaRequest = false;
				// columns
				if ($columns == null)
				{
					$columnsx = '*';
				}
				elseif (is_array($columns))
				{
					$columnsx = '`' . implode('`,`', $columns) . '`';
				}
				else
				{
					$columnsx = $columns;
				}
				// where
				if (is_numeric($where))
				{
					$where = $this->ifDataEncrypt($this->primaryKey,$where);
					$wherex = "`$this->primaryKey`='$where'";
				}
				elseif (is_array($where))
				{
					$wherex = self::parseWhereArray($where);
				}
				else
				{
					$wherex = $where;
				}
				if ($where != null)
					$wherex = ' WHERE ' . $wherex;
				// sql build
				$this->sql = "SELECT $columnsx FROM `" . $this->table . "` $wherex";
			}
			else
			{
				$this->isSenchaRequest = true;
				// limits
				$limits = '';
				if (isset($where->limit) || isset($where->start))
				{
					$limits = array();
					if (isset($where->start)) $limits[] = $where->start;
					if (isset($where->limit)) $limits[] = $where->limit;
					$limits = ' LIMIT ' . implode(',', $limits);
				}

				// sort
				$sortx = '';
				if (isset($where->sort))
				{
					$sortArray = array();
					foreach ($where->sort as $sort)
						{
						if(isset($sort->property) && (!is_array($this->phantomFields) || (is_array($this->phantomFields) && in_array($sort->property, $this->phantomFields)))){
							$sortDirection = (isset($sort->direction) ? $sort->direction : '');
							$sortArray[] = $sort->property.' '.$sortDirection;
						}
					}
					if(!empty($sortArray)){
						$sortx = ' ORDER BY ' . implode(', ', $sortArray);
					}
				}
				// group
				$groupx = '';
				if (isset($where->group))
				{
					$property = $where->group[0]->property;
					$direction = isset($where->group[0]->direction) ? $where->group[0]->direction : '';
					$groupx = " GROUP BY `$property` $direction";
				}
				// filter/where
				$wherex = '';

                if(isset($where->{$this->primaryKey}))
                {
                    $wherex = ' WHERE '.$this->primaryKey.' = \''.$where->{$this->primaryKey}.'\'';
                }
                elseif(isset($where->filter) && isset($where->filter[0]->property))
				{
					$whereArray = array();
					foreach ($where->filter as $foo)
					{
						if(isset($foo->property)){
							if($foo->value == null){
								if(isset($foo->operator)){
									$operator = $foo->operator == '=' ? ' IS ' : ' IS NOT';
								}else{
									$operator = 'IS';
								}
								$whereArray[] = "`$foo->property` $operator NULL";
							}else{
								$operator = isset($foo->operator)? $foo->operator : '=';
								$whereArray[] = "`$foo->property` $operator '$foo->value'";
							}
						}
					}
					if(count($whereArray) > 0) $wherex = 'WHERE ' . implode(' AND ', $whereArray);
				}
				$this->nolimitsql   = "SELECT * FROM `" . $this->table . "` $wherex $groupx $sortx";
				$this->sql          = "SELECT * FROM `" . $this->table . "` $wherex $groupx $sortx $limits";
			}

			return $this;


		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

    /**
     * function nextId()
     * Method to get the next ID from a table
     * @return mixed
     */
    public function nextId()
    {
        try
        {
            $r = Matcha::$__conn->query("SELECT MAX($this->primaryKey) AS lastID FROM $this->table")->fetch();
            return $r['lastID']+1;
        }
        catch(PDOException $e)
        {
            return MatchaErrorHandler::__errorProcess($e);
        }
    }

	/**
	 * returns multiple rows of records
	 * @return mixed
	 */
	public function all()
	{
//		return $this->sql;
		try
		{
			$this->record = Matcha::$__conn->query($this->sql)->fetchAll();
			$this->dataDecryptWalk();
			$this->dataUnSerializeWalk();
			$this->builtRoot();
			return $this->record;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * returns one record
	 * @return mixed
	 */
	public function one()
	{
//		return $this->sql;
		try
		{
			$this->record = Matcha::$__conn->query($this->sql)->fetch();
			$this->dataDecryptWalk();
			$this->dataUnSerializeWalk();
			$this->builtRoot();
			return $this->record;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * return an specific column
	 * @return mixed
	 */
	public function column()
	{
		try
		{
			return Matcha::$__conn->query($this->sql)->fetchColumn();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * @return mixed
	 */
	public function rowCount()
	{
		try
		{
			return Matcha::$__conn->query($this->sql)->rowCount();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * @return mixed
	 */
	public function columnCount()
	{
		try
		{
			return Matcha::$__conn->query($this->sql)->columnCount();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}


	public function sort($params){
		if (isset($params->sort))
		{
			$sortArray = array();
			foreach ($params->sort as $sort)
			{
				if(isset($sort->property) && (!is_array($this->phantomFields) || (is_array($this->phantomFields) && in_array($sort->property, $this->phantomFields)))){
					$sortDirection = (isset($sort->direction) ? $sort->direction : '');
					$sortArray[] = $sort->property.' '.$sortDirection;
				}
			}
			if(!empty($sortArray)){
				 $this->sql = $this->sql . ' ORDER BY ' . implode(', ', $sortArray);
			}
		}
		return $this;
	}

	public function group($params){
		if (isset($params->group))
		{
			$property = $params->group[0]->property;
			$direction = isset($params->group[0]->direction) ? $params->group[0]->direction : '';
			$this->sql = $this->sql . " GROUP BY `$property` $direction";
		}
		return $this;
	}

	/**
	 * LIMIT method
	 * @param null $start
	 * @param int $limit
	 * @return mixed
	 */
	public function limit($start = NULL, $limit = 25)
	{
		try
		{
			$this->sql = preg_replace("(LIMIT[ 0-9,]*)", '', $this->sql);
			if ($start == null)
			{
				$this->sql = $this->sql . " LIMIT $limit";
			}
			else
			{
				$this->sql = $this->sql . " LIMIT $start, $limit";
			}
			$this->record =  Matcha::$__conn->query($this->sql)->fetchAll();
			$this->dataDecryptWalk();
			$this->dataUnSerializeWalk();
			return $this->record;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * function save($record): (part of CRUD) Create & Update
	 * store the record as array into the working table
	 * @param array|object $record
	 * @param array $where
	 * @return array
	 */
	public function save($record, $where = array())
	{
		try
		{
			if(!empty($where))
			{
				$this->isSenchaRequest = false;
				$data = get_object_vars($record);
                $sql = $this->buildUpdateSqlStatement($data, $where);
				$this->rowsAffected = Matcha::$__conn->exec($sql);
                self::callBackMethod(array(array('crc32'=>crc32($sql), 'event'=>'UPDATE', 'sql'=>addslashes($sql))));
				$this->record = $data;
			}
			// single record object
			elseif(is_object($record))
			{
				$this->isSenchaRequest = true;
				$data = get_object_vars($record);
				// create record
				if (!isset($data[$this->primaryKey]) || (isset($data[$this->primaryKey]) && ($data[$this->primaryKey] == 0 || $data[$this->primaryKey] == ''))){
                    $sql = $this->buildInsetSqlStatement($data);
					$this->rowsAffected = Matcha::$__conn->exec($sql);
					$data[$this->primaryKey] = $this->lastInsertId = Matcha::$__conn->lastInsertId();
                    self::callBackMethod(array(array('insertId'=>$this->lastInsertId, 'crc32'=>crc32($sql), 'event'=>'INSERT', 'sql'=>addslashes($sql))));
					$this->record = $data;
				}
				else
				{
					// update a record
                    $sql = $this->buildUpdateSqlStatement($data);
					$this->rowsAffected = Matcha::$__conn->exec($sql);
                    self::callBackMethod(array(array('crc32'=>crc32($sql), 'event'=>'UPDATE', 'sql'=>addslashes($sql))));
					$this->record = $data;
				}
			}
			// array of records objects
			else
			{
				$this->isSenchaRequest = true;
				$records = array();
				foreach ($record as $rec)
				{
					$data = get_object_vars($rec);
					// create record
					if (!isset($data[$this->primaryKey]) || (isset($data[$this->primaryKey]) && ($data[$this->primaryKey] == 0 || $data[$this->primaryKey] == '')))
					{
                        $sql = $this->buildInsetSqlStatement($data);
						$this->rowsAffected = Matcha::$__conn->exec($sql);
						$data[$this->primaryKey] = $this->lastInsertId = Matcha::$__conn->lastInsertId();
                        self::callBackMethod(array(array('insertId'=>$this->lastInsertId, 'crc32'=>crc32($sql), 'event'=>'INSERT', 'sql'=>addslashes($sql))));
        			}
					else
					{
						// update a record
                        $sql = $this->buildUpdateSqlStatement($data);
						$this->rowsAffected = Matcha::$__conn->exec($sql);
                        self::callBackMethod(array(array('crc32'=>crc32($sql), 'event'=>'UPDATE', 'sql'=>addslashes($sql))));
					}
					$records[] = $data;
				}

				$this->record = $records;
			}

			$this->builtRoot();
			return $this->record;

		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * function destroy($record): (part of CRUD) delete
	 * will delete the record indicated by an id
	 * @param $record
	 * @return mixed
	 */
	public function destroy($record)
	{
		try
		{
			if (is_object($record))
			{
				$record = get_object_vars($record);
                $sql = "DELETE FROM " . $this->table . " WHERE $this->primaryKey = '".$record[$this->primaryKey]."'";
				$this->rowsAffected = Matcha::$__conn->exec($sql);
                self::callBackMethod( array(array('crc32'=>crc32($sql), 'event'=>'DELETE', 'sql'=>addslashes($sql))));
			}
			else
			{
				foreach ($record as $rec)
				{
					$rec = get_object_vars($rec);
                    $sql = "DELETE FROM " . $this->table . " WHERE $this->primaryKey ='".$rec[$this->primaryKey]."'";
					$this->rowsAffected = Matcha::$__conn->exec($sql);
                    self::callBackMethod( array(array('crc32'=>crc32($sql), 'event'=>'DELETE', 'sql'=>addslashes($sql))));
				}
			}
			return $this->rowsAffected;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

	/**
	 * @param $params
	 * @return MatchaCUP
	 */
	public function search($params){

		$sql = "SELECT * FROM `$this->table` ";

		$filter = '';

		if (isset($params->filter) && isset($params->filter[0]->property))
		{
			$whereArray = array();
			foreach ($params->filter as $foo)
			{
				if(isset($params->query) && isset($foo->property)){
					$operator = isset($foo->operator)? $foo->operator : ' LIKE ';
					$whereArray[] = "`$foo->property` $operator '$params->query%'";
				}
			}
			if(count($whereArray) > 0) $filter = 'WHERE ' . implode(' OR ', $whereArray);
		}
		$this->nolimitsql = $sql . $filter;
		$limits = '';
		if (isset($params->limit) || isset($params->start))
		{
			$limits = array();
			if (isset($params->start)) $limits[] = $params->start;
			if (isset($params->limit)) $limits[] = $params->limit;
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
     * @param array $dataInjectArray
     */
    public function callBackMethod($dataInjectArray = array())
    {
        if(method_exists(MatchaAudit::$hookClass, MatchaAudit::$hookMethod) && MatchaAudit::$__audit)
            call_user_func_array(array(MatchaAudit::$hookClass, MatchaAudit::$hookMethod), $dataInjectArray);
    }

	/**
     * function setModel($model):
	 * This method will set the model array as an object within MatchaCUP scope
	 * @param $model
	 * @return bool|\MatchaCUP
	 */
	public function setModel($model)
	{
		$this->model = (is_array($model) ? MatchaUtils::__arrayToObject($model) : $model);

        if(isset($this->model->table)){
            if(is_string($this->model->table)){
                $this->table = $this->model->table;
            }else{
                $this->table = $this->model->table->name;
            }
        }else{
            $this->table = false;
        }

		$this->primaryKey = MatchaModel::__getTablePrimaryKeyColumnName($this->table);
		$this->fields = MatchaModel::__getFields($this->model);
		$this->encryptedFields = MatchaModel::__getEncryptedFields($this->model);
		$this->phantomFields = MatchaModel::__getPhantomFields($this->model);
		$this->arrayFields = MatchaModel::__getArrayFields($this->model);
	}

	/**
     * function parseWhereArray($array):
	 * This method will parse the where array and return the SQL string
	 * @param $array
	 * @return string
	 */
	private function parseWhereArray($array)
	{
		$whereStr = '';
		$prevArray = false;
		foreach ($array as $key => $val)
		{
			if (is_string($key))
			{
				if ($prevArray) $whereStr .= 'AND ';
				$val = $this->ifDataEncrypt($key,$val);
				$whereStr .= "`$key`='$val' ";
				$prevArray = true;
			}
			elseif (is_array($val))
			{
				if ($prevArray)
					$whereStr .= 'AND ';
				$whereStr .= '(' . self::parseWhereArray($val) . ')';
				$prevArray = true;
			}
			else
			{
				$whereStr .= $val . ' ';
				$prevArray = false;
			}
		}
		return $whereStr;
	}

	/**
	 * function buildInsetSqlStatement($data):
	 * Method to build the insert sql statement
	 * @param $data
	 * @return mixed
	 */
	private function buildInsetSqlStatement($data)
	{
		$data = $this->parseValues($data);
		unset($data[$this->primaryKey]);
		$columns = array_keys($data);
		$values = array_values($data);
		$columns = '(`' . implode('`,`', $columns) . '`)';
		$values = '(\'' . implode('\',\'', $values) . '\')';
		$sql = "INSERT INTO `" . $this->table . "` $columns VALUES $values";
		return str_replace("'NULL'",'NULL',$sql);
	}

	/**
	 * function buildUpdateSqlStatement($data):
	 * Method to build the update sql statement
	 * @param $data
	 * @param array $where
	 * @return mixed
	 */
	private function buildUpdateSqlStatement($data, $where = array())
	{
		if(!empty($where)){
			$primaryKey      = current(array_keys($where));
			$primaryKeyValue = current(array_values($where));
		}else{
			$primaryKey      = $this->primaryKey;
			$primaryKeyValue = $data[$this->primaryKey];;
		}
		unset($data[$this->primaryKey]);
		$sets = array();
		$data = $this->parseValues($data);
		foreach ($data as $key => $val) $sets[] = "`$key`='$val'";
		$sets = implode(',', $sets);
		$sql = "UPDATE `" . $this->table . "` SET $sets WHERE $primaryKey = '$primaryKeyValue'";
		return str_replace("'NULL'",'NULL',$sql);
	}

	/**
	 * function parseValues($data):
	 * Parse the data and if some values met the type correct them.
	 * @param $data
	 * @return array
	 */
	private function parseValues($data)
	{
		$columns = array_keys($data);
		$values = array_values($data);

		foreach($columns as $index=>$column) if(!in_array($column,$this->fields)) unset($columns[$index],$values[$index]);

		$properties = (array) MatchaModel::__getFieldsProperties($columns, $this->model);
		foreach($values as $index => $foo)
		{
			if(!isset($properties[$index]['store']) || (isset($properties[$index]['store']) && $properties[$index]['store'] != false)){
				$type = $properties[$index]['type'];

				if(isset($properties[$index]['encrypt']) && $properties[$index]['encrypt']){
					$values[$index] = $this->dataEncrypt($values[$index]);
				}else{
					if($type == 'bool')
					{
						if($foo === true)
						{
							$values[$index] = 1;
						}
						elseif($foo === false)
						{
							$values[$index] = 0;
						}
//					}
//					elseif($type == 'int')
//					{
//						$values[$index] = ($foo == '' || $foo == false ? 'NULL' : $values[$index]);
					}elseif($type == 'date')
					{
						$values[$index] = ($foo == '' ? 'NULL' : $values[$index]);
					}
					elseif($type == 'array')
					{
						$values[$index] = ($foo == '' ? 'NULL' : serialize($values[$index]));
					}else{
						addslashes($values[$index]);
					}
				}
			}else{
				unset($columns[$index], $values[$index]);
			}
		}
		return array_combine($columns,$values);
	}

	/**
	 * @param $value
	 * @return string
	 */
	private function dataEncrypt($value){
		return MatchaUtils::__encrypt($value);
	}

	/**
	 * @param $key
	 * @param $value
	 * @return string
	 */
	private function ifDataEncrypt($key, $value){
		if(is_array($this->encryptedFields) && in_array($key,$this->encryptedFields)){
			$value = MatchaUtils::__encrypt($value);
		}
		return $value;
	}

	/**
	 * @param $item
	 * @param $key
	 * @param $encryptedFields
	 */
	private function dataDecrypt(&$item, $key, $encryptedFields){
		if(in_array($key, $encryptedFields)){
			$item = MatchaUtils::__decrypt($item);
		}
	}

	/**
	 *
	 */
	private function dataDecryptWalk(){
		if(is_array($this->record) && is_array($this->encryptedFields)){
			array_walk_recursive($this->record, 'self::dataDecrypt', $this->encryptedFields);
		}
	}


	private function dataUnSerialize(&$item, $key, $arrayFields){
		if(in_array($key, $arrayFields)){
			$item = unserialize($item);
		}
	}

	private function dataUnSerializeWalk(){
		if(is_array($this->record) && is_array($this->arrayFields)){
			array_walk_recursive($this->record, 'self::dataUnSerialize', $this->arrayFields);
		}
	}

	/**
	 * 
	 */
	private function builtRoot(){
		if(
			$this->isSenchaRequest &&
			isset($this->model->proxy) &&
			isset($this->model->proxy->reader) &&
			isset($this->model->proxy->reader->root)
		){
			$record = array();
			$total = ($this->nolimitsql != '' ? Matcha::$__conn->query($this->nolimitsql)->rowCount() : false);

			if($total !== false){
				if(isset($this->model->proxy->reader->totalProperty)){
					$record[$this->model->proxy->reader->totalProperty] = $total;
				}else{
					$record['total'] = $total;
				}
			}

			$record[$this->model->proxy->reader->root] = $this->record;
			$this->record = $record;
		}

	}
}
