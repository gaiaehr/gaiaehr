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
 
class MatchaAudit extends Matcha
{
    /**
     * MatchaAudit public and private variables
     */
    public static $__audit = false;
    public static $eventLogData = array();
    public static $hookClass = NULL;
    public static $hookMethod = NULL;

	/**
	 * function auditSaveLog($arrayToInsert = array()):
	 * Every store has to be logged into the database.
	 * Also generate the table if does not exist.
	 */
	static public function auditSaveLog()
	{
		// if the $__audit is true run the procedure if not skip it
		if(!self::$__audit) return false;
		try
		{
			// insert the event log
			$fields = "`".(string)implode("`, `", array_keys(self::$eventLogData))."`";
			$values = "'".(string)implode("', '", array_values(self::$eventLogData))."'";
			self::$__conn->query('INSERT INTO eventlog ('.$fields.') VALUES ('.$values.');');
			return self::$__conn->lastInsertId();
		}
		catch(PDOException $e)
		{
            MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

    /**
     * function audit($onoff = true):
     * Method to enable the audit log process.
     * This will write a log every time it INSERT, UPDATE, DELETE a record.
     */
    static public function audit($onoff = TRUE)
    {
        self::$__audit = $onoff;
        return self::$__audit;
    }

    /**
     * function setHookMethodCall($method = NULL):
     * Method to set the method to call when MatchaCUP->save or MatchaCUP->destroy
     * is executed.
     * @param null $class
     * @param null $method
     * @return bool
     */
    static public function setHookMethodCall($class = NULL, $method = NULL)
    {
        self::$hookClass = $class;
        self::$hookMethod = $method;
        return true;
    }

    /**
     * function defineLogModel($logModelArray):
     * Method to define the audit log structure all data and definition will be saved in LOG table.
     * @param $logModelArray
     * @return bool or exception
     */
    static public function defineLogModel($logModelArray)
    {
        try
        {
            //check if the table exist
            $recordSet = self::$__conn->query("SHOW TABLES LIKE 'eventlog';");
            if( isset($recordSet) ) self::__createTable('eventlog');
            unset($recordSet);

            // get the table column information and remove the id column
            // from the log table
            $tableColumns = self::$__conn->query("SHOW FULL COLUMNS IN eventlog;")->fetchAll();
            unset($tableColumns[MatchaUtils::__recursiveArraySearch('id', $tableColumns)]);

            // prepare the columns from the table and passed array for comparison
            $columnsTableNames = array();
            $columnsLogModelNames = array();
            foreach($tableColumns as $column) $columnsTableNames[] = $column['Field'];
            foreach($logModelArray as $column) $columnsLogModelNames[] = $column['name'];

            // get all the column that are not present in the database-table
            $differentCreateColumns = array_diff($columnsLogModelNames, $columnsTableNames);
            $differentDropColumns = array_diff($columnsTableNames, $columnsLogModelNames);

            if( count($differentCreateColumns) || count($differentDropColumns) )
            {
                // create columns on the database
                foreach($differentCreateColumns as $key => $column) self::__createColumn($logModelArray[$key], 'eventlog');
                // remove columns from the table
                foreach($differentDropColumns as $column) self::__dropColumn( $column, 'eventlog' );
            }
            return true;
        }
        catch(PDOException $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }
}