<?php
/* The GaiaEHR Registry File, this will containt all the global variables
 * used by GaiaEHR, putting here variable is a security risk please consider
 * first putting here variables that are not sesible to the database.
 * 
 * version 0.0.1
 * revision: N/A
 * author: Ernesto J Rodriguez
 *
 */
if(!isset($_SESSION)){
    session_name ( "GaiaEHR" );
    session_start();
    session_cache_limiter('private');
}

include_once($_SESSION['site']['root'].'/dataProvider/ACL.php');
include_once($_SESSION['site']['root'].'/dataProvider/User.php');
$ACL = new ACL();
$User = new User();
?>

perm = {
    access_dashboard    : <?php print ($ACL->hasPermission('access_dashboard')  ? 'true':'false') ?>,
    access_calendar     : <?php print ($ACL->hasPermission('access_calendar')   ? 'true':'false') ?>,
    access_messages     : <?php print ($ACL->hasPermission('access_messages')   ? 'true':'false') ?>,
    search_patient      : <?php print ($ACL->hasPermission('search_patient')    ? 'true':'false') ?>,


    add_patient         : <?php print ($ACL->hasPermission('add_patient')       ? 'true':'false') ?>,
    open_patient        : <?php print ($ACL->hasPermission('open_patient')      ? 'true':'false') ?>,
    open_patient        : <?php print ($ACL->hasPermission('open_patient')      ? 'true':'false') ?>,
    access_encounters   : <?php print ($ACL->hasPermission('access_encounters') ? 'true':'false') ?>,




    access_gloabal_settings : <?php print ($ACL->hasPermission('access_gloabal_settings')   ? 'true':'false') ?>,
    access_facilities       : <?php print ($ACL->hasPermission('access_facilities')         ? 'true':'false') ?>,
    access_users            : <?php print ($ACL->hasPermission('access_users')              ? 'true':'false') ?>,
    access_practice         : <?php print ($ACL->hasPermission('access_practice')           ? 'true':'false') ?>,
	access_data_manager     : <?php print ($ACL->hasPermission('access_data_manager')       ? 'true':'false') ?>,
    access_preventive_care  : <?php print ($ACL->hasPermission('access_preventive_care')    ? 'true':'false') ?>,
    access_medications      : <?php print ($ACL->hasPermission('access_medications')        ? 'true':'false') ?>,
    access_roles            : <?php print ($ACL->hasPermission('access_roles')              ? 'true':'false') ?>,
    access_layouts          : <?php print ($ACL->hasPermission('access_layouts')            ? 'true':'false') ?>,
    access_lists            : <?php print ($ACL->hasPermission('access_lists')              ? 'true':'false') ?>,
    access_event_log        : <?php print ($ACL->hasPermission('access_event_log')          ? 'true':'false') ?>,
    access_documents        : <?php print ($ACL->hasPermission('access_documents')          ? 'true':'false') ?>

};

user = {
    id     : <?php print $User->getCurrentUserId() ?>,
    name   : '<?php print $User->getCurrentUserTitleLastName() ?>'

};

settings = {
    site_url: '<?php print $_SESSION['site']['url'].'/sites/'.$_SESSION['site']['site'] ?>'
};
