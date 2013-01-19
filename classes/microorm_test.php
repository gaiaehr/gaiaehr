<?php

session_name('GaiaEHR');
session_start();
session_cache_limiter('private');
define('_GaiaEXEC', 1);

include_once('../registry.php');
include_once('../sites/default/conf.php');
include_once('dbHelper.php');

$db = new dbHelper();

$db->setTable('AAA_facility');

$db->setField('active', 'TINYINT', 1, false, false);
$db->setField('phone', 'VARCHAR', 30, true, false);
$db->setField('fax', 'VARCHAR', 30, true, false);
$db->setField('street', 'VARCHAR', 255, true, false);
$db->setField('city', 'VARCHAR', 255, true, false);
$db->setField('state', 'VARCHAR', 50, true, false);
$db->setField('postal_code', 'VARCHAR', 11, true, false);
$db->setField('country_code', 'VARCHAR', 10, true, false);
$db->setField('federal_ein', 'VARCHAR', 15, true, false);
$db->setField('service_location', 'TINYINT', 1, false, false);
$db->setField('billing_location', 'TINYINT', 1, false, false);
$db->setField('accepts_assignment', 'TINYINT', 1, false, false);
$db->setField('pos_code', 'TINYINT', 4, true, false);
$db->setField('x12_sender_id', 'VARCHAR', 25, true, false);
$db->setField('attn', 'VARCHAR', 65, true, false);
$db->setField('domain_identifier', 'VARCHAR', 60, true, false);
$db->setField('facility_npi', 'VARCHAR', 15, true, false);
$db->setField('tax_id_type', 'VARCHAR', 31, false, false);
//$db->setField('diarrea', 'VARCHAR', 31, true, false);

$db->executeORM();
 
?>