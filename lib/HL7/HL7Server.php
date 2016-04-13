<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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
use Ratchet\Server\IoServer;

require(str_replace('\\', '/', dirname(__FILE__)) . '/../../vendor/autoload.php');
require(str_replace('\\', '/', dirname(__FILE__)) . '/HL7ServerAbstract.php');

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$host = $_POST['host'];
$port = $_POST['port'];
$path = $_POST['path'];
$class = $_POST['class'];
$method = $_POST['method'];
$site = $_POST['site'];
$token = $_POST['token'];

define('ROOT', str_replace('lib/HL7', '', str_replace('\\', '/', dirname(__FILE__))));

/**
 * Enable the error and also set the ROOT directory for
 * the error log. But checks if the files exists and is
 * writable.
 *
 * NOTE: This should be part of Matcha::Connect
 */
ini_set('display_errors', 1);
$logPath = ROOT . 'sites/' . $site . '/log/';
if(file_exists($logPath) && is_writable($logPath)){
	$logFile = 'error_log.txt';
	$oldUmask = umask(0);
	clearstatcache();
	if(!file_exists($logPath . $logFile)){
		touch($logPath . $logFile);
		chmod($logPath . $logFile, 0775);
	}
	if(is_writable($logPath . $logFile))
		ini_set('error_log', $logPath . $logFile);
	umask($oldUmask);
}

chdir($path);
include_once("$class.php");

gc_enable();

$server = IoServer::factory(new HL7ServerAbstract, $port);
$server->run();

exit;
