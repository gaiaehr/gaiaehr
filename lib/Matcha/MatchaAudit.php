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
class MatchaAudit extends Matcha {
	/**
	 * MatchaAudit public and private variables
	 */
	public static $__audit = false;
	public static $eventLogData = array();
	public static $hookClass = NULL;
	public static $hookMethod = NULL;
	public static $hookTable = NULL;

	/**
	 * function auditSaveLog($arrayToInsert = array()):
	 * Every store has to be logged into the database.
	 * Also generate the table if does not exist.
	 */
	static public function auditSaveLog(){
		// if the $__audit is true run the procedure if not skip it
		if(!self::$__audit)
			return false;
		try{
			// insert the event log
			$fields = array_keys(self::$eventLogData);
			$placeholders = array();
			foreach($fields as $field){
				$placeholders[] = ':' . $field;
			}
			$query = 'INSERT INTO `' . self::$hookTable . '` ';
			$query .= '(`' . implode('`, `', $fields) . '`)';
			$query .= ' VALUES (' . implode(', ', $placeholders) . ')';

			$insert = Matcha::$__conn->prepare($query);
			foreach(self::$eventLogData as $key => $value){
				if(is_int($value)){
					$param = PDO::PARAM_INT;
				} elseif(is_bool($value)){
					$param = PDO::PARAM_BOOL;
				} elseif(is_null($value)){
					$param = PDO::PARAM_NULL;
				} elseif(is_string($value)){
					$param = PDO::PARAM_STR;
				} else{
					$param = FALSE;
				}
				$insert->bindValue(":$key", $value, $param);
			}
			$insert->execute();
			return self::$__conn->lastInsertId();
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

	/**
	 * function audit($logModelArray = array(), $methodCall = NULL, $idColumn = 'id', $logTable = 'log', $classCall = NULL)
	 * Method to enable the audit log process.
	 * This will write a log every time it INSERT, UPDATE, DELETE a record.
	 */
	static public function audit($logModelArray = array(), $methodCall = NULL, $idColumn = 'id', $logTable = 'log', $classCall = NULL){
		self::$__audit = true;
		self::$hookTable = $logTable;
		MatchaModel::$tableId = $idColumn;
		if($classCall == NULL)
			self::$hookClass = get_called_class(); else self::$hookClass = $classCall;
		self::$hookMethod = $methodCall;
		self::defineLogModel($logModelArray);
	}

	/**
	 * function defineLogModel($logModelArray):
	 * Method to define the audit log structure all data and definition will be saved in LOG table.
	 * @param $logModelArray
	 * @return bool or exception
	 */
	static public function defineLogModel($logModelArray){
		try{
			if(!is_object(self::$__conn))
				return false;

			//check if the table exist
			$recordSet = self::$__conn->query("SHOW TABLES LIKE '" . self::$hookTable . "';");
			if(isset($recordSet))
				self::__createTable(self::$hookTable);
			unset($recordSet);

			// get the table column information and remove the id column
			// from the log table
			$tableColumns = self::$__conn->query("SHOW FULL COLUMNS IN " . self::$hookTable . ";")->fetchAll();
			unset($tableColumns[MatchaUtils::__recursiveArraySearch('id', $tableColumns)]);

			// prepare the columns from the table and passed array for comparison
			$columnsTableNames = array();
			$columnsLogModelNames = array();
			foreach($tableColumns as $column)
				$columnsTableNames[] = $column['Field'];
			foreach($logModelArray as $column)
				$columnsLogModelNames[] = $column['name'];

			// get all the column that are not present in the database-table
			$differentCreateColumns = array_diff($columnsLogModelNames, $columnsTableNames);
			$differentDropColumns = array_diff($columnsTableNames, $columnsLogModelNames);

			if(count($differentCreateColumns) || count($differentDropColumns)){
				// create columns on the database
				foreach($differentCreateColumns as $key => $column)
					self::__createColumn($logModelArray[$key], self::$hookTable);
				// remove columns from the table
				foreach($differentDropColumns as $column)
					self::__dropColumn($column, self::$hookTable);
			}
			return true;
		} catch(PDOException $e){
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}
}