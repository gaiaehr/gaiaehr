<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 7/11/12
 * Time: 10:22 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name ('GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'].'/classes/Sessions.php');
class CronJob {

	function run(){
		/**
		 * only run cron if delay time has expired
		 */
		if((time() - $_SESSION['cron']['time']) > $_SESSION['cron']['delay']){
			/**
			 * stuff to run
			 */
			$sessions = new Sessions();
			$sessions->logoutInactiveUsers();
			/**
			 * reset cron time
			 */
			$_SESSION['cron']['time'] = time();
			return array('success'=>true, 'ran'=>true);

		}

		return array('success'=>true, 'ran'=>false);
	}

}
