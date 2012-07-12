<?php
if(!defined('_GaiaEXEC')) die('No direct access allowed.');
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
$d = 'sites/';
if(!file_exists($d)){
    mkdir($d, 0755);
}
$d = dir($d);

$sites = array();
$confs = array();

while (false !== ($entry = $d->read())) {
	if($entry != '.' && $entry != '..' && $entry != 'README' && $entry != '.DS_Store'){
        $confs[] = $entry . '/conf.php';
    }
    if($entry != '.' && $entry != '..' && $entry == 'default' && $entry != 'README' && $entry != '.DS_Store'){
        $default = $entry;
    }
    if($entry != '.' && $entry != '..' && $entry != 'README' && $entry != '.DS_Store'){
        $sites[] = $entry;
    }
}
$_SESSION['site']['self']       = $_SERVER['PHP_SELF'];
$_SESSION['site']['sites']      = $sites;
$_SESSION['site']['sitesCount'] = count($sites);
$_SESSION['site']['sites_conf'] = $confs;
$_SESSION['site']['root']       = str_replace('\\','/',dirname(__FILE__));
$_SESSION['site']['url']        = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['PHP_SELF']);
$_SESSION['site']['facility']   = 'default'; // THIS IS A TEMP VARIABLE

$_SESSION['inactive']['time']   = 60;
$_SESSION['inactive']['life']   = (time() - (isset($_SESSION['inactive']['timeout'])? $_SESSION['inactive']['timeout'] : time()));
$_SESSION['inactive']['timeout']= time();


$_SESSION['cron']['delay']      = 60;   // in seconds
$_SESSION['cron']['time']       = time(); // store the last cron time stamp

/**
 * Directory related variables
 */
//$_SESSION['dir']['ext']         = 'extjs-4.1.1';
$_SESSION['dir']['ext']         = 'extjs-4.1.0';
$_SESSION['dir']['touch']       = 'sencha-touch-2.0.1';
$_SESSION['dir']['ext_cal']     = 'extensible-1.5.1';
$_SESSION['dir']['AES']         = 'phpAES';
$_SESSION['dir']['adoHelper']   = 'dbHelper';
$_SESSION['dir']['ext_classes'] = 'classes/ext';
$_SESSION['dir']['jasper'] 		= 'jasperreports-4.5.1';
/**
 * Patient Related Variables
 */
$_SESSION['patient']['pid']     = null;
$_SESSION['patient']['name']    = null;
 /**
 * Server related variables
 */
$_SESSION['server']             = $_SERVER;
$_SESSION['server']['OS']       = (strstr( strtolower($_SERVER['SERVER_SIGNATURE']), 'win') ? 'Windows' : 'Linux');
$_SESSION['server']['token']    = null;
$_SESSION['server']['last_tid'] = null;
/**
 * Client related variables
 */
$_SESSION['client']['os']       = php_uname('s');