<?php
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
/**
 * This code was originally create by Garden State Health Systems (see credits bellow)
 * And heavily modified by Ernesto J Rodriguez to make it work with GaiaEHR class system.
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
class CCR
{
	private $ccr;
	private $e_ccr;

	private $pid;
	private $authorID;
	private $patientID;
	private $sourceID;
	private $gaiaID;

	function createCCR($action, $raw = "no")
	{
		$this->authorID  = $this->getUuid();
		$this->patientID = $this->getUuid();
		$this->sourceID  = $this->getUuid();
		$this->gaiaID    = $this->getUuid();
		//$result = $this->getActorData();
		//		while($res = sqlFetchArray($result[2])) {
		//			${"labID{$res['id']}"} = $this->getUuid();
		//		}
		$this->ccr    = new DOMDocument('1.0', 'UTF-8');
		$e_styleSheet = $this->ccr->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="stylesheet/ccr.xsl"');
		$this->ccr->appendChild($e_styleSheet);
		$e_ccr = $this->ccr->createElementNS('urn:astm-org:CCR', 'ContinuityOfCareRecord');
		$this->ccr->appendChild($e_ccr);
		/////////////// Header
		$e_Body = $this->ccr->createElement('Body');
		$e_ccr->appendChild($e_Body);

		//		/////////////// Problems
		//		$e_Problems = $this->ccr->createElement('Problems');
		//		require_once("createCCRProblem.php");
		//		$e_Body->appendChild($e_Problems);
		//		/////////////// Alerts
		//		$e_Alerts = $this->ccr->createElement('Alerts');
		//		require_once("createCCRAlerts.php");
		//		$e_Body->appendChild($e_Alerts);
		//		////////////////// Medication
		//		$e_Medications = $this->ccr->createElement('Medications');
		//		require_once("createCCRMedication.php");
		//		$e_Body->appendChild($e_Medications);
		//		///////////////// Immunization
		//		$e_Immunizations = $this->ccr->createElement('Immunizations');
		//		require_once("createCCRImmunization.php");
		//		$e_Body->appendChild($e_Immunizations);
		//		/////////////////// Results
		//		$e_Results = $this->ccr->createElement('Results');
		//		require_once("createCCRResult.php");
		//		$e_Body->appendChild($e_Results);
		/////////////////// Procedures
		//$e_Procedures = $this->ccr->createElement('Procedures');
		//require_once("createCCRProcedure.php");
		//$e_Body->appendChild($e_Procedures);
		//////////////////// Footer
		// $e_VitalSigns = $this->ccr->createElement('VitalSigns');
		// $e_Body->appendChild($e_VitalSigns);
		/////////////// Actors
		//		$e_Actors = $this->ccr->createElement('Actors');
		//		require_once("createCCRActor.php");
		//		$e_ccr->appendChild($e_Actors);
		if($action == "generate") {
			$this->gnrtCCR($this->ccr, $raw);
		}
		if($action == "viewccd") {
			$this->viewCCD($this->ccr, $raw);
		}
	}

	function gnrtCCR($raw = 'no')
	{
		$this->ccr->preserveWhiteSpace = false;
		$this->ccr->formatOutput       = true;
		if($raw == 'yes') {
			// simply send the xml to a textarea (nice debugging tool)
			echo '<textarea rows="35" cols="500" style="width:95%" readonly>';
			echo $this->ccr->saveXml();
			echo "</textarea>";
			return;
		} else {
			if($raw == 'hybrid') {
				// send a file that contains a hybrid file of the raw xml and the xsl stylesheet
				createHybridXML($this->ccr);
			} else {
				if($raw == 'pure') {
					// send a zip file that contains a separate xml data file and xsl stylesheet
					if(!(class_exists('ZipArchive'))) {
						$this->displayError("ERROR: Missing ZipArchive PHP Module");
						return;
					}
					$tempDir = $GLOBALS['temporary_files_dir'];
					$zipName = $tempDir . '/' . getReportFilename() . '-ccr.zip';
					if(file_exists($zipName)) {
						unlink($zipName);
					}
					$zip = new ZipArchive();
					if(!($zip)) {
						$this->displayError('ERROR: Unable to Create Zip Archive.');
						return;
					}
					if($zip->open($zipName, ZIPARCHIVE::CREATE)) {
						$zip->addFile('stylesheet/ccr.xsl", "stylesheet/ccr.xsl');
						$xmlName = $tempDir . '/' . getReportFilename() . '-ccr.xml';
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
						readfile($zipName);
						unlink($zipName);
						unlink($xmlName);
						exit(0);
					} else {
						$this->displayError('ERROR: Unable to Create Zip Archive.');
						return;
					}
				} else {
					header('Content-type: application/xml');
					echo $this->ccr->saveXml();
				}
			}
		}

	}

	function viewCCD($raw = "no")
	{
		$this->ccr->preserveWhiteSpace = false;
		$this->ccr->formatOutput       = true;
		//TODO: work with temp working directory
		$this->ccr->save($_SESSION['root'] . '/temp/ccrForCCD.xml');
		$xmlDom = new DOMDocument();
		$xmlDom->loadXML($this->ccr->saveXML());
		$ccr_ccd = new DOMDocument();
		$ccr_ccd->load($_SESSION['root'] . '/lib/ccr/ccd/ccr_ccd.xsl');
		$xslt = new XSLTProcessor();
		$xslt->importStylesheet($ccr_ccd);
		$ccd                     = new DOMDocument();
		$ccd->preserveWhiteSpace = false;
		$ccd->formatOutput       = true;
		$ccd->loadXML($xslt->transformToXML($xmlDom));
		$ccd->save($_SESSION['root'] . '/temp/ccdDebug.xml');
		if($raw == "yes") {
			// simply send the xml to a textarea (nice debugging tool)
			echo "<textarea rows='35' cols='500' style='width:95%' readonly>";
			echo $ccd->saveXml();
			echo "</textarea>";
			return;
		}
		$ss = new DOMDocument();
		$ss->load($_SESSION['root'] . "/lib/ccr/stylesheet/cda.xsl");
		$xslt->importStyleSheet($ss);
		$html = $xslt->transformToXML($ccd);
		echo $html;

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
		$substitute_string = '<?xml-stylesheet type="text/xsl" href="#style1"?>
	<!DOCTYPE ContinuityOfCareRecord [
	<!ATTLIST xsl:stylesheet id ID #REQUIRED>
	]>
	';
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
		$main_filename = getReportFilename() . "-ccr.xml";
		// send the output as a file to the user
		header("Content-type: text/xml");
		header("Content-Disposition: attachment; filename=" . $main_filename . "");
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

	function getMedicationData()
	{
		global $pid, $set, $start, $end;
		if($set == "on") {
			$sql    = "
	      SELECT prescriptions.date_added ,
	        prescriptions.patient_id,
	        prescriptions.start_date,
	        prescriptions.quantity,
	        prescriptions.interval,
	        prescriptions.note,
	        prescriptions.drug,
	        prescriptions.medication,
	        IF(prescriptions.active=1,'Active','Prior History No Longer Active') AS active,
	        prescriptions.provider_id,
	        prescriptions.size,
		prescriptions.rxnorm_drugcode,
	        IFNULL(prescriptions.refills,0) AS refills,
	        lo2.title AS form,
	        lo.title
	      FROM prescriptions
	      LEFT JOIN list_options AS lo
	      ON lo.list_id = 'drug_units' AND prescriptions.unit = lo.option_id
	      LEFT JOIN list_options AS lo2
	      ON lo2.list_id = 'drug_form' AND prescriptions.form = lo2.option_id
	      WHERE prescriptions.patient_id = ?
	      AND prescriptions.date_added BETWEEN ? AND ?
	      UNION
	      SELECT
	        DATE(DATE) AS date_added,
	        pid AS patient_id,
	        begdate AS start_date,
	        '' AS quantity,
	        '' AS `interval`,
	        comments AS note,
	        title AS drug,
	        '' AS medication,
	        IF((isnull(enddate) OR enddate = '0000-00-00' OR enddate >= CURDATE()),'Active','Prior History No Longer Active') AS active,
	        '' AS provider_id,
	        '' AS size,
	'' AS rxnorm_drugcode,
	        0 AS refills,
	        '' AS form,
	        '' AS title
	      FROM
	        lists
	      WHERE `type` = 'medication'
	        AND pid = ?
	        AND `date` BETWEEN ? AND ?";
			$result = sqlStatement($sql, array($pid, $start, $end, $pid, $start, $end));
		} else {
			$sql    = "
	      SELECT prescriptions.date_added ,
	        prescriptions.patient_id,
	        prescriptions.start_date,
	        prescriptions.quantity,
	        prescriptions.interval,
	        prescriptions.note,
	        prescriptions.drug,
	        prescriptions.medication,
	        IF(prescriptions.active=1,'Active','Prior History No Longer Active') AS active,
	        prescriptions.provider_id,
	        prescriptions.size,
		prescriptions.rxnorm_drugcode,
	        IFNULL(prescriptions.refills,0) AS refills,
	        lo2.title AS form,
	        lo.title
	      FROM prescriptions
	      LEFT JOIN list_options AS lo
	      ON lo.list_id = 'drug_units' AND prescriptions.unit = lo.option_id
	      LEFT JOIN list_options AS lo2
	      ON lo2.list_id = 'drug_form' AND prescriptions.form = lo2.option_id
	      WHERE prescriptions.patient_id = ?
	      UNION
	      SELECT
	        DATE(DATE) AS date_added,
	        pid AS patient_id,
	        begdate AS start_date,
	        '' AS quantity,
	        '' AS `interval`,
	        comments AS note,
	        title AS drug,
	        '' AS medication,
	        IF((isnull(enddate) OR enddate = '0000-00-00' OR enddate >= CURDATE()),'Active','Prior History No Longer Active') AS active,
	        '' AS provider_id,
	        '' AS size,
		'' AS rxnorm_drugcode,
	        0 AS refills,
	        '' AS form,
	        '' AS title
	      FROM
	        lists
	      WHERE `type` = 'medication'
	        AND pid = ?";
			$result = sqlStatement($sql, array($pid, $pid));
		}
		return $result;
	}

	function getImmunizationData()
	{
		global $pid, $set, $start, $end;
		if($set == "on") {
			$sql    = "SELECT
	      immunizations.administered_date,
	      immunizations.patient_id,
	      immunizations.vis_date,
	      immunizations.note,
	      immunizations.immunization_id,
	      immunizations.manufacturer,
	      codes.code_text AS title
	    FROM immunizations
	    LEFT JOIN codes ON immunizations.cvx_code = codes.code
	    LEFT JOIN code_types ON codes.code_type = code_types.ct_id
	    WHERE immunizations.patient_id = ? AND code_types.ct_key = 'CVX'
	    AND create_date BETWEEN ? AND ?";
			$result = sqlStatement($sql, array($pid, $start, $end));
		} else {
			$sql    = "SELECT
	      immunizations.administered_date,
	      immunizations.patient_id,
	      immunizations.vis_date,
	      immunizations.note,
	      immunizations.immunization_id,
	      immunizations.manufacturer,
	      codes.code_text AS title
	    FROM immunizations
	    LEFT JOIN codes ON immunizations.cvx_code = codes.code
	    LEFT JOIN code_types ON codes.code_type = code_types.ct_id
	    WHERE immunizations.patient_id = ? AND code_types.ct_key = 'CVX'";
			$result = sqlStatement($sql, array($pid));
		}
		return $result;
	}

	function getProcedureData()
	{
		global $pid, $set, $start, $end;
		if($set == "on") {
			$sql    = "
	    SELECT
	      lists.title as proc_title,
	      lists.date as `date`,
	      list_options.title as outcome,
	      '' as laterality,
	      '' as body_site,
	      lists.type as `type`,
	      lists.diagnosis as `code`,
	      IF(SUBSTRING(lists.diagnosis,1,LOCATE(':',lists.diagnosis)-1) = 'ICD9','ICD9-CM',SUBSTRING(lists.diagnosis,1,LOCATE(':',lists.diagnosis)-1)) AS coding
	    FROM
	      lists
	      LEFT JOIN issue_encounter
	        ON issue_encounter.list_id = lists.id
	      LEFT JOIN form_encounter
	        ON form_encounter.encounter = issue_encounter.encounter
	      LEFT JOIN facility
	        ON form_encounter.facility_id = facility.id
	      LEFT JOIN users
	        ON form_encounter.provider_id = users.id
	      LEFT JOIN list_options
	        ON lists.outcome = list_options.option_id
	        AND list_options.list_id = 'outcome'
	    WHERE lists.type = 'surgery'
	      AND lists.pid = ?
	      AND lists.date BETWEEN ? AND ?
	    UNION
	    SELECT
	      pt.name as proc_title,
	      prs.date as `date`,
	      '' as outcome,
	      ptt.laterality as laterality,
	      ptt.body_site as body_site,
	      'Lab Order' as `type`,
	      ptt.standard_code as `code`,
	      IF(SUBSTRING(ptt.standard_code,1,LOCATE(':',ptt.standard_code)-1) = 'ICD9','ICD9-CM',SUBSTRING(ptt.standard_code,1,LOCATE(':',ptt.standard_code)-1)) AS coding
	    FROM
	      procedure_result AS prs
	      LEFT JOIN procedure_report AS prp
	        ON prs.procedure_report_id = prp.procedure_report_id
	      LEFT JOIN procedure_order AS po
	        ON prp.procedure_order_id = po.procedure_order_id
	      LEFT JOIN procedure_type AS pt
	        ON prs.procedure_type_id = pt.procedure_type_id
	      LEFT JOIN procedure_type AS ptt
	        ON pt.parent = ptt.procedure_type_id
	        AND ptt.procedure_type = 'ord'
	      LEFT JOIN list_options AS lo
	        ON lo.list_id = 'proc_unit'
	        AND pt.units = lo.option_id
	    WHERE po.patient_id = ?
	    AND prs.date BETWEEN ? AND ?";
			$result = sqlStatement($sql, array($pid, $start, $end, $pid, $start, $end));
		} else {
			$sql    = "
	    SELECT
	      lists.title as proc_title,
	      lists.date as `date`,
	      list_options.title as outcome,
	      '' as laterality,
	      '' as body_site,
	      lists.type as `type`,
	      lists.diagnosis as `code`,
	      IF(SUBSTRING(lists.diagnosis,1,LOCATE(':',lists.diagnosis)-1) = 'ICD9','ICD9-CM',SUBSTRING(lists.diagnosis,1,LOCATE(':',lists.diagnosis)-1)) AS coding
	    FROM
	      lists
	      LEFT JOIN issue_encounter
	        ON issue_encounter.list_id = lists.id
	      LEFT JOIN form_encounter
	        ON form_encounter.encounter = issue_encounter.encounter
	      LEFT JOIN facility
	        ON form_encounter.facility_id = facility.id
	      LEFT JOIN users
	        ON form_encounter.provider_id = users.id
	      LEFT JOIN list_options
	        ON lists.outcome = list_options.option_id
	        AND list_options.list_id = 'outcome'
	    WHERE lists.type = 'surgery'
	      AND lists.pid = ?
	    UNION
	    SELECT
	      pt.name as proc_title,
	      prs.date as `date`,
	      '' as outcome,
	      ptt.laterality as laterality,
	      ptt.body_site as body_site,
	      'Lab Order' as `type`,
	      ptt.standard_code as `code`,
	      IF(SUBSTRING(ptt.standard_code,1,LOCATE(':',ptt.standard_code)-1) = 'ICD9','ICD9-CM',SUBSTRING(ptt.standard_code,1,LOCATE(':',ptt.standard_code)-1)) AS coding
	    FROM
	      procedure_result AS prs
	      LEFT JOIN procedure_report AS prp
	        ON prs.procedure_report_id = prp.procedure_report_id
	      LEFT JOIN procedure_order AS po
	        ON prp.procedure_order_id = po.procedure_order_id
	      LEFT JOIN procedure_type AS pt
	        ON prs.procedure_type_id = pt.procedure_type_id
	      LEFT JOIN procedure_type AS ptt
	        ON pt.parent = ptt.procedure_type_id
	        AND ptt.procedure_type = 'ord'
	      LEFT JOIN list_options AS lo
	        ON lo.list_id = 'proc_unit'
	        AND pt.units = lo.option_id
	    WHERE po.patient_id = ? ";
			$result = sqlStatement($sql, array($pid, $pid));
		}
		return $result;
	}

	function getProblemData()
	{
		# Note we are hard-coding (only allowing) problems that have been coded to ICD9. Would
		#  be easy to upgrade this to other codesets in future (ICD10,SNOMED) by using already
		#  existant flags in the code_types table.
		# Additionally, only using problems that have one diagnosis code set in diagnosis field.
		#  Note OpenEMR allows multiple codes set per problem, but will limit to showing only
		#  problems with one diagnostic code set in order to maintain previous behavior
		#  (this will likely need to be dealt with at some point; ie. support multiple dx codes per problem).
		global $pid, $set, $start, $end;
		if($set == "on") {
			$sql    = "
	    SELECT fe.encounter, fe.reason, fe.provider_id, u.title, u.fname, u.lname,
	      fe.facility_id, f.street, f.city, f.state, ie.list_id, l.pid, l.title AS prob_title, l.diagnosis,
	      l.outcome, l.groupname, l.begdate, l.enddate, l.type, l.comments , l.date
	    FROM lists AS l
	    LEFT JOIN issue_encounter AS ie ON ie.list_id = l.id
	    LEFT JOIN form_encounter AS fe ON fe.encounter = ie.encounter
	    LEFT JOIN facility AS f ON fe.facility_id = f.id
	    LEFT JOIN users AS u ON fe.provider_id = u.id
	    WHERE l.type = 'medical_problem' AND l.pid=? AND l.diagnosis LIKE 'ICD9:%'
	    AND l.diagnosis NOT LIKE '%;%'
	    AND l.date BETWEEN ? AND ?";
			$result = sqlStatement($sql, array($pid, $start, $end));
		} else {
			$sql    = "
	    SELECT fe.encounter, fe.reason, fe.provider_id, u.title, u.fname, u.lname,
	      fe.facility_id, f.street, f.city, f.state, ie.list_id, l.pid, l.title AS prob_title, l.diagnosis,
	      l.outcome, l.groupname, l.begdate, l.enddate, l.type, l.comments , l.date
	    FROM lists AS l
	    LEFT JOIN issue_encounter AS ie ON ie.list_id = l.id
	    LEFT JOIN form_encounter AS fe ON fe.encounter = ie.encounter
	    LEFT JOIN facility AS f ON fe.facility_id = f.id
	    LEFT JOIN users AS u ON fe.provider_id = u.id
	    WHERE l.type = 'medical_problem' AND l.pid=? AND l.diagnosis LIKE 'ICD9:%'
	    AND l.diagnosis NOT LIKE '%;%'";
			$result = sqlStatement($sql, array($pid));
		}
		return $result;
	}

	function getAlertData()
	{
		global $pid, $set, $start, $end;
		if($set == "on") {
			$sql    = "
	    select fe.reason, fe.provider_id, fe.facility_id, fe.encounter,
	      ie.list_id, l.pid, l.title as alert_title, l.outcome,
	      l.groupname, l.begdate, l.enddate, l.type, l.diagnosis, l.date ,
	      l.reaction , l.comments ,
	        f.street, f.city, f.state, u.title, u.fname, u.lname, cd.code_text
	    from lists as l
	    left join issue_encounter as ie
	    on ie.list_id = l.id
	    left join form_encounter as fe
	    on fe.encounter = ie.encounter
	    left join facility as f
	    on fe.facility_id = f.id
	    left join users as u
	    on fe.provider_id = u.id
	    left join codes as cd
	    on cd.code = SUBSTRING(l.diagnosis, LOCATE(':',l.diagnosis)+1)
	    where l.type = 'allergy' and l.pid=?
	    AND l.date BETWEEN ? AND ?";
			$result = sqlStatement($sql, array($pid, $start, $end));
		} else {
			$sql    = "
	    select fe.reason, fe.provider_id, fe.facility_id, fe.encounter,
	      ie.list_id, l.pid, l.title as alert_title, l.outcome,
	      l.groupname, l.begdate, l.enddate, l.type, l.diagnosis, l.date ,
	      l.reaction , l.comments ,
	        f.street, f.city, f.state, u.title, u.fname, u.lname, cd.code_text
	    from lists as l
	    left join issue_encounter as ie
	    on ie.list_id = l.id
	    left join form_encounter as fe
	    on fe.encounter = ie.encounter
	    left join facility as f
	    on fe.facility_id = f.id
	    left join users as u
	    on fe.provider_id = u.id
	    left join codes as cd
	    on cd.code = SUBSTRING(l.diagnosis, LOCATE(':',l.diagnosis)+1)
	    where l.type = 'allergy' and l.pid=?";
			$result = sqlStatement($sql, array($pid));
		}
		return $result;
	}

	function getResultData()
	{
		global $pid, $set, $start, $end;
		if($set == "on") {
			$sql    = "
	      SELECT
	        prs.procedure_result_id as `pid`,
	        pt.name as `name`,
	        pt.procedure_type_id as `type`,
	        prs.date as `date`,
	        concat_ws(' ',prs.result,lo.title) as `result`,
	        prs.range as `range`,
	        prs.abnormal as `abnormal`,
	        prs.comments as `comments`,
	        ptt.lab_id AS `lab`
	      FROM
	        procedure_result AS prs
	        LEFT JOIN procedure_report AS prp
	          ON prs.procedure_report_id = prp.procedure_report_id
	        LEFT JOIN procedure_order AS po
	          ON prp.procedure_order_id = po.procedure_order_id
	        LEFT JOIN procedure_type AS pt
	          ON prs.procedure_type_id = pt.procedure_type_id
	          LEFT JOIN procedure_type AS ptt
	          ON pt.parent = ptt.procedure_type_id
	          AND ptt.procedure_type = 'ord'
	        LEFT JOIN list_options AS lo
	          ON lo.list_id = 'proc_unit' AND pt.units = lo.option_id
	      WHERE po.patient_id=?
	      AND prs.date BETWEEN ? AND ?";
			$result = sqlStatement($sql, array($pid, $start, $end));
		} else {
			$sql    = "
	      SELECT
	        prs.procedure_result_id as `pid`,
	        pt.name as `name`,
	        pt.procedure_type_id as `type`,
	        prs.date as `date`,
	        concat_ws(' ',prs.result,lo.title) as `result`,
	        prs.range as `range`,
	        prs.abnormal as `abnormal`,
	        prs.comments as `comments`,
	        ptt.lab_id AS `lab`
	      FROM
	        procedure_result AS prs
	        LEFT JOIN procedure_report AS prp
	          ON prs.procedure_report_id = prp.procedure_report_id
	        LEFT JOIN procedure_order AS po
	          ON prp.procedure_order_id = po.procedure_order_id
	        LEFT JOIN procedure_type AS pt
	          ON prs.procedure_type_id = pt.procedure_type_id
	          LEFT JOIN procedure_type AS ptt
	          ON pt.parent = ptt.procedure_type_id
	          AND ptt.procedure_type = 'ord'
	        LEFT JOIN list_options AS lo
	          ON lo.list_id = 'proc_unit' AND pt.units = lo.option_id
	      WHERE po.patient_id=?";
			$result = sqlStatement($sql, array($pid));
		}
		return $result;
	}

	function getActorData()
	{
		global $pid;
		$sql       = "
		select fname, lname, DOB, sex, pid, street, city, state, postal_code, phone_contact
		from patient_data
		where pid=?";
		$result[0] = sqlStatement($sql, array($pid));
		$sql2      = "
		SELECT * FROM users AS u LEFT JOIN facility AS f ON u.facility_id = f.id WHERE u.id=?";
		$result[1] = sqlStatement($sql2, array($_SESSION['authUserID']));
		$sql3      = "
	  SELECT
	    u.*
	  FROM
	    procedure_type AS pt
	    LEFT JOIN procedure_order AS po
	      ON po.procedure_type_id = pt.procedure_type_id
	    LEFT JOIN forms AS f
	      ON f.form_id = po.procedure_order_id
	    LEFT JOIN list_options AS lo
	      ON lo.title = f.form_name
	    LEFT JOIN users AS u
	    ON pt.lab_id = u.id
	  WHERE f.pid = ?
	    AND lo.list_id = 'proc_type'
	    AND lo.option_id = 'ord'
	    GROUP BY u.id";
		$result[2] = sqlStatement($sql3, array($pid));
		return $result;
	}

	function getReportFilename()
	{
		global $pid;
		$sql             = "
	    select fname, lname, pid
	    from patient_data
	    where pid=?";
		$result          = sqlQuery($sql, array($pid));
		$result_filename = $result['lname'] . "-" . $result['fname'] . "-" . $result['pid'] . "-" . date("mdY", time());
		return $result_filename;
	}
}

$c = new CCR();
$c->createCCR('viewccd');