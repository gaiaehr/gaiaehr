<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 7/11/12
 * Time: 10:22 PM
 * To change this template use File | Settings | File Templates.
 */
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/Sessions.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
class CronJob
{

	function run()
	{
		/**
		 * only run cron if delay time has expired
		 */
		if ((time() - $_SESSION['cron']['time']) > $_SESSION['cron']['delay'] || $_SESSION['inactive']['start'])
		{
			/**
			 * stuff to run
			 */
			$s = new Sessions();
			$p = new Patient();

			foreach ($s->logoutInactiveUsers() as $user)
			{
				$p -> patientChartInByUserId($user['uid']);
			}

			/**
			 * set cron start to false reset cron time to current time
			 */
			$_SESSION['inactive']['start'] = false;
			$_SESSION['cron']['time'] = time();
			return array(
				'success' => true,
				'ran' => true
			);
		}
		return array(
			'success' => true,
			'ran' => false
		);
	}

}

//$foo = new CronJob();
//print '<pre>';
//$foo->run();
