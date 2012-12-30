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
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
//ini_set('memory_limit', '1024M');
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
	//private $patient;
	//private $medications;

	function __construct()
	{
		$this->db           = new dbHelper();
		return;
	}

	public function getStrengthByCODE($CODE){
		$this->db->setSQL("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DST'
		                      AND SAB    = 'MMSL'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDrugRouteByCODE($CODE){
		$this->db->setSQL("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DRT'
		                      AND SAB    = 'MMSL'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDoseformByCODE($CODE){
		$this->db->setSQL("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DDF'
		                      AND SAB    = 'MMSL'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDoseformAbbreviateByCODE($CODE){
		$this->db->setSQL("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DDFA'
		                      AND SAB    = 'MMSL'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}
	public function getDatabaseShortNameByCODE($CODE){
		$this->db->setSQL("SELECT SAB
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
                              AND SAB    = 'MMSL'");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['SAB'];
	}

	public function getMedicationNameByRXCUI($RXCUI){
		$this->db->setSQL("SELECT STR
		                     FROM rxnconso
		                    WHERE RXCUI = '$RXCUI'
		                 GROUP BY RXCUI");
		$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $rec['STR'];
	}

	public function getRXNORMLiveSearch(stdClass $params)
	{
        $this->db->setSQL("SELECT *
                             FROM rxnconso
                            WHERE (SAB = 'MMSL' AND TTY = 'BD')
                              AND STR LIKE '%$params->query%'
                         GROUP BY RXCUI
                         LIMIT 100");
		$records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		$total   = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'rows' => $records);
	}

    public function getMedicationAttributesByCODE($CODE){
        $this->db->setSQL("
            SELECT `ATV`, `ATN` FROM rxnsat WHERE `CODE` = '$CODE' AND `ATN` = 'DST' AND `SAB` = 'MMSL'
            UNION
            SELECT `ATV`, `ATN` FROM rxnsat WHERE `CODE` = '$CODE' AND `ATN` = 'DRT' AND `SAB` = 'MMSL'
            UNION
            SELECT `ATV`, `ATN` FROM rxnsat WHERE `CODE` = '$CODE' AND `ATN` = 'DDF' AND `SAB` = 'MMSL'
        ");
        $foo = array();
            foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) AS $fo){
                $foo[$fo['ATN']] = $fo['ATV'];
            };
        return $foo;
    }
}


//$e = new Rxnorm();
//$p = new stdClass();
//$p->query = 'meta';
//$p->start = 0;
//$p->limit = 25;
//echo '<pre>';
//print_r($e->getRXNORMLiveSearch($p));
