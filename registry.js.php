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
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}

include_once($_SESSION['site']['root'].'/dataProvider/ACL.php');
include_once($_SESSION['site']['root'].'/dataProvider/User.php');
$ACL = new ACL();
$User = new User();
?>


settings = {
    site_url: '<?php print $_SESSION['site']['url'].'/sites/'.$_SESSION['site']['site'] ?>'
};
