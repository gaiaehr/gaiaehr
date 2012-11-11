<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Encounter.php
 * Date: 1/21/12
 * Time: 3:26 PM
 */
/*
 GaiaEHR (Electronic Health Records)
 Medical.php
 Medical dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
if(!isset($_SESSION)) {
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/Medications.php');
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
class Rxnorm
{
	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * @var Patient
	 */
	private $patient;
	private $medications;

	function __construct()
	{
		$this->db           = new dbHelper();
		$this->patient      = new Patient();
		$this->medications  = new Medications();
		return;
	}

	public function getStrengthByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'RXN_AVAILABLE_STRENGTH'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDrugRouteByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'DRT'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDoseformByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'DDF'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDoseformAbbreviateByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'DDFA'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDatabaseShortNameByRXCUI($RXCUI){
		$this->db->setSQL("SELECT SAB
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['SAB'];
	}

	public function getRXNORMLiveSearch(stdClass $params)
	{
		$this->db->setSQL("SELECT *
   							   FROM  rxnconso
   							   WHERE RXCUI    	LIKE'$params->query%'
   							   OR STR         	LIKE'$params->query%'
   							   GROUP BY RXCUI");
		$records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		foreach($records as $val => $rec){
			$records[$val]['RXN_AVAILABLE_STRENGTH'] = $this->getStrengthByRXCUI($rec['RXCUI']);
			$records[$val]['DRT'] = $this->getDrugRouteByRXCUI($rec['RXCUI']);
			$records[$val]['DDF'] = $this->getDoseformByRXCUI($rec['RXCUI']);
			$records[$val]['DDFA'] = $this->getDoseformAbbreviateByRXCUI($rec['RXCUI']);
			$records[$val]['SAB'] = $this->getDatabaseShortNameByRXCUI($rec['RXCUI']);
		}
		$total   = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array(
			'totals' => $total,
			'rows'   => $records
		);
	}
}

//
//$e = new Medical();
//echo '<pre>';
//print_r($e->getPatientMedicationsByPatientID(1));
