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
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}
	public function getDrugRouteByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'DRT'");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}
	public function getDoseformByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'DDF'");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}
	public function getDoseformAbbreviateByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'DDFA'");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

	public function getQuantityByRXCUI($RXCUI){
		$this->db->setSQL("SELECT ATV
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'
		                   AND  ATN = 'RXN_QUANTITY'");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

	public function getDatabaseShortNameByRXCUI($RXCUI){
		$this->db->setSQL("SELECT SAB
		                   FROM rxnsat
		                   WHERE RXCUI = '$RXCUI'");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}
}

//
//$e = new Medical();
//echo '<pre>';
//print_r($e->getPatientMedicationsByPatientID(1));
