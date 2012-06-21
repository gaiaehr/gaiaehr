<?php
/* GaiaEHR Starter
 * 
 * Description: This will start the application, if no sites are found
 * in the sites directory run the setup wizard, if a directory is found
 * run the login screen. When the logon is submitted it will validate
 * the user and start the main application
 * 
 * Author: GI Technologies, 2011
 * modified: Ernesto J Rodriguez, Nov 7, 2011
 *
 * Ver: 0.0.3
 * 
 */
/**
 * Startup the SESSION
 * This will change in the future.
 * Maybe implement a SESSION Manager against the database.
 */
session_name ( "GaiaEHR" );
session_start();
session_cache_limiter('private');
define('_GaiaEXEC', 1);
/*
 * Startup the registry
 * This contains SESSION Variables to use in the application
 * and mobile_detect class is used to detect mobile browsers.
 */
include_once("registry.php");
include_once("classes/Mobile_Detect.php");
$mobile = new Mobile_Detect();
/**
 * Make the auth process
 */
if(isset($_SESSION['user']['auth'])){
	if ($_SESSION['user']['auth'] == true){
        /**
         * if mobile go to mobile app, else go to app
         */
        if($_SESSION['site']['checkInMode']){
            include_once("checkin/checkin.php");
        }elseif($mobile->isMobile()) {
		    include_once("app_mobile.php");
        }else{
	        include_once($_SESSION['site']['root'].'/dataProvider/Globals.php');
	        Globals::setGlobals();
            include_once("app.php");
        }
	}
/**
 * Make the logon process or Setup process
 */
} else {
	/**
     * Browse the site dir first
     */
	$count = 0;
	foreach ($_SESSION['site']['sites'] as $site){ $count++; }
	/**
     * If no directory is found inside sites dir run the setup wizard,
     * if a directory is found inside sites dir run the logon screen
     */
	if( $count <= 0){
		include_once("install/install.ejs.php");
	} else {
        /**
         * if mobile go to mobile app, else go to app
         */
        if ($mobile->isMobile()) {
            include_once("login/login_mobile.php");
        }else{
            include_once("login/login.php");
        }
	}
}