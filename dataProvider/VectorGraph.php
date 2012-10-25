<?php
/*
 GaiaEHR (Electronic Health Records)
 VectorGraph.php
 Vector Graph dataProvider
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
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/classes/Age.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/Encounter.php');
class VectorGraph
{

	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * @var Patient
	 */
	private $patient;
	/**
	 * @var Patient
	 */
	private $encounter;

	function __construct()
	{
		$this -> db = new dbHelper();
		$this -> patient = new Patient();
		$this -> encounter = new Encounter();
	}

	public function getGraphData(stdClass $params)
	{
		$graph = array();

		$curves = $this -> getGraphCurves($params -> type, $this -> patient -> getPatientSexIntByPid($params -> pid));

		if ($params -> type == 1)
		{
			// WeightForAgeInf
			$pData = $this -> getPatientWeightForAgeInfGraphDataByPid($params -> pid);
		}
		elseif ($params -> type == 2)
		{
			// LengthForAgeInf
			$pData = $this -> getPatientLengthForAgeInfGraphDataByPid($params -> pid);
		}
		elseif ($params -> type == 3)
		{
			// WeightForRecumbentInf
			$pData = $this -> getPatientWeightForRecumbentInfGraphDataByPid($params -> pid);
		}
		elseif ($params -> type == 4)
		{
			// HeadCircumferenceInf
			$pData = $this -> getPatientHeadCircumferenceInfGraphDataByPid($params -> pid);
		}
		elseif ($params -> type == 5)
		{
			// WeightForStature
			$pData = $this -> getPatientWeightForStatureGraphDataByPid($params -> pid);
		}
		elseif ($params -> type == 6)
		{
			// WeightForAge
			$pData = $this -> getPatientWeightForAgeGraphDataByPid($params -> pid);
		}
		elseif ($params -> type == 7)
		{
			// StatureForAge
			$pData = $this -> getPatientStatureForAgeGraphDataByPid($params -> pid);
		}
		else
		{
			// BMIForAge
			$pData = $this -> getPatientBMIForAgeGraphDataByPid($params -> pid);
		}

		foreach ($curves as $curve)
		{
			foreach ($pData as $data)
			{
				if ($data['age'] == $curve['age_mos'])
					$curve['PP'] = $data['PP'];
			}
			if ($params -> type == 6 || $params -> type == 7 || $params -> type == 8)
			{
				$curve['age'] = round($curve['age_mos'] / 12, 2);
			}
			else
			{
				$curve['age'] = $curve['age_mos'];
			}
			if ($curve['PP'] == null)
				unset($curve['PP']);
			unset($curve['age_mos']);
			$graph[] = $curve;
		}

		return $graph;
	}

	public function getPatientWeightForAgeInfGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this -> patient -> getPatientDOBByPid($pid);
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientLengthForAgeInfGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this -> patient -> getPatientDOBByPid($pid);
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['height_cm'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientWeightForRecumbentInfGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this -> patient -> getPatientDOBByPid($pid);
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['age'] = $foo['height_cm'];
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientHeadCircumferenceInfGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this -> patient -> getPatientDOBByPid($pid);
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['head_circumference_cm'];
			if ($fo['PP'] != null && $fo['PP'] != '')
			{
				$data[] = $fo;
			}
		}
		return $data;
	}

	private function getPatientWeightForStatureGraphDataByPid($pid)
	{
		$data = array();
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['height'] = $foo['height_cm'];
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientWeightForAgeGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this -> patient -> getPatientDOBByPid($pid);
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientStatureForAgeGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this -> patient -> getPatientDOBByPid($pid);
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['height_cm'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientBMIForAgeGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this -> patient -> getPatientDOBByPid($pid);
		foreach ($this->encounter->getVitalsByPid($pid) as $foo)
		{
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['bmi'];
			$data[] = $fo;
		}
		return $data;
	}

	private function getGraphCurves($type, $sex)
	{
		$this -> db -> setSQL("SELECT * FROM vector_graphs WHERE type = '$type' AND sex = '$sex'");
		$records = array();
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{
			unset($row['type'], $row['sex'], $row['L'], $row['M'], $row['S']);
			foreach ($row as $key => $val)
			{
				if ($val == null)
					unset($row[$key]);
			}
			$records[] = $row;
		}
		return $records;
	}

}

//print '<pre>';
//$params       = new stdClass();
//$params->type = 3;
//$params->pid  = 4;
//$v = new VectorGraph();
//print_r($v->getPatientWeightForRecumbentInfGraphDataByPid(4));
//print_r($v->getGraphData($params));
