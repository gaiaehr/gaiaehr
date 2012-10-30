<?php
/*
 GaiaEHR (Electronic Health Records)
 Services.php
 Services dataProvider
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
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
/**
 * @brief       Services Class.
 * @details     This class will handle all services
 *
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 *
 */
class Services
{
	/**
	 * @var dbHelper
	 */
	private $db;

	function __construct()
	{
		return $this -> db = new dbHelper();
	}

	/**
	 * @param stdClass $params
	 * @return array|stdClass
	 */
	public function getCptCodesList(stdClass $params)
	{
		$sort = (isset($params -> sort[0]) ? 'ORDER BY ' . $params -> sort[0] -> property . ' ' . $params -> sort[0] -> direction : '');
		$params -> active = (isset($params -> active) ? $params -> active : 1);
		$this -> db -> setSQL("SELECT * FROM cpt_codes WHERE active = '$params->active' $sort");
		return $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getHCPCList(stdClass $params)
	{
		$sort = (isset($params -> sort[0]) ? 'ORDER BY ' . $params -> sort[0] -> property . ' ' . $params -> sort[0] -> direction : '');
		$params -> active = (isset($params -> active) ? $params -> active : 1);
		$this -> db -> setSQL("SELECT * FROM hcpcs_codes WHERE active = '$params->active' $sort");
		return $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
	}

	public function getCptCodes(stdClass $params)
	{
		if ($params -> filter === 0)
		{
			$record = $this -> getCptRelatedByEidIcds($params -> eid);
		}
		elseif ($params -> filter === 1)
		{
			$record = $this -> getCptUsedByPid($params -> pid);
		}
		elseif ($params -> filter === 2)
		{
			$record = $this -> getCptUsedByClinic($params -> pid);
		}
		else
		{
			$record = $this -> getCptByEid($params -> eid);
		}
		return $record;
	}

	public function addCptCode(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['code_text'], $data['code_text_medium']);
		foreach ($data as $key => $val)
		{
			if ($val == null || $val == '')
			{
				unset($data[$key]);
			}
		}
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'encounter_codes_cpt', 'I'));
		$this -> db -> execLog();
		$params -> id = $this -> db -> lastInsertId;
		return array(
			'totals' => 1,
			'rows' => $params
		);
	}

	public function updateCptCode(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id'], $data['eid'], $data['code'], $data['code_text'], $data['code_text_medium']);
		$params -> id = intval($params -> id);
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'encounter_codes_cpt', 'U', "id='$params->id'"));
		$this -> db -> execLog();
		return array(
			'totals' => 1,
			'rows' => $params
		);
	}

	public function deleteCptCode(stdClass $params)
	{
		$this -> db -> setSQL("SELECT status FROM encounter_codes_cpt WHERE id = '$params->id'");
		$cpt = $this -> db -> fetchRecord();
		if ($cpt['status'] == 0)
		{
			$this -> db -> setSQL("DELETE FROM encounter_codes_cpt WHERE id ='$params->id'");
			$this -> db -> execLog();
		}
		return array(
			'totals' => 1,
			'rows' => $params
		);
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getCptRelatedByEidIcds($eid)
	{
		$this -> db -> setSQL("SELECT DISTINCT cpt.code, cpt.code_text
                             FROM cpt_codes as cpt
                       RIGHT JOIN cpt_icd as ci ON ci.cpt = cpt.code
                        LEFT JOIN encounter_codes_icdx as eci ON eci.code = ci.icd
                            WHERE eci.eid = '$eid'");
		$records = array();
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{
			if ($row['code'] != null || $row['code'] != '')
			{
				$records[] = $row;
			}
		}
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getCptByEid($eid)
	{
		$this -> db -> setSQL("SELECT DISTINCT ecc.*, cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_codes_cpt AS ecc
                        left JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                            WHERE ecc.eid = '$eid' ORDER BY ecc.id ASC");
		$records = $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getHCPCByEid($eid)
	{
		$this -> db -> setSQL("SELECT DISTINCT ech.*, hc.code, hc.code_text, hc.code_text_short
                             FROM encounter_codes_hcpcs AS ech
                        left JOIN hcpcs_codes AS hc ON ech.code = hc.code
                            WHERE ech.eid = '$eid' ORDER BY ech.id ASC");
		$records = $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	/**
	 * @param $pid
	 * @return array
	 */
	public function getCptUsedByPid($pid)
	{
		$this -> db -> setSQL("SELECT DISTINCT cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_codes_cpt AS ecc
                        left JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                        LEFT JOIN encounters AS e ON ecc.eid = e.eid
                            WHERE e.pid = '$pid'
                         ORDER BY e.service_date DESC");
		$records = $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	/**
	 * @return array
	 */
	public function getCptUsedByClinic()
	{
		$this -> db -> setSQL("SELECT DISTINCT cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_codes_cpt AS ecc
                        left JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                         ORDER BY cpt.code DESC");
		$records = $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	public function getActiveProblems(stdClass $params)
	{
		return $params;
	}

	public function addActiveProblems(stdClass $params)
	{
		return $params;
	}

	public function removeActiveProblems(stdClass $params)
	{
		return $params;
	}

}

//
//$params = new stdClass();
//$params->filter = 2;
//$params->pid = '7';
//$params->eid = '1';
//$params->start = 0;
//$params->limit = 25;
//
//$t = new Services();
//print '<pre>';
//print_r($t->getLastRevisionByCode('ICD9'));
