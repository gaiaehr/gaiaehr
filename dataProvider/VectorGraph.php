<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

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

include_once(ROOT . '/classes/Age.php');
include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/Encounter.php');

class VectorGraph
{

	/**
	 * @var MatchaHelper
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
		$this->db = new MatchaHelper();
		$this->patient = new Patient();
		$this->encounter = new Encounter();
	}

	public function getGraphData(stdClass $params)
	{
		$graph = array();

		$curves = $this->getGraphCurves($params->type, $this->patient->getPatientSexIntByPid($params->pid));

		if($params->type == 1){
			// WeightForAgeInf
			$pData = $this->getPatientWeightForAgeInfGraphDataByPid($params->pid);
		} elseif($params->type == 2){
			// LengthForAgeInf
			$pData = $this->getPatientLengthForAgeInfGraphDataByPid($params->pid);
		} elseif($params->type == 3){
			// WeightForRecumbentInf
			$pData = $this->getPatientWeightForRecumbentInfGraphDataByPid($params->pid);
		} elseif($params->type == 4){
			// HeadCircumferenceInf
			$pData = $this->getPatientHeadCircumferenceInfGraphDataByPid($params->pid);
		} elseif($params->type == 5){
			// WeightForStature
			$pData = $this->getPatientWeightForStatureGraphDataByPid($params->pid);
		} elseif($params->type == 6){
			// WeightForAge
			$pData = $this->getPatientWeightForAgeGraphDataByPid($params->pid);
		} elseif($params->type == 7){
			// StatureForAge
			$pData = $this->getPatientStatureForAgeGraphDataByPid($params->pid);
		} else{
			// BMIForAge
			$pData = $this->getPatientBMIForAgeGraphDataByPid($params->pid);
		}

		foreach($curves as $curve){

			// WeightForStature
			if($params->type == 5){
				foreach($pData as $data){
					if($data['height'] >= $curve['height'] && $data['height'] < $curve['height']){
						$curve['PP'] = $data['PP'];
					}
				}

			// the rest of age charts
			}else{
				foreach($pData as $data){
					if($data['age'] == $curve['age_mos'])
						$curve['PP'] = $data['PP'];
				}

				if($params->type == 6 || $params->type == 7 || $params->type == 8){
					$curve['age'] = round($curve['age_mos'] / 12, 2);
				} else{
					$curve['age'] = $curve['age_mos'];
				}
			}

			if(!isset($curve['PP']) || (isset($curve['PP']) && $curve['PP'] == null)) $curve['PP'] = 1000;
			unset($curve['age_mos']);
			$graph[] = $curve;
		}

		return $graph;
	}

	public function getPatientWeightForAgeInfGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this->patient->getPatientDOBByPid($pid);
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientLengthForAgeInfGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this->patient->getPatientDOBByPid($pid);
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['height_cm'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientWeightForRecumbentInfGraphDataByPid($pid)
	{
		$data = array();
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age'] = $foo['height_cm'];
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientHeadCircumferenceInfGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this->patient->getPatientDOBByPid($pid);
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['head_circumference_cm'];
			if($fo['PP'] != null && $fo['PP'] != ''){
				$data[] = $fo;
			}
		}
		return $data;
	}

	/**
	 * WeightForStature
	 * @param $pid
	 * @return array
	 */
	private function getPatientWeightForStatureGraphDataByPid($pid)
	{
		$data = array();
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['height'] = $foo['height_cm'];
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientWeightForAgeGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this->patient->getPatientDOBByPid($pid);
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['weight_kg'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientStatureForAgeGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this->patient->getPatientDOBByPid($pid);
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['height_cm'];
			$data[] = $fo;
		}
		return $data;
	}

	public function getPatientBMIForAgeGraphDataByPid($pid)
	{
		$data = array();
		$dob = $this->patient->getPatientDOBByPid($pid);
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age'] = Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['bmi'];
			$data[] = $fo;
		}
		return $data;
	}

	private function getGraphCurves($type, $sex)
	{
		$conn = Matcha::getConn();
		$sth = $conn->prepare("SELECT * FROM vector_graphs WHERE type = :type AND sex = :sex");
		$sth->execute(array(':type' => $type, ':sex' => $sex));
		$records = array();
		foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row){
			unset($row['type'], $row['sex'], $row['L'], $row['M'], $row['S']);
			foreach($row as $key => $val){
				if($val == null)
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
