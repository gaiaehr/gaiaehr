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
		$pData  = $this->getPatientGraphDataByPid($params->pid);
		foreach($curves as $curve) {
			foreach($pData as $data) {
				if($data['age_mos'] == $curve['age_mos']) {
					if($params->type == 1 || $params->type == 3 || $params->type == 5 || $params->type == 6 || $params->type == 8) {
						$curve['PP'] = $data['weight_kg'];
					} elseif($params->type == 2 || $params->type == 7) {
						$curve['PP'] = $data['height_cm'];
					} else {
						$curve['PP'] = $data['head_circumference_cm'];
					}
				}
			}
			if($params->type == 5 || $params->type == 6 || $params->type == 7 || $params->type == 8) {
				$curve['age_mos'] = ($curve['age_mos'] / 12);
			}
			if($curve['PP'] == null) unset($curve['PP']);
			$graph[] = $curve;
		}
		return $graph;
	}

	private function getPatientGraphDataByPid($pid)
	{
		$DOB    = new DateTime($this->patient->getDOBByPid($pid));
		$vitals = array();
		foreach($this->encounter->getVitalsByPid($pid) as $row) {
			$vital                          = array();
			$date                           = new DateTime($row['date']);
			$vital['age_mos']               = $DOB->diff($date)->m + .5;
			$vital['height_cm']             = $row['height_cm'];
			$vital['weight_kg']             = $row['weight_kg'];
			$vital['head_circumference_cm'] = $row['head_circumference_cm'];
			$vitals[]                       = $vital;
		}
		return $vitals;
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
//
//print '<pre>';
//$params       = new stdClass();
//$params->type = 1;
//$params->pid  = 1;
//$v = new VectorGraph();
//print_r($v->getGraphData($params));
