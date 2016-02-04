<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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

include_once(ROOT . '/dataProvider/Laboratories.php');
include_once(ROOT . '/dataProvider/Rxnorm.php');
include_once(ROOT . '/dataProvider/Services.php');
include_once(ROOT . '/dataProvider/DiagnosisCodes.php');
include_once(ROOT . '/dataProvider/Immunizations.php');

class Medical {
	/**
	 * @var MatchaHelper
	 */
	private $db;
	/**
	 * @var Laboratories
	 */
	private $laboratories;
	/**
	 * @var Rxnorm
	 */
	private $rxnorm;
	/**
	 * @var DiagnosisCodes
	 */
	private $diagnosis;

	/**
	 * @var bool|MatchaCUP
	 */
	private $p;
	/**
	 * @var bool|MatchaCUP
	 */
	private $a;
	/**
	 * @var bool|MatchaCUP
	 */
	private $i;
	/**
	 * @var bool|MatchaCUP
	 */
//	private $ap;
	/**
	 * @var bool|MatchaCUP
	 */
//	private $m;

	function __construct(){
		$this->db = new MatchaHelper();

        if(!isset($this->p))
            $this->p = MatchaModel::setSenchaModel('App.model.patient.Patient');
        if(!isset($this->a))
            $this->a = MatchaModel::setSenchaModel('App.model.patient.Allergies');
        if(!isset($this->i))
            $this->i = MatchaModel::setSenchaModel('App.model.patient.PatientImmunization');

		$this->laboratories = new Laboratories();
		$this->rxnorm = new Rxnorm();
		$this->services = new Services();
		$this->diagnosis = new DiagnosisCodes();
		$this->immunizations = new Immunizations();
		return;
	}

	/*********************************************
	 * METHODS USED BY SENCHA                    *
	 *********************************************/
	/**
	 * @return mixed
	 */

	/**
	 * @return array
	 */
	public function getImmunizationsList(){
		$sql = "SELECT * FROM cvx_codes";
		$this->db->setSQL($sql);
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getPatientImmunizations($params){
		return $this->i->load($params)->all();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function addPatientImmunization($params){
		$immunization = $this->i->save($params);
		// add service
		if($immunization !== false && isset($params->eid) && $params->eid > 0){
			$service = new stdClass();
			$service->pid = $params->pid;
			$service->eid = $params->eid;
			$service->uid = $params->uid;
			$service->code = $this->immunizations->getCptByCvx($params->code);
			$dx_pointers = array();
			foreach($this->diagnosis->getICDByEid($params->eid, true) AS $dx){
				$dx_children[] = $dx;
				$dx_pointers[] = $dx['code'];
			}
			$service->dx_pointers = implode(',', $dx_pointers);
			$this->services->addCptCode($service);
		}
		return $immunization;

	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function updatePatientImmunization($params){
		return $this->i->save($params);

	}

	public function getPatientAllergies($params){
		return $this->a->load($params)->all();
	}
	public function getPatientAllergiesByPid($pid){
		$params = new stdClass();
		$params->filters[0] = new stdClass();
		$params->filters[0]->property = 'pid';
		$params->filters[0]->value =  $pid;
		return $this->a->load($params)->all();
	}

	public function addPatientAllergies($params){
		return $this->a->save($params);
	}

	public function updatePatientAllergies($params){
		return $this->a->save($params);
	}

	public function getPatientLabsResults(stdClass $params){
		return $this->getPatientLabsResultsByPid($params->parent_id);
	}

	public function getPatientLabsResultsByPid($pid){
		$records = array();
		$this->db->setSQL("SELECT pLab.*, pDoc.url AS document_url
							 FROM patient_labs AS pLab
						LEFT JOIN patient_documents AS pDoc ON pLab.document_id = pDoc.id
							WHERE pLab.parent_id = '$pid'
						 ORDER BY date DESC");
		$labs = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		foreach($labs as $lab){
			$id = $lab['id'];
			$this->db->setSQL("SELECT observation_loinc, observation_value, unit
							     FROM patient_labs_results
							    WHERE patient_lab_id = '$id'");
			$lab['columns'] = $this->db->fetchRecords(PDO::FETCH_ASSOC);
			$lab['data'] = array();
			foreach($lab['columns'] as $column){
				$lab['data'][$column['observation_loinc']] = $column['observation_value'];
				$lab['data'][$column['observation_loinc'] . '_unit'] = $column['unit'];
			}
			$records[] = $lab;
		}
		return $records;
	}

	public function addPatientLabsResult(stdClass $params){
		$lab['pid'] = $params->pid;
		$lab['uid'] = $_SESSION['user']['id'];
		$lab['document_id'] = $params->document_id;
		$lab['date'] = date('Y-m-d H:i:s');
		$lab['parent_id'] = $params->parent_id;
		$this->db->setSQL($this->db->sqlBind($lab, 'patient_labs', 'I'));
		$this->db->execLog();
		$patient_lab_id = $this->db->lastInsertId;
		foreach($this->laboratories->getLabObservationFieldsByParentId($params->parent_id) as $result){
			$foo = array();
			$foo['patient_lab_id'] = $patient_lab_id;
			$foo['observation_loinc'] = $result->loinc_number;
			$foo['observation_value'] = null;
			$foo['unit'] = $result->default_unit;
			$this->db->setSQL($this->db->sqlBind($foo, 'patient_labs_results', 'I'));
			$this->db->execOnly();
		}
		return $params;
	}

	public function updatePatientLabsResult(stdClass $params){
		$data = get_object_vars($params);
		$id = $data['id'];
		unset($data['id']);
		foreach($data as $key => $val){
			$foo = explode('_', $key);
			if(sizeof($foo) == 1){
				$observationValue = $val;
			} else{
				$this->db->setSQL("UPDATE patient_labs_results
									  SET observation_value = '$observationValue',
									      unit = '$val'
								    WHERE patient_lab_id = '$id'
								      AND observation_loinc = '$foo[0]'");
				$this->db->execLog();
			}
		}
		return $params;
	}

	public function deletePatientLabsResult(stdClass $params){
		return $params;
	}

	public function signPatientLabsResultById($id){
		$foo['auth_uid'] = $_SESSION['user']['id'];
		$this->db->setSQL($this->db->sqlBind($foo, 'patient_labs', 'U', "id = '$id'"));
		$this->db->execLog();
		return array('success' => true);
	}

	/*********************************************
	 * METHODS USED BY PHP                       *
	 *********************************************/
	/**
	 * @param $pid
	 * @return array
	 */
	public function getPatientImmunizationsByPid($pid){
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		return $this->i->load($params)->all();
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getImmunizationsByEncounterID($eid){
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'eid';
		$params->filter[0]->value = $eid;
		return $this->i->load($params)->all();
	}

	/**
	 * @param $pid
	 * @return array
	 */
	private function getAllergiesByPatientID($pid){
		$this->db->setSQL("SELECT * FROM patient_allergies WHERE pid='$pid'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec){
			$rec['alert'] = ($rec['end_date'] == null || $rec['end_date'] == '0000-00-00 00:00:00') ? 1 : 0;
			$records[] = $rec;
		}
		return $records;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getAllergiesByEncounterID($eid){
		$this->db->setSQL("SELECT * FROM patient_allergies WHERE eid='$eid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $pid
	 * @return array
	 */
	private function getMedicalIssuesByPatientID($pid){
		$this->db->setSQL("SELECT * FROM patient_active_problems WHERE pid='$pid'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec){
			$rec['alert'] = ($rec['end_date'] == null || $rec['end_date'] == '0000-00-00 00:00:00') ? 1 : 0;
			$records[] = $rec;
		}
		return $records;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getMedicalIssuesByEncounterID($eid){
		$this->db->setSQL("SELECT * FROM patient_active_problems WHERE eid = '$eid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function getPatientProblemsByPid($pid){
		$this->db->setSQL("SELECT * FROM patient_active_problems WHERE pid = '$pid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $pid
	 * @return array
	 */
	private function getPatientSurgeryByPatientID($pid){
		$this->db->setSQL("SELECT * FROM patient_surgery WHERE pid='$pid'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec){
			$rec['alert'] = (!isset($rec['end_date']) || $rec['end_date'] == '0000-00-00 00:00:00') ? 1 : 0;
			$records[] = $rec;
		}
		return $records;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getPatientSurgeryByEncounterID($eid){
		$this->db->setSQL("SELECT * FROM patient_surgery WHERE eid='$eid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $pid
	 * @return array
	 */
	private function getPatientDentalByPatientID($pid){
		$this->db->setSQL("SELECT * FROM patient_dental WHERE pid='$pid'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec){
			$rec['alert'] = ($rec['end_date'] == null || $rec['end_date'] == '0000-00-00 00:00:00') ? 1 : 0;
			$records[] = $rec;
		}
		return $records;
	}

	public function getSurgeriesLiveSearch(stdClass $params){
		$this->db->setSQL("SELECT *
   							FROM  surgeries
   							WHERE surgery      LIKE'$params->query%'
   							  OR type         LIKE'$params->query%'");
		$records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array(
			'totals' => $total,
			'rows' => $records
		);
	}

	public function getCDTLiveSearch(stdClass $params){
		$this->db->setSQL("SELECT *
   							FROM  cdt_codes
   							WHERE text      LIKE'$params->query%'
   							  OR code         LIKE'$params->query%'");
		$records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array(
			'totals' => $total,
			'rows' => $records
		);
	}

	public function reviewAllMedicalWindowEncounter(stdClass $params){
		$data = array();
		$data['review_immunizations'] = 1;
		$data['review_allergies'] = 1;
		$data['review_active_problems'] = 1;
		$data['review_alcohol'] = $params->review_alcohol;
		$data['review_smoke'] = $params->review_smoke;
		$data['review_pregnant'] = $params->review_pregnant;
		$data['review_surgery'] = 1;
		$data['review_medications'] = 1;
		$data['review_dental'] = 1;
		$this->db->setSQL($this->db->sqlBind($data, 'encounters', 'U', array('eid' => $params->eid)));
		$this->db->execLog();
		return array('success' => true);
	}

	public function getEncounterReviewByEid($eid){
		$this->db->setSQL("SELECT pid, review_alcohol, review_smoke, review_pregnant FROM encounters WHERE eid = '$eid'");
		$rec = $this->db->fetchRecord();
		$this->db->setSQL("SELECT review_smoke, service_date FROM encounters WHERE pid = '{$rec['pid']}' ORDER BY service_date DESC LIMIT 1");
		$smoke = $this->db->fetchRecord();
		$hasHistory = $smoke !== false && isset($smoke['review_smoke']);
		$rec['last_history_smoke'] = $hasHistory ? $smoke['review_smoke'] : '';
		$rec['last_history_smoke_date'] = $hasHistory ? $smoke['service_date'] : '';
		return $rec;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getPatientDentalByEncounterID($eid){
		$this->db->setSQL("SELECT * FROM patient_dental WHERE eid='$eid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $pid
	 * @return array
	 */
	public function getPatientMedicationsByPatientID($pid){
		$this->db->setSQL("SELECT * FROM patient_medications WHERE pid='$pid'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec){
			$date1 = strtotime(date('Y-m-d'));
			$date2 = strtotime($rec['end_date']);
			$rec['alert'] = (($date2 > $date1) || $rec['end_date'] == null || $rec['end_date'] == '') ? 1 : 0;
			$records[] = $rec;
		}
		return $records;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getPatientMedicationsByEncounterID($eid){
		$this->db->setSQL("SELECT * FROM patient_medications WHERE eid='$eid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function reviewMedicalWindowEncounter(stdClass $params){
		$data = get_object_vars($params);
		$eid = $data['eid'];
		$area = $data['area'];
		unset($data['area'], $data['eid']);
		$data[$area] = 1;
		$this->db->setSQL($this->db->sqlBind($data, 'encounters', 'U', array('eid' => $eid)));
		$this->db->execLog();
		return array('success' => true);
	}

	public function getLabsLiveSearch(stdClass $params){
		$this->db->setSQL("SELECT id,
								  parent_loinc,
								  loinc_number,
								  loinc_name
							FROM  labs_panels
							WHERE parent_loinc <> loinc_number
							  AND loinc_name      LIKE'$params->query%'");
		$records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array(
			'totals' => $total,
			'rows' => $records
		);
	}

	public function getPatientsMedicalSummaryGrouped(stdClass $params){

		$records = array();
		$foo = $this->getAllergiesByPatientID($params->pid);
		if(!empty($allergies)){
			foreach($foo AS $row){
				$record = array(
					'group' => 'Allergies',
					'title' => $row['allergy'],
					'summary' => $row['allergy_type'] . ' | ' . $row['reaction'] . ' | ' . $row['severity'] . ' | ' . $row['location'],
					'status' => $row['end_date'] == null ? 'Active' : 'Not Active',
					'date' => $row['begin_date']
				);
				$records[] = $record;
			}
		} else{
			$record = array(
				'group' => 'Allergies',
				'title' => 'None'
			);
			$records[] = $record;
		}
		$foo = $this->getPatientSurgeryByPatientID($params->pid);
		if(!empty($foo)){
			foreach($foo AS $row){
				$record = array(
					'group' => 'Surgeries',
					'title' => $row['surgery'],
					'summary' => $row['notes'],
					'status' => $row['outcome'],
					'date' => $row['date']
				);
				$records[] = $record;
			}
		} else{
			$record = array(
				'group' => 'Surgeries',
				'title' => 'None'
			);
			$records[] = $record;
		}
		$foo = $this->getPatientLabsResultsByPid($params->pid);
		if(!empty($foo)){
			foreach($foo AS $row){
				$record = array(
					'group' => 'Laboratories',
					'title' => $row['surgery'],
					'summary' => $row['notes'],
					'status' => $row['outcome'],
					'date' => $row['date']
				);
				$records[] = $record;
			}
		} else{
			$record = array(
				'group' => 'Laboratories',
				'title' => 'None'
			);
			$records[] = $record;
		}
		$foo = $this->getPatientImmunizationsByPid($params->pid);
		if(!empty($foo)){
			foreach($foo AS $row){
				$record = array(
					'group' => 'Immunizations',
					'title' => $row['immunization_name'],
					'summary' => $row['note'],
					'status' => '',
					'date' => $row['administered_date']
				);
				$records[] = $record;
			}
		} else{
			$record = array(
				'group' => 'Immunizations',
				'title' => 'None'
			);
			$records[] = $record;
		}
		return $records;
	}

	public function sendVXU($params){
		$p = new stdClass();
		$p->filters = array();
		$p->filters[0] = new stdClass();
		$p->filters[0]->property = 'pid';
		$p->filters[0]->value = $params->pid;

		$data = array();
		$data['to'] = $params->to;
		$data['patient'] = $this->p->load($p)->one();
		$data['immunizations'] = array();

		foreach($params->immunizations As $i){
			$data['immunizations'][] = $this->i->load($i)->one();
		}

		return $data;

	}

	/**
	 * @param $date
	 * @return mixed
	 */
	public function parseDate($date){
		return str_replace('T', ' ', $date);
	}

}
