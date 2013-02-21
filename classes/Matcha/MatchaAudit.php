<?php
 /**
  * Matcha::connect (MatchaAudit Class)
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
 
class MatchaAudit extends Matcha
{

    public $logModel;
    public static $__audit;

	/**
	 * __auditLog($arrayToInsert = array()):
	 * Every store has to be logged into the database.
	 * Also generate the table if does not exist.
	 */
	static protected function __auditLog($arrayToInsert = array())
	{
		// if the $__audit is true run the procedure if not skip it
		if(!Matcha::$__audit) return true;

		// generate the appropriate event log comment
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
			$recordSet = self::$__conn->query("SHOW TABLES LIKE 'log';");
			if( $recordSet->fetch(PDO::FETCH_ASSOC) ) self::__createTable('log');
			unset($recordSet);

            // get the table column information and remove the id column
            // from the log table
            $recordSet = self::$__conn->query("SHOW FULL COLUMNS IN log;");
            $tableColumns = $recordSet->fetchAll(PDO::FETCH_ASSOC);
            unset($tableColumns[self::__recursiveArraySearch('id', $tableColumns)]);

            // prepare the columns from the table and passed array for comparison
            foreach($tableColumns as $column) $columnsTableNames[] = $column['Field'];
            foreach($arrayToInsert as $column) $columnsLogModelNames[] = $column[0];

            // get all the column that are not present in the database-table
            $differentCreateColumns = array_diff($columnsLogModelNames, $columnsTableNames);
            $differentDropColumns = array_diff($columnsTableNames, $columnsLogModelNames);
            if( count($differentCreateColumns) != 0 && count($differentDropColumns) != 0)
			{
                // add columns to the table
                foreach($differentCreateColumns as $key => $column) self::__createColumn($column, $workingModel)]);
                // remove columns from the table
                foreach($differentDropColumns as $key => $column) self::__dropColumn( $column[$key], 'log' );
            }
				
			// insert the event log
			$fields = (string)implode(', ', array_keys($eventData));
			$values = (string)implode(', ', array_values($eventData));
			self::$__conn->query('INSERT INTO log ('.$fields.') VALUES ('.$values.');');
			return self::$__conn->lastInsertId();
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}

    /**
     * function audit($onoff = true):
     * Method to enable the audit log process.
     * This will write a log every time it INSERT, UPDATE, DELETE a record.
     */
    static public function audit($onoff = true)
    {
        self::$__audit = (bool)$onoff;
    }

    static public function defineLogModel($logModelArray)
    {
        self::$logModel = $logModelArray;
        return true;
    }
}