<?php
/**
 * GaiaEHR Configuration file per site
 * MySQL Config
 * Database Init Configuration
 */
//$_SESSION['site'] = array();
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
$_SESSION['site']['name'] = '%sitename%';
$_SESSION['site']['default_localization']  = '%lang%';
$_SESSION['site']['theme'] = '%theme%';
$_SESSION['site']['timezone'] = '%timezone%';

$_SESSION['site']['id']    = basename(dirname(__FILE__));
$_SESSION['site']['dir']   = $_SESSION['site']['id'];
$_SESSION['site']['url']   = $_SESSION['url'] . '/sites/' . $_SESSION['site']['dir'];
$_SESSION['site']['path']  = str_replace('\\', '/', dirname(__FILE__));
$_SESSION['site']['temp']['url']  = $_SESSION['site']['url'] . '/temp';
$_SESSION['site']['temp']['path'] = $_SESSION['site']['path'] . '/temp';