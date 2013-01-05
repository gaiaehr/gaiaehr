<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ernesto
 * Date: 7/4/12
 * Time: 2:23 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name ('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

include_once($_SESSION['root'].'/classes/dbHelper.php');

$db = new dbHelper();
$tables = array(
	'calendar_events',
	'encounter_codes_cpt',
	'encounter_codes_hcpcs',
    'encounter_hcfa_1500_options',
    'encounter_codes_icdx',
	'encounter_history',
	'patient_demographics',
	'encounter_dictation',
	'encounters',
	'encounter_review_of_systems',
	'encounter_review_of_systems_check',
	'encounter_soap',
	'encounter_vitals',
	'messages',
	'users_sessions',
	'emergencies',


	// patient tables
	'patient_allergies',
	'patient_disclosures',
	'patient_immunizations',
	'patient_medications',
	'patient_notes',
	'patient_active_problems',
	'patient_dental',
	'patient_doctors_notes',
	'patient_orders',
	'patient_pools',
	'patient_labs_results',
	'patient_labs',
	'patient_documents',
	'patient_prescriptions',
	'patient_reminders',
	'patient_disclosures',
	'patient_surgery',
	'patient_out_chart',
	'patient_zone',
	'preventive_care_inactive_patient',

    'payment_transactions',


	// codes table
	'icd9_dx_code',
	'icd9_dx_long_code',
	'icd9_sg_code',
	'icd9_sg_long_code',
	'icd10_dx_order_code',
	'icd10_gem_dx_9_10',
	'icd10_gem_dx_10_9',
	'icd10_gem_pcs_9_10',
	'icd10_gem_pcs_10_9',
	'icd10_pcs_order_code',
	'icd10_reimbr_dx_9_10',
	'icd10_reimbr_pcs_9_10',
	'rxnatomarchive',
	'rxnconso',
	'rxncui',
	'rxncuichanges',
	'rxndoc',
	'rxnrel',
	'rxnsab',
	'rxnsat',
	'rxnsty',
	'sct_concepts',
	'sct_descriptions',
	'sct_relationships',
	'standardized_tables_track',
	'log'
);

function getDirectoryList ($directory)
{
  $results = array();
  $handler = opendir($directory);
  while ($file = readdir($handler)) {
    if ($file != "." && $file != "..") {
      $results[] = $file;
    }
  }
  closedir($handler);
  return $results;
}

function RemoveReclusiveDir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") RemoveReclusiveDir($dir."/".$object); else unlink($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }

	return true;
}


//print '<h1>SQL Clean Up!</h1>';

foreach($tables as $table){
	$db->setSQL("TRUNCATE TABLE $table");
	$err = $db->execOnly();
	$msg = (isset($err[1])) ? 'FAIL!' : 'PASS!';
	//print 'Empty  `' . $table . '`  ' . $msg . '<br>';
}

//print '<h1>Patient Directories Clean Up!</h1>';
$path = '../sites/'.$_SESSION['site']['dir'].'/patients/';
$patientsDir = getDirectoryList($path);

foreach($patientsDir as $dir){
	RemoveReclusiveDir($path . $dir);
}

print 'Factory Reset Complete!';

