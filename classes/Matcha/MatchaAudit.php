<?php
/**
 * Matcha::connect microORM v0.0.1
 * This set of classes will help Sencha ExtJS and PHP developers deliver fast and powerful application fast and easy to develop.
 * If Sencha ExtJS is a GUI Framework of the future, think Matcha micrORM as the bridge between the Client-Server
 * GAP. 
 * 
 * Matcha will read and parse a Sencha Model .js file and then connect to the database and produce a compatible database-table
 * from your model. Also will provide the basic functions for the CRUD. If you are familiar with Sencha ExtJS, and know 
 * about Sencha Models, you will need this PHP Class. You can use it in any way you want, in MVC like pattern, your own pattern, 
 * or just playing simple. It's compatible with all your coding style. 
 * 
 * Taking some ideas from diferent microORM's and full featured ORM's we bring you this cool Class. 
 * 
 * History:
 * Born in the fields of GaiaEHR we needed a way to develop the application more faster, Gino Rivera suggested the use of an
 * microORM for fast development and the development began. We tried to use some already developed and well known ORM's on the 
 * space of PHP, but none satisfied our needs. So Gino Rivera sugested the development of our own microORM (a long way to run).
 * 
 * But despite the long run, it returned to be more logical to get ideas from the well known ORM's and how Sensha manage their models
 * so this is the result. 
 *  
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