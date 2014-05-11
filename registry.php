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

if(!defined('_GaiaEXEC')) die('No direct access allowed.');
if(!defined('HTTP')){
	if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)){
		define('HTTP', 'https');
	} else {
		define('HTTP', 'http');
	}
}
if(!defined('HOST')) define('HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
if(!defined('URI'))	define('URI', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/gaiaehr/');
if(!defined('ROOT')) define('ROOT', str_replace('\\', '/', dirname(__FILE__)));
if(!defined('URL'))	define('URL', HTTP . '://' . HOST . URI);
// application version
if(!defined('VERSION'))	define('VERSION', '0.7.200');
// extjs sdk directory
if(!defined('EXTJS')) define('EXTJS', 'extjs-4.1.1a');

// sites values
$_SESSION['sites'] = array();

if(!defined('sites_count')){
	$sitedir = ROOT . '/sites/';
	$count = 0;
	if($handle = opendir($sitedir)){
		while(false !== ($entry = readdir($handle))) {
			if($entry != '.' && $entry != '..' && is_dir($sitedir . $entry) === true)
				$count++;
		}
		closedir($handle);
	}
	define('sites_count', $count);
}

// timeout values
$_SESSION['inactive']['time'] = 60;
$_SESSION['inactive']['start'] = true;
$_SESSION['inactive']['life'] = (time() - (isset($_SESSION['inactive']['timeout']) ? $_SESSION['inactive']['timeout'] : time()));
$_SESSION['inactive']['timeout'] = time();

// cron job
$_SESSION['cron']['delay'] = 60; // in seconds
$_SESSION['cron']['time'] = time(); // store the last cron time stamp

// server data
$_SESSION['server'] = $_SERVER;
$_SESSION['server']['OS'] = (php_uname('s') == 'Linux' ? 'Linux' : 'Windows');
$_SESSION['server']['IS_WINDOWS'] = (php_uname('s') == 'Linux' ? false : true);
$_SESSION['server']['PHP_VERSION'] = phpversion();
$_SESSION['server']['token'] = null;
$_SESSION['server']['last_tid'] = null;

// client data
$_SESSION['client']['browser'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$_SESSION['client']['os'] = (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') === false ? 'Linux' : 'Windows');
