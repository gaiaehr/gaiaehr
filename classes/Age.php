<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
$_SESSION['site']['flops'] = 0;
class Age
{

	public static function getYearsBetweenDates($date1, $date2){
		$date1 = new DateTime($date1);
		$date2 = new DateTime($date2);
		$interval = $date1->diff($date2);
		return $interval->y;
	}

	public static function getMonsBetweenDates($date1, $date2){
		$date1 = new DateTime($date1);
		$date2 = new DateTime($date2);
		$interval = $date1->diff($date2);
		return $interval->y === 0 ? $interval->m : ($interval->y * 12) + $interval->m;
	}

	public static function getDaysBetweenDates($date1, $date2){
		$start = strtotime($date1);
		$end = strtotime($date2);
		$diff = $end - $start;
		return round($diff / 86400);
	}

}

//print Age::getDaysBetweenDates("2007-03-24", "2009-06-26");



