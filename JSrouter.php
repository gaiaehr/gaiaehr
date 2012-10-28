<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 10/11/12
 * Time: 6:27 PM
 */
if(!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
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
	include_once($_SESSION['root'] . '/dataProvider/Globals.php');

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