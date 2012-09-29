<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/15/12
 * Time: 7:14 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/classes/Age.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Encounter.php');
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
		$this->db        = new dbHelper();
		$this->patient   = new Patient();
		$this->encounter = new Encounter();
	}

	public function getGraphData(stdClass $params)
	{
		$graph  = array();

		$curves = $this->getGraphCurves($params->type, $this->patient->getPatientSexIntByPid($params->pid));

		if($params->type == 1){ // WeightForAgeInf
			$pData  = $this->getPatientWeightForAgeInfGraphDataByPid($params->pid);
		}elseif($params->type == 2){ // LengthForAgeInf
			$pData  = $this->getPatientLengthForAgeInfGraphDataByPid($params->pid);
		}elseif($params->type == 3){ // WeightForRecumbentInf
			$pData  = $this->getPatientWeightForRecumbentInfGraphDataByPid($params->pid);
		}elseif($params->type == 4){ // HeadCircumferenceInf
			$pData  = $this->getPatientHeadCircumferenceInfGraphDataByPid($params->pid);
		}elseif($params->type == 5){ // WeightForStature
			$pData  = $this->getPatientWeightForStatureGraphDataByPid($params->pid);
		}elseif($params->type == 6){ // WeightForAge
			$pData  = $this->getPatientWeightForAgeGraphDataByPid($params->pid);
		}elseif($params->type == 7){ // StatureForAge
			$pData  = $this->getPatientStatureForAgeGraphDataByPid($params->pid);
		}else{ // BMIForAge
			$pData  = $this->getPatientBMIForAgeGraphDataByPid($params->pid);
		}

		foreach($curves as $curve) {
			foreach($pData as $data) {
				if($data['age'] == $curve['age_mos']) $curve['PP'] = $data['PP'];
			}
			if($params->type == 5 || $params->type == 6 || $params->type == 7 || $params->type == 8) {
				$curve['age'] = round($curve['age_mos'] / 12, 2);
			}else{
				$curve['age'] = $curve['age_mos'];
			}
			if($curve['PP'] == null) unset($curve['PP']);
			unset($curve['age_mos']);
			$graph[] = $curve;
		}

		return $graph;
	}

	public function getPatientWeightForAgeInfGraphDataByPid($pid)
	{
		$data = array();
		$dob        = $this->patient->getPatientDOBByPid($pid);
		$vitals     = $this->encounter->getVitalsByPid($pid);
		$data['age']= Age::getMonsBetweenDates($dob, $vitals['date']) + .5;
		$data['PP'] = $vitals['weight_kg'];
		return $data;
	}

	public function getPatientLengthForAgeInfGraphDataByPid($pid)
	{
		$dob = $this->patient->getPatientAgeByPid($pid);
		$pData = array();

		return $pData;
	}

	public function getPatientWeightForRecumbentInfGraphDataByPid($pid)
	{
		$dob = $this->patient->getPatientAgeByPid($pid);
		$pData = array();

		return $pData;
	}

	public function getPatientHeadCircumferenceInfGraphDataByPid($pid)
	{
		$dob = $this->patient->getPatientAgeByPid($pid);
		$pData = array();

		return $pData;
	}

	private function getPatientWeightForStatureGraphDataByPid($pid)
	{
		$dob = $this->patient->getPatientAgeByPid($pid);
		$pData = array();

		return $pData;
	}

	public function getPatientWeightForAgeGraphDataByPid($pid)
	{
		$dob = $this->patient->getPatientAgeByPid($pid);
		$pData = array();

		return $pData;
	}

	public function getPatientStatureForAgeGraphDataByPid($pid)
	{
		$dob = $this->patient->getPatientAgeByPid($pid);
		$pData = array();

		return $pData;
	}

	public function getPatientBMIForAgeGraphDataByPid($pid)
	{
		$data = array();
		$dob  = $this->patient->getPatientDOBByPid($pid);
		foreach($this->encounter->getVitalsByPid($pid) as $foo){
			$fo['age']= Age::getMonsBetweenDates($dob, $foo['date']) + .5;
			$fo['PP'] = $foo['bmi'];
			$data[] = $fo;
		}
		return $data;
	}

	private function getGraphCurves($type, $sex)
	{
		$this->db->setSQL("SELECT * FROM vector_graphs WHERE type = '$type' AND sex = '$sex'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
			unset($row['type'], $row['sex'], $row['L'], $row['M'], $row['S']);
			foreach($row as $key => $val) {
				if($val == null) unset($row[$key]);
			}
			$records[] = $row;
		}
		return $records;
	}

}

//print '<pre>';
//$params       = new stdClass();
//$params->type = 8;
//$params->pid  = 1;
//$v = new VectorGraph();
//print_r($v->getGraphData($params));
//print_r($v->getPatientBMIForAgeGraphDataByPid(1));
