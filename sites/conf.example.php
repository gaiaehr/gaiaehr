<?php
/**
 * GaiaEHR Configuration file per site
 * MySQL Config
 * Database Init Configuration
 */
$_SESSION['site']['db']['type'] = 'mysql';
$_SESSION['site']['db']['host'] = '%host%';
$_SESSION['site']['db']['port'] = '%port%';
$_SESSION['site']['db']['username'] = '%user%';
$_SESSION['site']['db']['password'] = '%pass%';
$_SESSION['site']['db']['database'] = '%db%';
/**
 * AES Key
 * 256bit - key
 */
$_SESSION['site']['AESkey'] = "%key%";
/**
 * Default site language and theme
 * Check if the localization variable already has a value, if not pass the 
 * default language.
 */
if(!$_SESSION['site']['localization']) $_SESSION['site']['localization'] = '%lang%';
$_SESSION['site']['theme'] = '%theme%';
$_SESSION['site']['directory']      = str_replace('\\', '/', dirname(__FILE__));
/**
 * Setup Command
 * If it's true, the application will
 * run the Setup Wizard
 */
$_SESSION['site']['setup'] = false;