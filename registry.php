<?php
/**
 * GaiaEHR
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

//if(!defined('_GaiaEXEC')) die('No direct access allowed.');

$sites    = array();
$confs    = array();
$dir = (file_exists('sites/') ? 'sites/' : '../sites/');
if($handle = opendir($dir))
{
	while(false !== ($entry = readdir($handle))) 
	{
		if($entry != '.' && $entry != '..' && is_dir($dir . $entry) === true)
		{
			$confs[] = "$entry/conf.php";
			$sites[] = $entry;
		}
	}
	closedir($handle);
}

// general
$_SESSION['root'] = str_replace('\\', '/', dirname(__FILE__));
$_SESSION['url']   = 'http://' . $_SERVER['HTTP_HOST'].'/'.basename(dirname(__FILE__));

// sites values
$_SESSION['sites']['sites'] = $sites;
$_SESSION['sites']['count'] = count($sites);;
$_SESSION['sites']['confs'] = $confs;

// timeout values
$_SESSION['inactive']['time']    = 60;
$_SESSION['inactive']['start']   = true;
$_SESSION['inactive']['life']    = (time() - (isset($_SESSION['inactive']['timeout']) ? $_SESSION['inactive']['timeout'] : time()));
$_SESSION['inactive']['timeout'] = time();

// cron job
$_SESSION['cron']['delay'] = 60; // in seconds
$_SESSION['cron']['time']  = time(); // store the last cron time stamp

// directories
$_SESSION['dir']['ext']         = 'extjs-4.1.1';
$_SESSION['dir']['touch']       = 'sencha-touch-2.0.1';
$_SESSION['dir']['ext_cal']     = 'extensible-1.5.1';
$_SESSION['dir']['AES']         = 'phpAES';
$_SESSION['dir']['ext_classes'] = 'classes/ext';

// patient
$_SESSION['patient']['pid']      = null;
$_SESSION['patient']['name']     = null;
$_SESSION['patient']['readOnly'] = null;

// server data
$_SESSION['server']                = $_SERVER;
$_SESSION['server']['OS']          = (php_uname('s') == 'Linux' ? 'Linux' : 'Windows');
$_SESSION['server']['IS_WINDOWS']  = (php_uname('s') == 'Linux' ? false : true);
$_SESSION['server']['PHP_VERSION'] = phpversion();
$_SESSION['server']['token']       = null;
$_SESSION['server']['last_tid']    = null;

// client data
$_SESSION['client']['browser'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['client']['os']      = (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') === false ? 'Linux' : 'Windows');
