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

	public static function getAgeMonthDecimalByDobAndTargetDate($dob,$target) {
		// date format Y-m-d H:i:s
	    $dateDOB = explode(' ',$dob);
	    $dateDOB = explode('-', $dateDOB[0]);
	    $dateTarget = explode(' ',$target);
	    $dateTarget = explode('-',$dateTarget[0]);
	    // Collect differences
	    $iDiffYear  = $dateTarget[0] - $dateDOB[0];
	    $iDiffMonth = $dateTarget[1] - $dateDOB[1];
	    $iDiffDay   = $dateTarget[2] - $dateDOB[2];
	    // If birthday has not happen yet for this year, subtract 1.
	    if($iDiffMonth < 0 || ($iDiffMonth == 0 && $iDiffDay < 0)) $iDiffYear--;
	    // Ensure diffYear is not less than 0
	    if ($iDiffYear < 0) $iDiffYear = 0;
	    return (12 * $iDiffYear) + $iDiffMonth;
	}
}

//print '<pre>';
//print Age::getAgeMonthDecimalByDobAndTargetDate("2007-03-24 00:00:00", "2009-06-26 00:00:00");



