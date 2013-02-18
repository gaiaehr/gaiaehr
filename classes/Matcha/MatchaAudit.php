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
	/**
	 * function __auditLog($sqlStatement = ''):
	 * Every store has to be logged into the database.
	 * Also generate the table if does not exist.
	 */
	static public function __auditLog($sqlStatement = '')
	{
		// if the $__audit is true run the procedure if not skip it
		if(!Matcha::$__audit) return true;
		// generate the appropriate event log comment 
		$record = array();
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
			$recordSet = self::$__conn->query("SHOW TABLES LIKE '".self::$__senchaModel['table']."';");
			if( $recordSet->fetch(PDO::FETCH_ASSOC) ) self::__createTable('log');
			unset($recordSet);
			
			//check for the available fields
			$recordSet = self::$__conn->query("SHOW COLUMNS IN ".self::$__senchaModel['table'].";");
			if( $recordSet->fetchAll(PDO::FETCH_ASSOC) ) self::__logModel();
			unset($recordSet);
				
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
	 * function __logModel():
	 * Method to create the log table columns
	 */
	static private function __logModel()
	{
		try
		{
			self::$__conn->query("CREATE TABLE IF NOT EXISTS `log` (
						`id` bigint(20) NOT NULL AUTO_INCREMENT,
						`date` datetime DEFAULT NULL,
						`event` varchar(255) DEFAULT NULL,
						`user` varchar(255) DEFAULT NULL,
						`facility` varchar(255) NOT NULL,
						`comments` longtext,
						`user_notes` longtext,
						`patient_id` bigint(20) DEFAULT NULL,
						`success` tinyint(1) DEFAULT '1',
						`checksum` longtext,
						`crt_user` varchar(255) DEFAULT NULL,
						`ip` varchar(50) DEFAULT NULL,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
			return true;
		}
		catch(PDOException $e)
		{
			return MatchaErrorHandler::__errorProcess($e);
		}
	}
}