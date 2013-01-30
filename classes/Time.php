<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 5/11/12
 * Time: 7:38 PM
 * To change this template use File | Settings | File Templates.
 */
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

class Time
{
	public static function getLocalTime($format = 'Y-m-d H:i:s')
	{
			return date($format, time());
	}

}
