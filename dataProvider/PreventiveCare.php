<?php
/*
 GaiaEHR (Electronic Health Records)
 PreventiveCare.php
 Preventive Care dataProvider
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
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
/**
 * @brief       Services Class.
 * @details     This class will handle all services
 *
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 *
 */

class PreventiveCare
{
	/**
	 * @var dbHelper
	 */
	private $db;

	/**
	 * @var Patient
	 */
	private $patient;

	function __construct()
	{
		$this -> db = new dbHelper();
		$this -> patient = new Patient();
	}

	/******************************************************************************************************************/
	/**** PUBLIC METHODS || PUBLIC METHODS || PUBLIC METHODS || PUBLIC METHODS ||
	 * PUBLIC METHODS || PUBLIC METHODS ****/
	/******************************************************************************************************************/

	/**
	 * get preventive care guideline by category id
	 * @param stdClass $params
	 * @return array
	 */
	public function getGuideLinesByCategory(stdClass $params)
	{
		$records = array();

		$this -> db -> setSQL("SELECT * FROM preventive_care_guidelines WHERE category_id = '$params->category_id'");
		$records = $this -> db -> fetchRecords(PDO::FETCH_CLASS);

		$total = count($records);
		$records = array_slice($records, $params -> start, $params -> limit);
		return array(
			'totals' => $total,
			'rows' => $records
		);
		;
	}

	/**
	 * update preventive care guideline by category id
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addGuideLine(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'preventive_care_guidelines', 'I'));
		$this -> db -> execLog();
		$params -> id = $this -> db -> lastInsertId;
		return $params;
	}

	/**
	 * update preventive care guideline by category id
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateGuideLine(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'preventive_care_guidelines', 'U', "id='$params->id'"));
		$this -> db -> execLog();
		return $params;
	}

	/**
	 * get guideline active problem by guideline id
	 * @param stdClass $params
	 * @return \stdClass
	 */
	public function getGuideLineActiveProblems(stdClass $params)
	{
		$active_problems = array();
		$foo = explode(';', $this -> getCodes($params -> id, 'active_problems'));
		if ($foo[0])
		{
			foreach ($foo as $fo)
			{
				$this -> db -> setSQL("SELECT code, code_text FROM codes_icds WHERE code = '$fo' AND code IS NOT NULL");
				$problem = $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
				$problem['guideline_id'] = $params -> id;
				$active_problems[] = $problem;
			}
		}
		return $active_problems;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function addGuideLineActiveProblems($params)
	{
		if (is_array($params))
		{
			foreach ($params as $p)
			{
				$this -> addCode($p -> guideline_id, $p -> code, 'active_problems');
			}
		}
		else
		{
			$this -> addCode($params -> guideline_id, $params -> code, 'active_problems');
		}
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function removeGuideLineActiveProblems($params)
	{
		if (is_array($params))
		{
			foreach ($params as $p)
			{
				$this -> removeCode($p -> guideline_id, $p -> code, 'active_problems');
			}
		}
		else
		{
			$this -> removeCode($params -> guideline_id, $params -> code, 'active_problems');
		}
		return $params;
	}

	/**
	 * get guideline medications by guideline id
	 * @param stdClass $params
	 * @return \stdClass
	 */
	public function getGuideLineMedications(stdClass $params)
	{
		$medications = array();
		$foo = explode(';', $this -> getCodes($params -> id, 'medications'));
		if ($foo[0])
		{
			foreach ($foo AS $fo)
			{
				$this -> db -> setSQL("SELECT RXCUI AS code,
										  STR AS code_text
								     FROM rxnconso
								    WHERE id = '$fo'");
				$medication = $this -> db -> fetchRecord(PDO::FETCH_CLASS);
				$medication['guideline_id'] = $params -> id;
				$medications[] = $medication;
			}
		}
		return $medications;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addGuideLineMedications($params)
	{
		if (is_array($params))
		{
			foreach ($params as $p)
			{
				$this -> addCode($p -> guideline_id, $p -> code, 'medications');
			}
		}
		else
		{
			$this -> addCode($params -> guideline_id, $params -> code, 'medications');
		}
		return $params;
	}

	public function addGuideLineLabs($params)
	{

		if (is_array($params))
		{
			foreach ($params as $p)
			{
				$data = get_object_vars($p);
				unset($data['id']);
				$this -> db -> setSQL($this -> db -> sqlBind($data, 'labs_guidelines', 'I'));
				$this -> db -> execLog();
			}
		}
		else
		{
			$data = get_object_vars($params);
			unset($data['id']);
			$this -> db -> setSQL($this -> db -> sqlBind($data, 'labs_guidelines', 'I'));
			$this -> db -> execLog();
		}
		return $params;
	}

	public function getGuideLineLabs(stdClass $params)
	{
		$this -> db -> setSQL("SELECT * FROM labs_guidelines WHERE preventive_care_id='$params->id'");
		return $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
	}

	public function removeGuideLineLabs($params)
	{

		if (is_array($params))
		{
			foreach ($params as $p)
			{
				$data = get_object_vars($p);
				$id = $data['id'];
				$this -> db -> setSQL("DELETE FROM labs_guidelines WHERE id = '$id'");
				$this -> db -> execLog();
			}
		}
		else
		{
			$data = get_object_vars($params);
			$id = $data['id'];
			$this -> db -> setSQL("DELETE FROM labs_guidelines WHERE id = '$id'");
			$this -> db -> execLog();
		}
		return $params;

	}

	public function updateGuideLineLabs($params)
	{

		if (is_array($params))
		{
			foreach ($params as $p)
			{
				$data = get_object_vars($p);
				$id = $data['id'];
				unset($data['id']);
				$this -> db -> setSQL($this -> db -> sqlBind($data, "labs_guidelines", "U", "id='$id'"));
				$this -> db -> execLog();
			}
		}
		else
		{
			$data = get_object_vars($params);
			$id = $data['id'];
			unset($data['id']);
			$this -> db -> setSQL($this -> db -> sqlBind($data, "labs_guidelines", "U", "id='$id'"));
			$this -> db -> execLog();

		}
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function removeGuideLineMedications($params)
	{
		if (is_array($params))
		{
			foreach ($params as $p)
			{
				$this -> removeCode($p -> guideline_id, $p -> code, 'medications');
			}
		}
		else
		{
			$this -> removeCode($params -> guideline_id, $params -> code, 'medications');
		}
		return $params;
	}

	/******************************************************************************************************************/
	/* PRIVATE METHODS || PRIVATE METHODS || PRIVATE METHODS || PRIVATE METHODS ||
	 * PRIVATE METHODS || PRIVATE METHODS */
	/******************************************************************************************************************/

	/**
	 * @param $id
	 * @param $codeColumn
	 * @return mixed
	 */
	private function getCodes($id, $codeColumn)
	{
		$this -> db -> setSQL("SELECT $codeColumn FROM preventive_care_guidelines WHERE id = '$id' AND $codeColumn IS NOT NULL");
		$foo = $this -> db -> fetchRecord(PDO::FETCH_CLASS);
		return $foo[$codeColumn];
	}

	/**
	 * @param $id
	 * @param $code
	 * @param $codeColumn
	 * @return mixed
	 */
	private function removeCode($id, $code, $codeColumn)
	{
		$codes = explode(';', $this -> getCodes($id, $codeColumn));
		$key = array_search($code, $codes);
		if ($key !== false)
			unset($codes[$key]);
		if (!empty($codes))
		{
			$codes = implode(';', $codes);
			$data[$codeColumn] = $codes;
		}
		else
		{
			$data[$codeColumn] = null;
		}
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'preventive_care_guidelines', 'U', "id='$id'"));
		$this -> db -> execLog();
		return;
	}

	/**
	 * @param $id
	 * @param $code
	 * @param $codeColumn
	 * @return mixed
	 */
	private function addCode($id, $code, $codeColumn)
	{
		$codes = explode(';', $this -> getCodes($id, $codeColumn));
		if (!$codes[0])
		{
			$data[$codeColumn] = $code;
		}
		else
		{
			$codes[] = $code;
			$codes = implode(';', $codes);
			$data[$codeColumn] = $codes;
		}
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'preventive_care_guidelines', 'U', "id='$id'"));
		$this -> db -> execLog();
		return;
	}

	public function checkAge($pid, $immu_id)
	{

		$DOB = $this -> patient -> getPatientDOBByPid($pid);
		$age = $this -> patient -> getPatientAgeByDOB($DOB);
		$range = $this -> getPreventiveCareAgeRangeById($immu_id);

		if ($age['DMY']['years'] >= $range['age_start'] && $age['DMY']['years'] <= $range['age_end'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function checkSex($pid, $immu_id)
	{

		$pSex = $this -> patient -> getPatientSexByPid($pid);

		$iSex = $this -> getPreventiveCareSexById($immu_id);

		if ($iSex == $pSex)
		{
			return true;
		}
		else
		if ($iSex == 'Both' || $iSex == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function checkPregnant($pid, $immu_id)
	{

		$ppreg = $this -> patient -> getPatientPregnantStatusByPid($pid);
		$ipreg = $this -> getPreventiveCarePregnantById($immu_id);

		if ($ppreg == 1 && $ipreg == 1)
		{
			return true;
		}
		elseif ($ppreg == 1 && $ipreg == 0)
		{

			return true;
		}
		else
		{
			return false;
		}

	}

	public function checkProblem($pid, $preventiveId)
	{

		$check = $this -> checkMedicationProblemLabs($pid, $preventiveId, 'patient_active_problems', 'active_problems', 'code');
		if ($check)
		{
			return true;

		}
		else
		{
			return false;
		}

	}

	public function checkMedications($pid, $preventiveId)
	{

		$check = $this -> checkMedicationProblemLabs($pid, $preventiveId, 'patient_medications', 'medications', 'RXCUI');
		if ($check)
		{
			return true;

		}
		else
		{
			return false;
		}

	}

	public function checkMedicationProblemLabs($pid, $preventiveId, $tablexx, $column, $columnName)
	{

		$preventiveProblems = $this -> getPreventiveCareActiveProblemsById($preventiveId);
		$preventiveProblems = explode(';', $preventiveProblems[$column]);
		$patientProblems = $this -> patient -> getPatientActiveProblemsById($pid, $tablexx, $columnName);
		$checking = array();
		$size = sizeof($preventiveProblems);
		foreach ($preventiveProblems as $prob)
		{
			foreach ($patientProblems as $patient)
			{
				if ($prob == $patient[$columnName])
				{
					$checking[$patient[$columnName]] = true;

				}
			}
		}
		if ($size == sizeof($checking) || $preventiveProblems[0] == '')
		{
			return true;
		}
		else
		{

			return false;
		}

	}

	public function getPreventiveCarePregnantById($id)
	{
		$this -> db -> setSQL("SELECT pregnant
                           FROM preventive_care_guidelines
                           WHERE id='$id'");
		$u = $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
		return $u['pregnant'];
	}

	public function getPreventiveCareSexById($id)
	{
		$this -> db -> setSQL("SELECT sex
                           FROM preventive_care_guidelines
                           WHERE id='$id'");
		$u = $this -> db -> fetchRecord(PDO::FETCH_ASSOC);

		if ($u['sex'] == 1)
		{
			$sex = 'Male';
		}
		else
		if ($u['sex'] == 2)
		{
			$sex = 'Female';
		}
		else
		{
			$sex = 'Both';
		}

		return $sex;
	}

	public function getPreventiveCareActiveProblemsById($id)
	{
		$this -> db -> setSQL("SELECT *
                           FROM preventive_care_guidelines
                           WHERE id='$id'");
		return $this -> db -> fetchRecord(PDO::FETCH_ASSOC);

	}

	public function getPreventiveCareAgeRangeById($id)
	{
		$this -> db -> setSQL("SELECT age_start,
                                  age_end
                           FROM preventive_care_guidelines
                           WHERE id='$id'");
		return $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
	}

	public function activePreventiveCareAlert($params)
	{
		$alerts = $this -> getPreventiveCareCheck($params);
		if (sizeof($alerts) >= '0' && $alerts[0] != '')
		{
			return array('success' => true);
		}
		else
		{
			return array('success' => false);
		}
	}

	public function checkForDismiss($pid)
	{

		$this -> db -> setSQL("SELECT *
                           FROM preventive_care_inactive_patient
                           WHERE pid='$pid'
                           AND dismiss='1'");
		return $this -> db -> fetchRecords(PDO::FETCH_ASSOC);

	}

	public function getPreventiveCareCheck(stdClass $params)
	{

		$this -> db -> setSQL("SELECT * FROM preventive_care_guidelines");
		$patientAlerts = array();

		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec)
		{

			$rec['alert'] = (($this -> checkAge($params -> pid, $rec['id']) && $this -> checkSex($params -> pid, $rec['id']) && $this -> checkProblem($params -> pid, $rec['id']) && $this -> checkMedications($params -> pid, $rec['id'])) || $this -> checkPregnant($params -> pid, $rec['id'])) ? true : false;

			if ($rec['category_id'] == 3)
			{
				$rec['type'] = 'Immunizations';
			}
			else
			if ($rec['category_id'] == 4)
			{
				$rec['type'] = 'Laboratory Test';
			}
			else
			if ($rec['category_id'] == 1516)
			{
				$rec['type'] = 'Diagnostic Test';
			}
			else
			if ($rec['category_id'] == 1517)
			{
				$rec['type'] = 'Disease Management';
			}
			else
			if ($rec['category_id'] == 1518)
			{
				$rec['type'] = 'Pedriatic Vaccines';
			}
			else
			{
				$rec['type'] = 'Uncategorized';
			}

			//            && $this->checkPregnant($params->pid, $rec['id'])
			if ($rec['alert'] && $rec['active'])
			{
				$patientAlerts[] = $rec;

			}
		}

		$patientDismissedAlerts = $this -> checkForDismiss($params -> pid);

		$count = 0;
		foreach ($patientAlerts as $patientAlert)
		{
			foreach ($patientDismissedAlerts as $patientDismissedAlert)
			{
				if ($patientDismissedAlert['preventive_care_id'] == $patientAlert['id'])
				{
					unset($patientAlerts[$count]);
				}
			}
			$count++;
		}

		return array_values($patientAlerts);
	}

	public function addPreventivePatientDismiss(stdClass $params)
	{

		$data = get_object_vars($params);
		$preventivecareid = $data['id'];
		$pid = $_SESSION['patient']['pid'];
		$this -> db -> setSQL("SELECT *
                           FROM preventive_care_inactive_patient
                           WHERE pid='$pid'
                           AND preventive_care_id ='$preventivecareid' ");
		$u = $this -> db -> fetchRecord(PDO::FETCH_ASSOC);

		if ($u[0] == ' ' || $u == null)
		{

			unset($data['description'], $data['type'], $data['alert']);
			$data['preventive_care_id'] = $data['id'];
			$data['pid'] = $_SESSION['patient']['pid'];
			$data['uid'] = $_SESSION['user']['id'];
			unset($data['id']);
			$this -> db -> setSQL($this -> db -> sqlBind($data, 'preventive_care_inactive_patient', 'I'));
			$this -> db -> execLog();
			$params -> id = $this -> db -> lastInsertId;
			return array(
				'totals' => 1,
				'rows' => $params
			);
		}
		else
		{

			$id = $u['id'];
			unset($data['description'], $data['type'], $data['alert']);
			$data['preventive_care_id'] = $data['id'];
			$data['pid'] = $_SESSION['patient']['pid'];
			$data['uid'] = $_SESSION['user']['id'];
			unset($data['id']);
			$this -> db -> setSQL($this -> db -> sqlBind($data, 'preventive_care_inactive_patient', 'U', "id='$id'"));
			$this -> db -> execLog();
			return array(
				'totals' => 1,
				'rows' => $params
			);
		}

	}

	public function getPreventiveCareDismissPatientByEncounterID($eid)
	{
		$this -> db -> setSQL("SELECT pcip.*, pcg.description
                           FROM preventive_care_inactive_patient AS pcip
                           LEFT JOIN preventive_care_guidelines AS pcg ON pcg.id = pcip.preventive_care_id
                           WHERE pcip.eid='$eid'");
		return $this -> db -> fetchRecords(PDO::FETCH_ASSOC);

	}

	public function getpreventiveCareById($id)
	{
		$this -> db -> setSQL("SELECT * FROM preventive_care_guidelines WHERE id = '$id'");
		return $this -> db -> fetchRecord();
	}

	public function getPreventiveCareDismissedAlertsByPid(stdClass $params)
	{
		$this -> db -> setSQL("SELECT pcig.id,
                                  pcig.preventive_care_id,
                                  pcig.reason,
                                  pcig.dismiss,
                                  pcig.date,
                                  pcig.observation,
                                  pcg.description
                           FROM preventive_care_inactive_patient as pcig
                           LEFT JOIN preventive_care_guidelines  as pcg on pcig.preventive_care_id = pcg.id
                           WHERE pcig.pid = '$params->pid'
                           AND  pcig.dismiss = 1");
		return $this -> db -> fetchRecords();
	}

	public function updatePreventiveCareDismissedAlertsByPid(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id'], $data['description']);
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'preventive_care_inactive_patient', 'U', array('id' => $params -> id)));
		$this -> db -> execLog();
		return $params;
	}

}

//$params = new stdClass();
//$params->start = 0;
//$params->limit = 25;
//$params->pid = 5;
//$t = new PreventiveCare();
//print '<pre>';
//print_r($t->getPreventiveCareCheck($params));
