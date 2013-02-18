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