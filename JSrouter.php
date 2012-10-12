<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 10/11/12
 * Time: 6:27 PM
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/dataProvider/i18nRouter.php');
include_once($_SESSION['site']['root'] . '/dataProvider/ACL.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Globals.php');

$i18n = i18nRouter::getTranslation();
$acl = new ACL();
$perms = array();
foreach($acl->getAllUserPermsAccess() AS $perm){
	$perms[$perm['perm']] = $perm['value'];
}
$user = new User();
$userData = $user->getCurrentUserBasicData();

header('Content-Type: text/javascript');
print 'acl = '. json_encode($perms).';';
print 'i18n = '. json_encode($i18n).';';
print 'user = '. json_encode($userData).';';
print 'settings.site_url = "'. Globals::setGlobals().'";';