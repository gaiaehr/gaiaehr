<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//$_SESSION['site'] = array();
$_SESSION['site']['db']['type'] = 'mysql';
$_SESSION['site']['db']['host'] = '#host#';
$_SESSION['site']['db']['port'] = '#port#';
$_SESSION['site']['db']['username'] = '#user#';
$_SESSION['site']['db']['password'] = '#pass#';
$_SESSION['site']['db']['database'] = '#db#';
/**
 * AES Key
 * 256bit - key
 */
$_SESSION['site']['AESkey'] = '#key#';
/**
 * HL7 server values
 */
$_SESSION['site']['hl7']['port'] = #hl7Port#;
/**
 * Default site language and theme
 * Check if the localization variable already has a value, if not pass the 
 * default language.
 */
$_SESSION['site']['name'] = '#sitename#';
$_SESSION['site']['default_localization']  = '#lang#';
$_SESSION['site']['theme'] = '#theme#';
$_SESSION['site']['timezone'] = '#timezone#';

$_SESSION['site']['id']    = basename(dirname(__FILE__));
$_SESSION['site']['dir']   = $_SESSION['site']['id'];
$_SESSION['site']['url']   = $_SESSION['url'] . 'sites/' . $_SESSION['site']['dir'];
$_SESSION['site']['path']  = str_replace('\\', '/', dirname(__FILE__));
$_SESSION['site']['temp']['url']  = $_SESSION['site']['url'] . '/temp';
$_SESSION['site']['temp']['path'] = $_SESSION['site']['path'] . '/temp';