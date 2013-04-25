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

if(!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
require_once ($_SESSION['root'] . '/classes/MatchaHelper.php');
include_once($_SESSION['root'] . '/dataProvider/i18nRouter.php');
include_once($_SESSION['root'] . '/dataProvider/Globals.php');
header('Content-Type: text/javascript');

// Output the translation selected by the user.
$i18n = i18nRouter::getTranslation();
print 'lang = '. json_encode( $i18n ).';';

// Output all the globals settings on the database.
$global = Globals::getGlobals();
$global['root'] = $_SESSION['root'];
$global['url']  = $_SESSION['url'];
$global['site']  = $_SESSION['site']['dir'];
print 'globals = '. json_encode( $global ).';';

if(!isset($_SESSION['site']['error']) && (isset($_SESSION['user']) && $_SESSION['user']['auth'] == true))
{
	include_once($_SESSION['root'] . '/dataProvider/ACL.php');
	include_once($_SESSION['root'] . '/dataProvider/User.php');

	$acl = new ACL();
	$perms = array();
	/*
	 * Look for user permissions and pass it to a PHP variable.
	 * This variable will be used in JavaScript code
	 * look at it as a PHP to JavaScript variable conversion.
	 */
	foreach($acl->getAllUserPermsAccess() AS $perm)
	{
		$perms[$perm['perm']] = $perm['value'];
	}
	$user = new User();
	$userData = $user->getCurrentUserBasicData();
	Globals::setGlobals();
	/*
	 * Pass all the PHP to JavaScript
	 */
	print 'acl = '. json_encode($perms).';';
	print 'user = '. json_encode($userData).';';
	print 'settings.site_url = "'. $_SESSION['site']['url'] .'";';
}