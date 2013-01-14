<?php
/**
 * This code was originally create by Garden State Health Systems for OpenEMR
 * (see credits bellow) and heavily modified by Ernesto J Rodriguez to make it
 * work with GaiaEHR class system.
 *
 * ------------------------------------------------------------------------
 *                     Garden State Health Systems
 *                    Copyright (c) 2010 gshsys.com
 *                      <http://www.gshsys.com/>
 * ------------------------------------------------------------------------
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting
 * source code which is considered copyrighted (c) material of the
 * original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 *
 */
 
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/PoolArea.php');
include_once ($_SESSION['root'] . '/dataProvider/Medical.php');
include_once ($_SESSION['root'] . '/dataProvider/PreventiveCare.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/DiagnosisCodes.php');
class CCR
{
	private $ccr;
	private $pid;
	private $authorID;
	private $patientID;
	private $sourceID;
	private $gaiaID;
	private $start;
	private $end;

	private $patient;
	private $medical;

	function __construct()
	{
		$this->ccr       = new DOMDocument('1.0', 'UTF-8');
		$this->pid       = $_SESSION['patient']['pid'];
		$this->authorID  = $this->getUuid();
		$this->patientID = $this->getUuid();
		$this->sourceID  = $this->getUuid();
		$this->gaiaID    = $this->getUuid();


		$this->patient   = new Patient();
		$this->medical   = new Medical();

	}

	function createCCR($request)
	{

		$action = $request['action'];
		$raw    = $request['raw'];

		$this->pid   = $request['pid'];
//		$this->start = $request['start'];
//		$this->end   = $request['end'];


		//$result = $this->getActorData();
		//		while($res = sqlFetchArray($result[2])) {
		//			${"labID{$res['id']}"} = $this->getUuid();
		//		}
		$e_styleSheet = $this->ccr->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="'.$_SESSION['url'].'/lib/ccr/stylesheet/ccr.xsl"');
		$this->ccr->appendChild($e_styleSheet);
		$e_ccr = $this->ccr->createElementNS('urn:astm-org:CCR', 'ContinuityOfCareRecord');
		$this->ccr->appendChild($e_ccr);
		/**
		 * Header
		 */
		$this->createHeader($e_ccr);
		$e_Body = $this->ccr->createElement('Body');
		$e_ccr->appendChild($e_Body);

		/**
		 * Problems
		 */
		$e_Problems = $this->ccr->createElement('Problems');
		$this->createProblem($e_Problems);
		$e_Body->appendChild($e_Problems);
		/**
		 * Alerts
		 */
		$e_Alerts = $this->ccr->createElement('Alerts');
		$this->createAlerts($e_Alerts);
		$e_Body->appendChild($e_Alerts);
		/**
		 * Medication
		 */
		$e_Medications = $this->ccr->createElement('Medications');
		$this->createMedications($e_Medications);
		$e_Body->appendChild($e_Medications);
		/**
		 * Immunization
		 */
		$e_Immunizations = $this->ccr->createElement('Immunizations');
		$this->createImmunizations($e_Immunizations);
		$e_Body->appendChild($e_Immunizations);
		/**
		 * Results
		 */
		$e_Results = $this->ccr->createElement('Results');
		$this->createResults($e_Results);
		$e_Body->appendChild($e_Results);
		/**
		 * Procedures
		 */
		//		$e_Procedures = $this->ccr->createElement('Procedures');
		//		require_once("createCCRProcedure.php");
		//		$e_Body->appendChild($e_Procedures);
		/**
		 * Footer
		 */
		//		 $e_VitalSigns = $this->ccr->createElement('VitalSigns');
		//		 $e_Body->appendChild($e_VitalSigns);
		/**
		 * Actors
		 */

		$e_Actors = $this->ccr->createElement('Actors');
		$this->createActors($e_Actors);
		$e_ccr->appendChild($e_Actors);
		if($action == 'generate') {
			$this->gnrtCCR($raw);
		}
		if($action == 'viewccd') {
			$this->viewCCD($raw);
		}
	}

	function gnrtCCR($raw)
	{
		$this->ccr->preserveWhiteSpace = false;
		$this->ccr->formatOutput       = true;
		if($raw == 'yes') {
			// simply send the xml to a textarea (nice debugging tool)
			echo '<textarea rows="35" cols="500" style="width:100%; height:99%" readonly>';
			echo $this->ccr->saveXml();
			echo '</textarea>';
			return;
		} else {
			if($raw == 'hybrid') {
				// send a file that contains a hybrid file of the raw xml and the xsl stylesheet
				$this->createHybridXML($this->ccr);
			} else {
				if($raw == 'pure') {
					// send a zip file that contains a separate xml data file and xsl stylesheet
					if(!class_exists('ZipArchive')) {
						$this->displayError('ERROR: Missing ZipArchive PHP Module');
						return;
					}
					$tempDir = $_SESSION['site']['temp']['path'];
					$zipName = $tempDir . '/' . $this->getReportFilename() . '-ccr.zip';
					if(file_exists($zipName)) {
						unlink($zipName);
					}
					$zip = new ZipArchive();
					if($zip->open($zipName, ZipArchive::CREATE)) {
						$zip->addFile($_SESSION['root'] . '/lib/ccr/stylesheet/ccr.xsl', 'stylesheet/ccr.xsl');
						$xmlName = $tempDir . '/' . $this->getReportFilename() . '-ccr.xml';
						if(file_exists($xmlName)) {
							unlink($xmlName);
						}
						$this->ccr->save($xmlName);
						$zip->addFile($xmlName, basename($xmlName));
						$zip->close();
						header('Pragma: public');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Content-Type: application/force-download');
						header('Content-Length: ' . filesize($zipName));
						header('Content-Disposition: attachment; filename=' . basename($zipName) . ';');
						header('Content-Description: File Transfer');
						readfile($xmlName);
//						unlink($zipName);
//						unlink($xmlName);
						exit();
					} else {
						$this->displayError('ERROR: Unable to Create Zip Archive.');
						return;
					}
				} else {
//					print '<pre>';
//					print_r($this->medical->getPatientImmunizationsByPid($this->pid));
					header('Content-type: application/xml');
					print $this->ccr->saveXml();
				}
			}
		}

	}

	function viewCCD($raw)
	{
		$this->ccr->preserveWhiteSpace = false;
		$this->ccr->formatOutput       = true;
		$this->ccr->save($_SESSION['site']['temp']['path'] . '/ccrForCCD.xml');
		$xmlDom = new DOMDocument();
		$xmlDom->loadXML($this->ccr->saveXML());
		$ccr_ccd = new DOMDocument();
		$ccr_ccd->load('../lib/ccr/ccd/ccr_ccd.xsl');
//

		if(class_exists('XSLTProcessor')){
			$xslt = new XSLTProcessor();
			$xslt->importStylesheet($ccr_ccd);
			$ccd                     = new DOMDocument();
			$ccd->preserveWhiteSpace = false;
			$ccd->formatOutput       = true;
			$ccd->loadXML($xslt->transformToXML($xmlDom));
			$ccd->save($_SESSION['site']['temp']['path'] . '/ccdDebug.xml');
			if($raw == 'yes') {
				// simply send the xml to a textarea (nice debugging tool)
				echo '<textarea rows="35" cols="500" style="width:100%; height:99%" readonly>';
				echo $ccd->saveXml();
				echo '</textarea>';
				return;
			}
			$ss = new DOMDocument();
			$ss->load($_SESSION['root'] . '/lib/ccr/stylesheet/cda.xsl');
			$xslt->importStyleSheet($ss);
			print $xslt->transformToXML($ccd);
		}else{
			print 'PHP Error Class \'XsltProcessor\' not found. <a href="http://php.net/manual/en/xsl.installation.php" target="_blank">[more info]</a>';
		}
	}

	function sourceType($uuid)
	{
		$e_Source = $this->ccr->createElement('Source');
		$e_Actor  = $this->ccr->createElement('Actor');
		$e_Source->appendChild($e_Actor);
		$e_ActorID = $this->ccr->createElement('ActorID', $uuid);
		$e_Actor->appendChild($e_ActorID);
		return $e_Source;
	}

	function displayError($message)
	{
		echo '<script type="text/javascript">alert("' . addslashes($message) . '");</script>';
	}

	function createHybridXML()
	{
		// save the raw xml
		$main_xml = $this->ccr->saveXml();
		// save the stylesheet
		$main_stylesheet = file_get_contents('stylesheet/ccr.xsl');
		// replace stylesheet link in raw xml file
		$substitute_string = '<?xml-stylesheet type="text/xsl" href="#style1"?><!DOCTYPE ContinuityOfCareRecord [ <!ATTLIST xsl:stylesheet id ID #REQUIRED> ]>';
		$replace_string    = '<?xml-stylesheet type="text/xsl" href="stylesheet/ccr.xsl"?>';
		$main_xml          = str_replace($replace_string, $substitute_string, $main_xml);
		// remove redundant xml declaration from stylesheet
		$replace_string  = '<?xml version="1.0" encoding="UTF-8"?>';
		$main_stylesheet = str_replace($replace_string, '', $main_stylesheet);
		// embed the stylesheet in the raw xml file
		$replace_string  = '<ContinuityOfCareRecord xmlns="urn:astm-org:CCR">';
		$main_stylesheet = $replace_string . $main_stylesheet;
		$main_xml        = str_replace($replace_string, $main_stylesheet, $main_xml);
		// insert style1 id into the stylesheet parameter
		$substitute_string = 'xsl:stylesheet id="style1" exclude-result-prefixes';
		$replace_string    = 'xsl:stylesheet exclude-result-prefixes';
		$main_xml          = str_replace($replace_string, $substitute_string, $main_xml);
		// prepare the filename to use
		//   LASTNAME-FIRSTNAME-PID-DATESTAMP-ccr.xml
		$main_filename = $this->getReportFilename() . "-ccr.xml";
		// send the output as a file to the user
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename=' . $main_filename);
		echo $main_xml;
	}

	function createHeader($e_ccr)
	{
		$e_ccrDocObjID = $this->ccr->createElement('CCRDocumentObjectID', $this->getUuid());
		$e_ccr->appendChild($e_ccrDocObjID);
		$e_Language = $this->ccr->createElement('Language');
		$e_ccr->appendChild($e_Language);
		$e_Text = $this->ccr->createElement('Text', 'English');
		$e_Language->appendChild($e_Text);
		$e_Version = $this->ccr->createElement('Version', 'V1.0');
		$e_ccr->appendChild($e_Version);
		$e_dateTime = $this->ccr->createElement('DateTime');
		$e_ccr->appendChild($e_dateTime);
		$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', date('Y-m-d\TH:i:s\Z'));
		$e_dateTime->appendChild($e_ExactDateTime);
		$e_patient = $this->ccr->createElement('Patient');
		$e_ccr->appendChild($e_patient);
		//$e_ActorID = $this->ccr->createElement('ActorID', $row['patient_id']);
		$e_ActorID = $this->ccr->createElement('ActorID', 'A1234'); // This value and ActorID in createCCRActor.php should be same.
		$e_patient->appendChild($e_ActorID);
		//Header From:
		$e_From = $this->ccr->createElement('From');
		$e_ccr->appendChild($e_From);
		$e_ActorLink = $this->ccr->createElement('ActorLink');
		$e_From->appendChild($e_ActorLink);
		$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
		$e_ActorLink->appendChild($e_ActorID);
		$e_ActorRole = $this->ccr->createElement('ActorRole');
		$e_ActorLink->appendChild($e_ActorRole);
		$e_Text = $this->ccr->createElement('Text', 'author');
		$e_ActorRole->appendChild($e_Text);
		//Header To:
		$e_To = $this->ccr->createElement('To');
		$e_ccr->appendChild($e_To);
		$e_ActorLink = $this->ccr->createElement('ActorLink');
		$e_To->appendChild($e_ActorLink);
		//$e_ActorID = $this->ccr->createElement('ActorID', $row['patient_id']);
		$e_ActorID = $this->ccr->createElement('ActorID', 'A1234');
		$e_ActorLink->appendChild($e_ActorID);
		$e_ActorRole = $this->ccr->createElement('ActorRole');
		$e_ActorLink->appendChild($e_ActorRole);
		$e_Text = $this->ccr->createElement('Text', 'patient');
		$e_ActorRole->appendChild($e_Text);
		//Header Purpose:
		$e_Purpose = $this->ccr->createElement('Purpose');
		$e_ccr->appendChild($e_Purpose);
		$e_Description = $this->ccr->createElement('Description');
		$e_Purpose->appendChild($e_Description);
		$e_Text = $this->ccr->createElement('Text', 'Summary of patient information');
		$e_Description->appendChild($e_Text);
	}

	function createProblem($e_Problems)
	{
		$data = $this->medical->getPatientProblemsByPid($this->pid);
		$pCount = 0;
		foreach($data AS $row) {
			$pCount++;
			$e_Problem = $this->ccr->createElement('Problem');
			$e_Problems->appendChild($e_Problem);
			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', 'PROB' . $pCount);
			$e_Problem->appendChild($e_CCRDataObjectID);
			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_Problem->appendChild($e_DateTime);
			$date            = new DateTime($row['date']);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
			$e_DateTime->appendChild($e_ExactDateTime);
			$e_IDs = $this->ccr->createElement('IDs');
			$e_Problem->appendChild($e_IDs);
			$e_ID = $this->ccr->createElement('ID', $row['pid']);
			$e_IDs->appendChild($e_ID);
			$e_IDs->appendChild($this->sourceType($this->sourceID));
			$e_Type = $this->ccr->createElement('Type');
			$e_Problem->appendChild($e_Type);
			$e_Text = $this->ccr->createElement('Text', 'Problem'); // Changed to pass through validator, Problem type must be one of the required string values: Problem, Condition, Diagnosis, Symptom, Finding, Complaint, Functional Limitation.
			//$e_Text = $ccr->createElement('Text', $row['prob_title']);
			$e_Type->appendChild($e_Text);
			$e_Description = $this->ccr->createElement('Description');
			$e_Problem->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', 'lookup_code_descriptions');
			//			$e_Text = $this->ccr->createElement('Text', lookup_code_descriptions($row['diagnosis']));
			$e_Description->appendChild($e_Text);
			$e_Code = $this->ccr->createElement('Code');
			$e_Description->appendChild($e_Code);
			$e_Value = $this->ccr->createElement('Value', $row['diagnosis']);
			$e_Code->appendChild($e_Value);
			$e_Value = $this->ccr->createElement('CodingSystem', 'ICD9-CM');
			$e_Code->appendChild($e_Value);
			$e_Status = $this->ccr->createElement('Status');
			$e_Problem->appendChild($e_Status);
			// $e_Text = $this->ccr->createElement('Text', $row['outcome']);
			$e_Text = $this->ccr->createElement('Text', 'Active');
			$e_Status->appendChild($e_Text);
			//$e_CommentID = $ccr->createElement('CommentID', $row['comments']);
			//$e_Problem->appendChild($e_CommentID);
			$e_Source = $this->ccr->createElement('Source');
			$e_Actor  = $this->ccr->createElement('Actor');
			$e_Source->appendChild($e_Actor);
			$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
			$e_Actor->appendChild($e_ActorID);
			$e_Problem->appendChild($e_Source);
			$e_CommentID = $this->ccr->createElement('CommentID', $row['comments']);
			$e_Problem->appendChild($e_CommentID);
			$e_Episodes = $this->ccr->createElement('Episodes');
			$e_Problem->appendChild($e_Episodes);
			$e_Number = $this->ccr->createElement('Number');
			$e_Episodes->appendChild($e_Number);
			$e_Episode = $this->ccr->createElement('Episode');
			$e_Episodes->appendChild($e_Episode);
			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', 'EP' . $pCount);
			$e_Episode->appendChild($e_CCRDataObjectID);
			$e_Episode->appendChild($this->sourceType($this->sourceID));
			$e_Episodes->appendChild($this->sourceType($this->sourceID));
			$e_HealthStatus = $this->ccr->createElement('HealthStatus');
			$e_Problem->appendChild($e_HealthStatus);
			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_HealthStatus->appendChild($e_DateTime);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime');
			$e_DateTime->appendChild($e_ExactDateTime);
			$e_Description = $this->ccr->createElement('Description');
			$e_HealthStatus->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', $row['reason']);
			$e_Description->appendChild($e_Text);
			$e_HealthStatus->appendChild($this->sourceType($this->sourceID));
		}
	}

	function createAlerts($e_Alerts)
	{
		//$result = getAlertData();
		$data = array(
			array('date' => '2004-12-23 00:00:00', 'pid' => 1, 'type' => 'alert type', 'alert_title' => 'alert title', 'code_text' => 'code text', 'diagnosis' => 200.12, 'outcome' => 'outcome', 'reaction' => 'reaction'),
			array('date' => '2004-12-23 00:00:00', 'pid' => 1, 'type' => 'alert type', 'alert_title' => 'alert title', 'code_text' => 'code text', 'diagnosis' => 210.17, 'outcome' => 'outcome', 'reaction' => 'reaction'),
			array('date' => '2004-12-23 00:00:00', 'pid' => 1, 'type' => 'alert type', 'alert_title' => 'alert title', 'code_text' => 'code text', 'diagnosis' => 234.11, 'outcome' => 'outcome', 'reaction' => 'reaction')
		);
		foreach($data AS $row) {
			//while ($row = sqlFetchArray($result)) {
			$e_Alert = $this->ccr->createElement('Alert');
			$e_Alerts->appendChild($e_Alert);
			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid());
			$e_Alert->appendChild($e_CCRDataObjectID);
			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_Alert->appendChild($e_DateTime);
			$date            = new DateTime($row['date']);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
			$e_DateTime->appendChild($e_ExactDateTime);
			$e_IDs = $this->ccr->createElement('IDs');
			$e_Alert->appendChild($e_IDs);
			$e_ID = $this->ccr->createElement('ID', $row['pid']);
			$e_IDs->appendChild($e_ID);
			$e_IDs->appendChild($this->sourceType($this->sourceID));
			$e_Type = $this->ccr->createElement('Type');
			$e_Alert->appendChild($e_Type);
			$e_Text = $this->ccr->createElement('Text', $row['type'] . '-' . $row['alert_title']);
			$e_Type->appendChild($e_Text);
			$e_Description = $this->ccr->createElement('Description');
			$e_Alert->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', $row['code_text']);
			$e_Description->appendChild($e_Text);
			$e_Code = $this->ccr->createElement('Code');
			$e_Description->appendChild($e_Code);
			$e_Value = $this->ccr->createElement('Value', $row['diagnosis']);
			$e_Code->appendChild($e_Value);
			$e_Alert->appendChild($this->sourceType($this->sourceID));
			$e_Agent = $this->ccr->createElement('Agent');
			$e_Alert->appendChild($e_Agent);
			$e_EnvironmentalAgents = $this->ccr->createElement('EnvironmentalAgents');
			$e_Agent->appendChild($e_EnvironmentalAgents);
			$e_EnvironmentalAgent = $this->ccr->createElement('EnvironmentalAgent');
			$e_EnvironmentalAgents->appendChild($e_EnvironmentalAgent);
			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid());
			$e_EnvironmentalAgent->appendChild($e_CCRDataObjectID);
			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_EnvironmentalAgent->appendChild($e_DateTime);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $row['date']);
			$e_DateTime->appendChild($e_ExactDateTime);
			$e_Description = $this->ccr->createElement('Description');
			$e_EnvironmentalAgent->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', $row['alert_title']);
			$e_Description->appendChild($e_Text);
			$e_Code = $this->ccr->createElement('Code');
			$e_Description->appendChild($e_Code);
			$e_Value = $this->ccr->createElement('Value'); //,$row['codetext']
			$e_Code->appendChild($e_Value);
			$e_Status = $this->ccr->createElement('Status');
			$e_EnvironmentalAgent->appendChild($e_Status);
			$e_Text = $this->ccr->createElement('Text', $row['outcome']);
			$e_Status->appendChild($e_Text);
			$e_EnvironmentalAgent->appendChild($this->sourceType($this->sourceID));
			$e_Reaction = $this->ccr->createElement('Reaction');
			$e_Alert->appendChild($e_Reaction);
			$e_Description = $this->ccr->createElement('Description');
			$e_Reaction->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', $row['reaction']);
			$e_Description->appendChild($e_Text);
			$e_Status = $this->ccr->createElement('Status');
			$e_Reaction->appendChild($e_Status);
			$e_Text = $this->ccr->createElement('Text', 'None');
			$e_Status->appendChild($e_Text);

		}

	}

	function createMedications($e_Medications)
	{
		$data = $this->medical->getPatientMedicationsByPatientID($this->pid);
		foreach($data AS $row) {
			$e_Medication = $this->ccr->createElement('Medication');
			$e_Medications->appendChild($e_Medication);
			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid());
			$e_Medication->appendChild($e_CCRDataObjectID);
			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_Medication->appendChild($e_DateTime);
			$date            = date_create($row['create_date']);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
			$e_DateTime->appendChild($e_ExactDateTime);
			$e_Type = $this->ccr->createElement('Type');
			$e_Medication->appendChild($e_Type);
			$e_Text = $this->ccr->createElement('Text', 'Medication');
			$e_Type->appendChild($e_Text);
			$e_Status = $this->ccr->createElement('Status');
			$e_Medication->appendChild($e_Status);
			$e_Text = $this->ccr->createElement('Text', ($row['end_date'] == null || $row['end_date'] == '' ? 'Active' : 'Prior History No Longer Active' ));
			$e_Status->appendChild($e_Text);
			$e_Medication->appendChild($this->sourceType($this->sourceID));
			$e_Product = $this->ccr->createElement('Product');
			$e_Medication->appendChild($e_Product);
			$e_ProductName = $this->ccr->createElement('ProductName');
			$e_Product->appendChild($e_ProductName);
			$e_Text = $this->ccr->createElement('Text', $row['STR']);
			$e_ProductName->appendChild(clone $e_Text);
			$e_Code = $this->ccr->createElement('Code');
			$e_ProductName->appendChild($e_Code);
			$e_Value = $this->ccr->createElement('Value', $row['CODE']);
			$e_Code->appendChild($e_Value);
			$e_Value = $this->ccr->createElement('CodingSystem', 'RxNorm');
			$e_Code->appendChild($e_Value);
			$e_Strength = $this->ccr->createElement('Strength');
			$e_Product->appendChild($e_Strength);
			$e_Value = $this->ccr->createElement('Value', $row['dose']);
			$e_Strength->appendChild($e_Value);
			$e_Units = $this->ccr->createElement('Units');
			$e_Strength->appendChild($e_Units);
			$e_Unit = $this->ccr->createElement('Unit',' - ');
			$e_Units->appendChild($e_Unit);
			$e_Form = $this->ccr->createElement('Form');
			$e_Product->appendChild($e_Form);
			$e_Text = $this->ccr->createElement('Text', $row['form']);
			$e_Form->appendChild($e_Text);
			$e_Quantity = $this->ccr->createElement('Quantity');
			$e_Medication->appendChild($e_Quantity);
			$e_Value = $this->ccr->createElement('Value', $row['dispense']);
			$e_Quantity->appendChild($e_Value);
			$e_Units = $this->ccr->createElement('Units');
			$e_Quantity->appendChild($e_Units);
			$e_Unit = $this->ccr->createElement('Unit', ' - ');
			$e_Units->appendChild($e_Unit);
			$e_Directions = $this->ccr->createElement('Directions');
			$e_Medication->appendChild($e_Directions);
			$e_Direction = $this->ccr->createElement('Direction');
			$e_Directions->appendChild($e_Direction);
			$e_Description = $this->ccr->createElement('Description');
			$e_Direction->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', $row['prescription_often']);
			$e_Description->appendChild(clone $e_Text);
			$e_Route = $this->ccr->createElement('Route');
			$e_Direction->appendChild($e_Route);
			$e_Text = $this->ccr->createElement('Text', $row['route']);
			$e_Route->appendChild($e_Text);
			$e_Site = $this->ccr->createElement('Site');
			$e_Direction->appendChild($e_Site);
			$e_Text = $this->ccr->createElement('Text', '????');
			$e_Site->appendChild($e_Text);
			$e_PatientInstructions = $this->ccr->createElement('PatientInstructions');
			$e_Medication->appendChild($e_PatientInstructions);
			$e_Instruction = $this->ccr->createElement('Instruction');
			$e_PatientInstructions->appendChild($e_Instruction);
			$e_Text = $this->ccr->createElement('Text', $row['prescription_often']);
			$e_Instruction->appendChild($e_Text);
			$e_Refills = $this->ccr->createElement('Refills');
			$e_Medication->appendChild($e_Refills);
			$e_Refill = $this->ccr->createElement('Refill');
			$e_Refills->appendChild($e_Refill);
			$e_Number = $this->ccr->createElement('Number', $row['refill']);
			$e_Refill->appendChild($e_Number);
		}
	}

	function createImmunizations($e_Immunizations)
	{
		$data = $this->medical->getPatientImmunizationsByPid($this->pid);
		foreach($data AS $row) {
			$e_Immunization = $this->ccr->createElement('Immunization');
			$e_Immunizations->appendChild($e_Immunization);

			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid());
			$e_Immunization->appendChild($e_CCRDataObjectID);

			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_Immunization->appendChild($e_DateTime);
			$date = date_create($row['administered_date']);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
			$e_DateTime->appendChild($e_ExactDateTime);

			$e_Type = $this->ccr->createElement('Type');
			$e_Immunization->appendChild($e_Type);
			$e_Text = $this->ccr->createElement('Text', 'Immunization');
			$e_Type->appendChild($e_Text);

			$e_Status = $this->ccr->createElement('Status');
			$e_Immunization->appendChild($e_Status);
			$e_Text = $this->ccr->createElement('Text','ACTIVE');
			$e_Status->appendChild($e_Text);

			$e_Immunization->appendChild($this->sourceType($this->sourceID));

			$e_Product = $this->ccr->createElement('Product');
			$e_Immunization->appendChild($e_Product);
			$e_ProductName = $this->ccr->createElement('ProductName');
			$e_Product->appendChild($e_ProductName);
			$e_Text = $this->ccr->createElement('Text',$row['immunization_name']);
			$e_ProductName->appendChild($e_Text);
			$e_Code = $this->ccr->createElement('Code',$row['immunization_id']);
			$e_ProductName->appendChild($e_Code);

			$e_Directions = $this->ccr->createElement('Directions');
			$e_Immunization->appendChild($e_Directions);
			$e_Direction = $this->ccr->createElement('Direction');
			$e_Directions->appendChild($e_Direction);
			$e_Description = $this->ccr->createElement('Description');



			$e_Direction->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text',$row['note']);
			$e_Description->appendChild($e_Text);
			$e_Code = $this->ccr->createElement('Code');
			$e_Description->appendChild($e_Code);
			$e_Value = $this->ccr->createElement('Value', 'None');
			$e_Code->appendChild($e_Value);

//			$e_Immunization = $this->ccr->createElement('Immunization');
//			$e_Immunizations->appendChild($e_Immunization);
//			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid());
//			$e_Immunization->appendChild($e_CCRDataObjectID);
//
//			$e_DateTime = $this->ccr->createElement('DateTime');
//			$e_Immunization->appendChild($e_DateTime);
//			$date            = date_create($row['administered_date']);
//			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
//			$e_DateTime->appendChild($e_ExactDateTime);
//			$e_Type = $this->ccr->createElement('Type');
//			$e_Immunization->appendChild($e_Type);
//			$e_Text = $this->ccr->createElement('Text', 'Immunization');
//			$e_Type->appendChild($e_Text);
//			$e_Status = $this->ccr->createElement('Status');
//			$e_Immunization->appendChild($e_Status);
//			$e_Text = $this->ccr->createElement('Text', 'ACTIVE');
//			$e_Status->appendChild($e_Text);
//			$e_Immunization->appendChild($this->sourceType($this->sourceID));
//
//
//			$e_Product = $this->ccr->createElement('Product');
//			$e_Immunization->appendChild($e_Product);
//			$e_ProductName = $this->ccr->createElement('ProductName');
//			$e_Product->appendChild($e_ProductName);
//			$e_Text = $this->ccr->createElement('Text', $row['immunization_name']);
//			$e_ProductName->appendChild($e_Text);
//
//
//			$e_Code = $this->ccr->createElement('ProductCode');
//			$e_Product->appendChild($e_Code);
//			$e_Text = $this->ccr->createElement('Text', 'sdsdsd');
//			$e_Code->appendChild($e_Text);
//
//
//
//
//			$e_Directions = $this->ccr->createElement('Directions');
//			$e_Immunization->appendChild($e_Directions);
//			$e_Direction = $this->ccr->createElement('Direction');
//			$e_Directions->appendChild($e_Direction);
//			$e_Description = $this->ccr->createElement('Description');
//			$e_Direction->appendChild($e_Description);
//			$e_Text = $this->ccr->createElement('Text', $row['note']);
//			$e_Description->appendChild($e_Text);
//			$e_Code = $this->ccr->createElement('Code');
//			$e_Description->appendChild($e_Code);
//			$e_Value = $this->ccr->createElement('Value', 'None');
//			$e_Code->appendChild($e_Value);

		}
	}

	function createResults($e_Results)
	{
		//$result = getResultData();
		//$row = sqlFetchArray($result);
		$data = array(
			array(
				'date'                  => '2004-12-23 00:00:00',
				'pid'                   => 1,
				'name'                  => 'Title test',
				'result'                => 'note text',
				'range'                 => 'note text',
				'abnormal'              => 'note text'
			),
			array(
				'date'                  => '2004-12-23 00:00:00',
				'pid'                   => 1,
				'name'                  => 'Title test',
				'result'                => 'note text',
				'range'                 => 'note text',
				'abnormal'              => 'note text'
			),
		);
		foreach($data AS $row) {
			$e_Result = $this->ccr->createElement('Result');
			$e_Results->appendChild($e_Result);
			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid()); //, $row['immunization_id']);
			$e_Result->appendChild($e_CCRDataObjectID);
			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_Result->appendChild($e_DateTime);
			$date            = date_create($row['date']);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
			$e_DateTime->appendChild($e_ExactDateTime);
			$e_IDs = $this->ccr->createElement('IDs');
			$e_Result->appendChild($e_IDs);
			$e_ID = $this->ccr->createElement('ID');
			$e_IDs->appendChild($e_ID);
			$e_IDs->appendChild($this->sourceType($this->authorID));
			$e_Source = $this->ccr->createElement('Source');
			$e_Result->appendChild($e_Source);
			$e_Actor = $this->ccr->createElement('Actor');
			$e_Source->appendChild($e_Actor);
			$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
			//$e_ActorID = $this->ccr->createElement('ActorID',${"labID{$row['lab']}"});
			$e_Actor->appendChild($e_ActorID);
			$e_Test = $this->ccr->createElement('Test');
			$e_Result->appendChild($e_Test);
			$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid());
			$e_Test->appendChild($e_CCRDataObjectID);
			$e_DateTime = $this->ccr->createElement('DateTime');
			$e_Test->appendChild($e_DateTime);
			$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
			$e_DateTime->appendChild($e_ExactDateTime);
			$e_Type = $this->ccr->createElement('Type');
			$e_Test->appendChild($e_Type);
			$e_Text = $this->ccr->createElement('Text', 'Observation');
			$e_Type->appendChild($e_Text);
			$e_Description = $this->ccr->createElement('Description');
			$e_Test->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', $row['name']);
			$e_Description->appendChild($e_Text);
			$e_Code = $this->ccr->createElement('Code');
			$e_Description->appendChild($e_Code);
			$e_Value = $this->ccr->createElement('Value', 'Value');
			$e_Code->appendChild($e_Value);
			$e_Source = $this->ccr->createElement('Source');
			$e_Test->appendChild($e_Source);
			$e_Actor = $this->ccr->createElement('Actor');
			$e_Source->appendChild($e_Actor);
			$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
			$e_Actor->appendChild($e_ActorID);
			$e_TestResult = $this->ccr->createElement('TestResult');
			$e_Test->appendChild($e_TestResult);
			$e_Value = $this->ccr->createElement('Value', $row['result']);
			$e_TestResult->appendChild($e_Value);
			$e_Code = $this->ccr->createElement('Code');
			$e_TestResult->appendChild($e_Code);
			$e_Value = $this->ccr->createElement('Value', 'Value');
			$e_Code->appendChild($e_Value);
			$e_Description = $this->ccr->createElement('Description');
			$e_TestResult->appendChild($e_Description);
			$e_Text = $this->ccr->createElement('Text', $row['result']);
			$e_Description->appendChild($e_Text);
			//if($row['abnormal'] == '' ) {
			$e_NormalResult = $this->ccr->createElement('NormalResult');
			$e_Test->appendChild($e_NormalResult);
			$e_Normal = $this->ccr->createElement('Normal');
			$e_NormalResult->appendChild($e_Normal);
			$e_Value = $this->ccr->createElement('Value', $row['range']);
			$e_Normal->appendChild($e_Value);
			$e_Units = $this->ccr->createElement('Units');
			$e_Normal->appendChild($e_Units);
			$e_Unit = $this->ccr->createElement('Unit', 'Test Unit');
			$e_Units->appendChild($e_Unit);
			$e_Source = $this->ccr->createElement('Source');
			$e_Normal->appendChild($e_Source);
			$e_Actor = $this->ccr->createElement('Actor');
			$e_Source->appendChild($e_Actor);
			$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
			$e_Actor->appendChild($e_ActorID);
			//} else {
			$e_Flag = $this->ccr->createElement('Flag');
			$e_Test->appendChild($e_Flag);
			$e_Text = $this->ccr->createElement('Text', $row['abnormal']);
			$e_Flag->appendChild($e_Text);
			//}
			//$e_Test = $this->ccr->createElement('Test');
			//$e_Result->appendChild($e_Test);
			//
			//$e_CCRDataObjectID = $this->ccr->createElement('CCRDataObjectID', $this->getUuid());
			//$e_Test->appendChild($e_CCRDataObjectID);
			//
			//$e_DateTime = $this->ccr->createElement('DateTime');
			//$e_Test->appendChild($e_DateTime);
			//
			//$e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $date->format('Y-m-d\TH:i:s\Z'));
			//$e_DateTime->appendChild($e_ExactDateTime);
			//
			//$e_Type = $this->ccr->createElement('Type');
			//$e_Test->appendChild($e_Type);
			//
			//$e_Text = $this->ccr->createElement('Text', 'Observation');
			//$e_Type->appendChild($e_Text);
			//
			//
			//$e_Description = $this->ccr->createElement('Description' );
			//$e_Test->appendChild($e_Description);
			//
			//$e_Text = $this->ccr->createElement('Text', 'Range');
			//$e_Description->appendChild($e_Text);
			//
			//$e_Code = $this->ccr->createElement('Code');
			//$e_Description->appendChild($e_Code);
			//
			//$e_Value = $this->ccr->createElement('Value', 'None');
			//$e_Code->appendChild($e_Value);
			//
			//$e_Test->appendChild($this->sourceType($this->ccr, $this->authorID));
			//
			//$e_TestResult = $this->ccr->createElement('TestResult' );
			//$e_Test->appendChild($e_TestResult);
			//
			//$e_Value = $this->ccr->createElement('Value', '1.0');
			//$e_TestResult->appendChild($e_Value);
			//
			//$e_Code = $this->ccr->createElement('Code' );
			//$e_TestResult->appendChild($e_Code);
			//
			//$e_Value = $this->ccr->createElement('Value', 'Test 01 Code');
			//$e_Code->appendChild($e_Value);
			//
			//$e_Description = $this->ccr->createElement('Description' );
			//$e_TestResult->appendChild($e_Description);
			//
			//$e_Text = $this->ccr->createElement('Text', $row['range']);
			//$e_Description->appendChild($e_Text);
			//
			//
			//if($row['abnormal'] == '' ) {
			//	$e_NormalResult = $this->ccr->createElement('NormalResult');
			//	$e_Test->appendChild($e_NormalResult);
			//} else {
			//	$e_Flag = $this->ccr->createElement('Flag');
			//	$e_Test->appendChild($e_Flag);
			//
			//	$e_Text = $this->ccr->createElement('Text');
			//	$e_Flag->appendChild($e_Text);
			//
			//}
		}
	}

	function createActors($e_Actors)
	{
        $p = $this->patient->getPatientDemographicDataByPid($this->pid);

        $e_Actor = $this->ccr->createElement('Actor');
        $e_Actors->appendChild($e_Actor);
        $e_ActorObjectID = $this->ccr->createElement('ActorObjectID', 'A1234'); // Refer createCCRHeader.php
        $e_Actor->appendChild($e_ActorObjectID);
        $e_Person = $this->ccr->createElement('Person');
        $e_Actor->appendChild($e_Person);
        $e_Name = $this->ccr->createElement('Name');
        $e_Person->appendChild($e_Name);
        $e_CurrentName = $this->ccr->createElement('CurrentName');
        $e_Name->appendChild($e_CurrentName);
        $e_Given = $this->ccr->createElement('Given', $p['fname']);
        $e_CurrentName->appendChild($e_Given);
        $e_Family = $this->ccr->createElement('Family', $p['lname']);
        $e_CurrentName->appendChild($e_Family);
        $e_Suffix = $this->ccr->createElement('Suffix');
        $e_CurrentName->appendChild($e_Suffix);
        $e_DateOfBirth = $this->ccr->createElement('DateOfBirth');
        $e_Person->appendChild($e_DateOfBirth);
        $dob             = date_create($p['DOB']);
        $e_ExactDateTime = $this->ccr->createElement('ExactDateTime', $dob->format('Y-m-d\TH:i:s\Z'));
        $e_DateOfBirth->appendChild($e_ExactDateTime);
        $e_Gender = $this->ccr->createElement('Gender');
        $e_Person->appendChild($e_Gender);
        $e_Text = $this->ccr->createElement('Text', $p['sex']);
        $e_Gender->appendChild($e_Text);
        $e_Code = $this->ccr->createElement('Code');
        $e_Gender->appendChild($e_Code);
        $e_Value = $this->ccr->createElement('Value');
        $e_Code->appendChild($e_Value);
        $e_IDs = $this->ccr->createElement('IDs');
        $e_Actor->appendChild($e_IDs);
        $e_Type = $this->ccr->createElement('Type');
        $e_IDs->appendChild($e_Type);
        $e_Text = $this->ccr->createElement('Text', 'Patient ID');
        $e_Type->appendChild($e_Text);
        $e_ID = $this->ccr->createElement('ID', $p['pid']);
        $e_IDs->appendChild($e_ID);
        $e_Source = $this->ccr->createElement('Source');
        $e_IDs->appendChild($e_Source);
        $e_SourceActor = $this->ccr->createElement('Actor');
        $e_Source->appendChild($e_SourceActor);
        $e_ActorID = $this->ccr->createElement('ActorID', $this->getUuid());
        $e_SourceActor->appendChild($e_ActorID);
        // address
        $e_Address = $this->ccr->createElement('Address');
        $e_Actor->appendChild($e_Address);
        $e_Type = $this->ccr->createElement('Type');
        $e_Address->appendChild($e_Type);
        $e_Text = $this->ccr->createElement('Text', 'H');
        $e_Type->appendChild($e_Text);
        $e_Line1 = $this->ccr->createElement('Line1', $p['address']);
        $e_Address->appendChild($e_Line1);
        $e_Line2 = $this->ccr->createElement('Line2');
        $e_Address->appendChild($e_Line1);
        $e_City = $this->ccr->createElement('City', $p['city']);
        $e_Address->appendChild($e_City);
        $e_State = $this->ccr->createElement('State', $p['state']);
        $e_Address->appendChild($e_State);
        $e_PostalCode = $this->ccr->createElement('PostalCode', $p['zipcode']);
        $e_Address->appendChild($e_PostalCode);
        $e_Telephone = $this->ccr->createElement('Telephone');
        $e_Actor->appendChild($e_Telephone);
        $e_Value = $this->ccr->createElement('Value', $p['home_phone']);
        $e_Telephone->appendChild($e_Value);
        $e_Source = $this->ccr->createElement('Source');
        $e_Actor->appendChild($e_Source);
        $e_Actor = $this->ccr->createElement('Actor');
        $e_Source->appendChild($e_Actor);
        $e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
        $e_Actor->appendChild($e_ActorID);

		$informationData = array(
			'facility' => 'facility',
			'street' => 'street',
			'city' => 'city',
			'state' => 'state',
			'postal_code' => 'postal_code',
			'phone' => 'phone'
		);
		//////// Actor Information Systems
		$e_Actor = $this->ccr->createElement('Actor');
		$e_Actors->appendChild($e_Actor);
		$e_ActorObjectID = $this->ccr->createElement('ActorObjectID', $this->authorID);
		$e_Actor->appendChild($e_ActorObjectID);
		$e_InformationSystem = $this->ccr->createElement('InformationSystem');
		$e_Actor->appendChild($e_InformationSystem);
		$e_Name = $this->ccr->createElement('Name', $informationData['facility']);
		$e_InformationSystem->appendChild($e_Name);
		$e_Type = $this->ccr->createElement('Type', 'Facility');
		$e_InformationSystem->appendChild($e_Type);
		$e_IDs = $this->ccr->createElement('IDs');
		$e_Actor->appendChild($e_IDs);
		$e_Type = $this->ccr->createElement('Type');
		$e_IDs->appendChild($e_Type);
		$e_Text = $this->ccr->createElement('Text', '');
		$e_Type->appendChild($e_Text);
		$e_ID = $this->ccr->createElement('ID', '');
		$e_IDs->appendChild($e_ID);
		$e_Source = $this->ccr->createElement('Source');
		$e_IDs->appendChild($e_Source);
		$e_SourceActor = $this->ccr->createElement('Actor');
		$e_Source->appendChild($e_SourceActor);
		$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
		$e_SourceActor->appendChild($e_ActorID);
		$e_Address = $this->ccr->createElement('Address');
		$e_Actor->appendChild($e_Address);
		$e_Type = $this->ccr->createElement('Type');
		$e_Address->appendChild($e_Type);
		$e_Text = $this->ccr->createElement('Text', 'WP');
		$e_Type->appendChild($e_Text);
		$e_Line1 = $this->ccr->createElement('Line1', $informationData['street']);
		$e_Address->appendChild($e_Line1);
		$e_Line2 = $this->ccr->createElement('Line2');
		$e_Address->appendChild($e_Line1);
		$e_City = $this->ccr->createElement('City', $informationData['city']);
		$e_Address->appendChild($e_City);
		$e_State = $this->ccr->createElement('State', $informationData['state'] . ' ');
		$e_Address->appendChild($e_State);
		$e_PostalCode = $this->ccr->createElement('PostalCode', $informationData['postal_code']);
		$e_Address->appendChild($e_PostalCode);
		$e_Telephone = $this->ccr->createElement('Telephone');
		$e_Actor->appendChild($e_Telephone);
		$e_Phone = $this->ccr->createElement('Value', $informationData['phone']);
		$e_Telephone->appendChild($e_Phone);
		$e_Source = $this->ccr->createElement('Source');
		$e_Actor->appendChild($e_Source);
		$e_Actor = $this->ccr->createElement('Actor');
		$e_Source->appendChild($e_Actor);
		$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
		$e_Actor->appendChild($e_ActorID);
		//////// Actor Information Systems
		$e_Actor = $this->ccr->createElement('Actor');
		$e_Actors->appendChild($e_Actor);
		$e_ActorObjectID = $this->ccr->createElement('ActorObjectID', $this->gaiaID);
		$e_Actor->appendChild($e_ActorObjectID);
		$e_InformationSystem = $this->ccr->createElement('InformationSystem');
		$e_Actor->appendChild($e_InformationSystem);
		$e_Name = $this->ccr->createElement('Name', 'GEHR');
		$e_InformationSystem->appendChild($e_Name);
		$e_Type = $this->ccr->createElement('Type', 'GaiaEHR');
		$e_InformationSystem->appendChild($e_Type);
		$e_Version = $this->ccr->createElement('Version', '1.x');
		$e_InformationSystem->appendChild($e_Version);
		$e_IDs = $this->ccr->createElement('IDs');
		$e_Actor->appendChild($e_IDs);
		$e_Type = $this->ccr->createElement('Type');
		$e_IDs->appendChild($e_Type);
		$e_Text = $this->ccr->createElement('Text', 'Certification #');
		$e_Type->appendChild($e_Text);
		$e_ID = $this->ccr->createElement('ID', 'NONE');
		$e_IDs->appendChild($e_ID);
		$e_Source = $this->ccr->createElement('Source');
		$e_IDs->appendChild($e_Source);
		$e_SourceActor = $this->ccr->createElement('Actor');
		$e_Source->appendChild($e_SourceActor);
		$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
		$e_SourceActor->appendChild($e_ActorID);
		$e_Address = $this->ccr->createElement('Address');
		$e_Actor->appendChild($e_Address);
		$e_Type = $this->ccr->createElement('Type');
		$e_Address->appendChild($e_Type);
		$e_Text = $this->ccr->createElement('Text', 'WP');
		$e_Type->appendChild($e_Text);
		$e_Line1 = $this->ccr->createElement('Line1', '90 Blvd. Media Luna');
		$e_Address->appendChild($e_Line1);
		$e_Line2 = $this->ccr->createElement('Line2');
		$e_Address->appendChild($e_Line1);
		$e_City = $this->ccr->createElement('City', 'San Juan');
		$e_Address->appendChild($e_City);
		$e_State = $this->ccr->createElement('State', 'PR ');
		$e_Address->appendChild($e_State);
		$e_PostalCode = $this->ccr->createElement('PostalCode', '00987');
		$e_Address->appendChild($e_PostalCode);
		$e_Telephone = $this->ccr->createElement('Telephone');
		$e_Actor->appendChild($e_Telephone);
		$e_Phone = $this->ccr->createElement('Value', '000-000-0000');
		$e_Telephone->appendChild($e_Phone);
		$e_Source = $this->ccr->createElement('Source');
		$e_Actor->appendChild($e_Source);
		$e_Actor = $this->ccr->createElement('Actor');
		$e_Source->appendChild($e_Actor);
		$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
		$e_Actor->appendChild($e_ActorID);
		$labData = array();
		foreach($labData AS $row) {
			$e_Actor = $this->ccr->createElement('Actor');
			$e_Actors->appendChild($e_Actor);
			$e_ActorObjectID = $this->ccr->createElement('ActorObjectID', ${"labID{$row['id']}"});
			$e_Actor->appendChild($e_ActorObjectID);
			$e_InformationSystem = $this->ccr->createElement('InformationSystem');
			$e_Actor->appendChild($e_InformationSystem);
			$e_Name = $this->ccr->createElement('Name', $row['lname'] . " " . $row['fname']);
			$e_InformationSystem->appendChild($e_Name);
			$e_Type = $this->ccr->createElement('Type', 'Lab Service');
			$e_InformationSystem->appendChild($e_Type);
			$e_IDs = $this->ccr->createElement('IDs');
			$e_Actor->appendChild($e_IDs);
			$e_Type = $this->ccr->createElement('Type');
			$e_IDs->appendChild($e_Type);
			$e_Text = $this->ccr->createElement('Text', '');
			$e_Type->appendChild($e_Text);
			$e_ID = $this->ccr->createElement('ID', '');
			$e_IDs->appendChild($e_ID);
			$e_Source = $this->ccr->createElement('Source');
			$e_IDs->appendChild($e_Source);
			$e_SourceActor = $this->ccr->createElement('Actor');
			$e_Source->appendChild($e_SourceActor);
			$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
			$e_SourceActor->appendChild($e_ActorID);
			$e_Address = $this->ccr->createElement('Address');
			$e_Actor->appendChild($e_Address);
			$e_Type = $this->ccr->createElement('Type');
			$e_Address->appendChild($e_Type);
			$e_Text = $this->ccr->createElement('Text', 'WP');
			$e_Type->appendChild($e_Text);
			$e_Line1 = $this->ccr->createElement('Line1', $row['street']);
			$e_Address->appendChild($e_Line1);
			$e_Line2 = $this->ccr->createElement('Line2');
			$e_Address->appendChild($e_Line1);
			$e_City = $this->ccr->createElement('City', $row['city']);
			$e_Address->appendChild($e_City);
			$e_State = $this->ccr->createElement('State', $row['state'] . ' ');
			$e_Address->appendChild($e_State);
			$e_PostalCode = $this->ccr->createElement('PostalCode', $row['zip']);
			$e_Address->appendChild($e_PostalCode);
			$e_Telephone = $this->ccr->createElement('Telephone');
			$e_Actor->appendChild($e_Telephone);
			$e_Phone = $this->ccr->createElement('Value', $row['phone']);
			$e_Telephone->appendChild($e_Phone);
			$e_Source = $this->ccr->createElement('Source');
			$e_Actor->appendChild($e_Source);
			$e_Actor = $this->ccr->createElement('Actor');
			$e_Source->appendChild($e_Actor);
			$e_ActorID = $this->ccr->createElement('ActorID', $this->authorID);
			$e_Actor->appendChild($e_ActorID);
		}

	}

	function getUuid()
	{
		// The field names refer to RFC 4122 section 4.1.2
		return sprintf('A%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
			mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
			mt_rand(0, 65535), // 16 bits for "time_mid"
			mt_rand(0, 4095), // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
			bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
			// 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
			// (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
			// 8 bits for "clk_seq_low"
			mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node"
		);
	}

	function getReportFilename()
	{
		$sql = "SELECT fname, lname, pid FROM patient_data WHERE pid = '$this->pid'";
		$result_filename = 'fulano-detal-1-' . date("mdY", time());
		return $result_filename;
	}
}

if(isset($_REQUEST['action']) && isset($_REQUEST['raw'])){
	$c = new CCR();
	// generate, viewccd
	// yes, hybrid, pure
	$c->createCCR($_REQUEST);
}

