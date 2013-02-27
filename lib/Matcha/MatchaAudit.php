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

	/**
	 * __auditLog($arrayToInsert = array()):
	 * Every store has to be logged into the database.
	 * Also generate the table if does not exist.
	 */
	static public function auditSaveLog($arrayToInsert = array())
	{
		// if the $__audit is true run the procedure if not skip it
		if(!self::$__audit) return false;
		try
		{
			// insert the event log
			$fields = "`".(string)implode("`, `", array_keys($arrayToInsert))."`";
			$values = "'".(string)implode("', '", array_values($arrayToInsert))."'";
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
    static public function audit($onoff = TRUE)
    {
        self::$__audit = $onoff;
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
            $recordSet = self::$__conn->query("SHOW TABLES LIKE 'log';");
            if( isset($recordSet) ) self::__createTable('log');
            unset($recordSet);

            // get the table column information and remove the id column
            // from the log table
            $recordSet = self::$__conn->query("SHOW FULL COLUMNS IN log;");
            $tableColumns = $recordSet->fetchAll(PDO::FETCH_ASSOC);
            unset($tableColumns[self::__recursiveArraySearch('id', $tableColumns)]);

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
                foreach($differentCreateColumns as $key => $column) self::__createColumn($logModelArray[$key], 'log');
                // remove columns from the table
                foreach($differentDropColumns as $column) self::__dropColumn( $column, 'log' );
            }
            return true;
        }
        catch(PDOException $e)
        {
            return MatchaErrorHandler::__errorProcess($e);
        }
    }
}