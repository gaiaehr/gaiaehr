<?php
if(!defined('_GaiaEXEC')) die('No direct access allowed.');
//if(!defined('_GaiaEXEC')) die('No direct access allowed.');
/* The GaiaEHR Registry File, this will containt all the global variables
 * used by GaiaEHR, putting here variable is a security risk please consider
 * first putting here variables that are not sesible to the database.
 * 
 * version 0.0.1
 * revision: N/A
 * author: GI Technologies, 2011
 *
 * Read the SITES directory first
 * To get the conf.php
 *
 * this returns the current folder and defined it as a root.
 */
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
$_SESSION['dir']['adoHelper']   = 'dbHelper';
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
