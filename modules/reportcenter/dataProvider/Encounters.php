<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace modules\reportcenter\dataProvider;

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once('Reports.php');
include_once(ROOT . '/classes/MatchaHelper.php');
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/Encounter.php');
include_once(ROOT . '/dataProvider/i18nRouter.php');

class Encounters extends Reports {
	private $conn;
	private $user;
	private $patient;
	private $encounter;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct() {
		parent::__construct();
		$this->conn = \Matcha::getConn();

		$this->user = new \User();
		$this->patient = new \Patient();
		$this->encounter = new \Encounter();

		return;
	}

	public function CreateEncountersReport(\stdClass $params) {
		$params->to = ($params->to == '') ? date('Y-m-d') : $params->to;
		$html = "<br><h1>Encounters ($params->from - $params->to )</h1>";
		$html2 = "";
		$html .= "<table  border=\"0\" width=\"100%\">
            <tr>
               <th colspan=\"2\" style=\"font-weight: bold;\">" . \i18nRouter::t("encounters") . "</th>
            </tr>
            <tr>
               <td>" . \i18nRouter::t("provider") . "</td>
               <td>" . \i18nRouter::t("encounter") . "</td>
            </tr>";
		$html2 = $this->htmlEncountersList($params, $html2);
		$html .= $html2;
		$html .= "</table>";
		ob_end_clean();
		$Url = $this->ReportBuilder($html, 10);
		return array(
			'success' => true,
			'html' => $html,
			'url' => $Url
		);
	}

	public function getEncountersFromAndTo($from, $to) {
		$sql = " SELECT *
	               FROM patient_prescriptions
	              WHERE created_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";

		if(isset($pid) && $pid != ''){
			$sql .= " AND pid = :p";
			$recordSet = $this->conn->prepare($sql);
			$recordSet->execute(array(':p' => $pid));
		}else{
			$recordSet = $this->conn->prepare($sql);
			$recordSet->execute();
		}


//		foreach($recordSet->fetchAll(\PDO::FETCH_ASSOC) as $key => $data){
//			$id = $data['id'];
//			$sql = " SELECT *
//		   	           FROM patient_medications
//		   	          WHERE prescription_id = '$id'";
//
//			if(isset($drug) && $drug != ''){
//				$sql .= " AND medication_id = '$drug'";
//			}
//			$recordSet2 = $this->conn->prepare($sql); ???? execute
//			$alldata[$key] = recordSets->fetchAll;
//		}

		return $recordSet->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function htmlEncountersList($params, $html) {
		foreach($this->getEncountersFromAndTo($params->from, $params->to) AS $data){
			foreach($data as $data2){
				$html .= "
		            <tr>
						<td>" . $this->patient->getPatientFullNameByPid($data2['pid']) . "</td>
						<td>" . $data2['pid'] . "</td>
					</tr>";
			}
		}
		return $html;
	}

}

//$e = new Rx();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->createPrescriptionsDispensations($params));
