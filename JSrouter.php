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

if(!isset($_SESSION)){
    session_cache_limiter('private');
    session_cache_expire(1);
    session_regenerate_id(false);
    session_name('GaiaEHR');
    session_start();
    setcookie(session_name(),session_id(),time()+86400, '/', null, false, true);
}
ob_start();
if(!defined('_GaiaEXEC')) define('_GaiaEXEC', 1);
require_once(dirname(__FILE__) . '/registry.php');
require_once(dirname(__FILE__) . '/sites/'.$_REQUEST['site'].'/conf.php');
require_once(dirname(__FILE__) . '/classes/MatchaHelper.php');
include_once(dirname(__FILE__) . '/dataProvider/i18nRouter.php');
include_once(dirname(__FILE__) . '/dataProvider/Globals.php');
header('Content-Type: text/javascript');

// check if is emergency access....
if(isset($_SESSION['user']) && isset($_SESSION['user']['emergencyAccess']) && $_SESSION['user']['emergencyAccess'])
{
	$isEmerAccess = 1;
}
else
{
	$isEmerAccess = 0;
}
print 'isEmerAccess = '.$isEmerAccess.';';


// Output the translation selected by the user.
$i18n = i18nRouter::getTranslation();
print 'lang = '. json_encode( $i18n ).';';

// Output all the globals settings on the database.
$global = Globals::setGlobals();
$global['root'] = ROOT;
$global['url']  = URL;
$global['host']  = HOST;
$global['site']  = site_dir;

print 'globals = '. json_encode( $global ).';';

if(!isset($_SESSION['site']['error']) && (isset($_SESSION['user']) && $_SESSION['user']['auth'] == true)){
	include_once(dirname(__FILE__) . '/dataProvider/ACL.php');
	include_once(dirname(__FILE__) . '/dataProvider/Facilities.php');
	include_once(dirname(__FILE__) . '/dataProvider/User.php');

	$ACL = new ACL();
	$perms = array();
	/*
	 * Look for user permissions and pass it to a PHP variable.
	 * This variable will be used in JavaScript code
	 * look at it as a PHP to JavaScript variable conversion.
	 */
	foreach($ACL->getAllUserPermsAccess() AS $perm){
		$perms[$perm['perm']] = $perm['value'];
	}
	unset($ACL);

	$User = new User();
	$userData = $User->getCurrentUserBasicData();
	$userData['site'] = $_SESSION['user']['site'];
	$userData['token'] = $_SESSION['user']['token'];
	$userData['facility'] = $_SESSION['user']['facility'];
	$userData['localization'] = $_SESSION['user']['localization'];
	unset($User);

	$Facilities = new Facilities();
	$structure = $Facilities->geFacilitiesStructure();
	unset($Facilities);

	/*
	 * Pass all the PHP to JavaScript
	 */
	print 'window.acl = ' . json_encode($perms) . ';';
	print 'window.user = ' . json_encode($userData) . ';';
	print 'window.structure = ' . json_encode($structure) . ';';
	print 'window.settings.site_url = "' . $global['url'] . '";';

	if(isset($_SESSION['styles'])){
		print 'window.styles = ' . json_encode($_SESSION['styles']) . ';';
	}

	if(isset($_SESSION['scripts'])){
		print 'window.scripts = ' . json_encode($_SESSION['scripts']) . ';';
	}

}

