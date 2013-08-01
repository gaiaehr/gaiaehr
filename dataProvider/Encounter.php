<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

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

include_once ($_SESSION['root'] . '/classes/Time.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/PoolArea.php');
include_once ($_SESSION['root'] . '/dataProvider/Medical.php');
include_once ($_SESSION['root'] . '/dataProvider/PreventiveCare.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/DiagnosisCodes.php');

class Encounter
{
	/**
	 * @var MatchaHelper
	 */
	private $db;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var Patient
	 */
	private $patient;
	/**
	 * @var Services
	 */
	private $services;
	/**
	 * @var
	 */
	private $eid;
	/**
	 * @var PoolArea
	 */
	private $poolArea;
	/**
	 * @var Medical
	 */
	private $medical;
	/**
	 * @var PreventiveCare
	 */
	private $preventiveCare;
	/**
	 * @var DiagnosisCodes
	 */
	private $diagnosis;

    private $EncounterHistory;

	function __construct()
	{
		$this->db             = new MatchaHelper();
		$this->user           = new User();
		$this->patient        = new Patient();
		$this->services       = new Services();
		$this->poolArea       = new PoolArea();
		$this->medical        = new Medical();
		$this->preventiveCare = new PreventiveCare();
		$this->diagnosis      = new DiagnosisCodes();
		return;
	}

	private function setEid($eid)
	{
		$this->eid = $eid;
        /**
         * This is a temporary variable to comfort the certification needed by GaiaEHR
         * GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
         * Added by: Gino Rivera Falu
         * Web Jul 31 2013
         */
        $_SESSION['encounter']['id'] = $eid; // Added by Gino Rivera
	}

	/**
	 * @return array
	 */
	public function checkOpenEncounters()
	{
		$pid = $_SESSION['patient']['pid'];
		$this->db->setSQL("SELECT pid FROM encounters WHERE pid = '$pid' AND close_date IS NULL");
		$total = $this->db->rowCount();
		if($total >= 1){
			return array('encounter' => true);
		} else {
			return array('encounter' => false);
		}
	}

	/**
	 * @param stdClass $params
	 * @return array
	 *  Naming: "getPatientEncounters"
	 */
	public function getEncounters(stdClass $params)
	{
		$pid    = $_SESSION['patient']['pid'];
		$ORDERX = isset($params->sort) ? $params->sort[0]->property . ' ' . $params->sort[0]->direction : 'service_date DESC';
		$this->db->setSQL("SELECT * FROM encounters WHERE pid = '$pid' ORDER BY $ORDERX");
		$rows = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$row['status'] = ($row['close_date'] == null) ? 'open' : 'close';
			array_push($rows, $row);
		}
		return $rows;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 *  Naming: "createPatientEncounters"
	 */
	public function createEncounter(stdClass $params)
	{
		$params->pid      = (isset($params->pid) && is_numeric($params->pid) ? $params->pid : $_SESSION['patient']['pid']);
		$params->open_uid = $_SESSION['user']['id'];
		$data             = get_object_vars($params);
		foreach($data as $key => $val){
			if($val == ''){
				unset($data[$key]);
			}
		}
		$data['service_date'] = $this->parseDate($data['service_date']);
		$sql                = $this->db->sqlBind($data, 'encounters', 'I');
		$this->db->setSQL($sql);
		$this->db->execLog();
		$eid     = $this->db->lastInsertId;
		$default = array('pid' => $params->pid, 'eid' => $eid, 'uid' => $params->open_uid, 'date' => Time::getLocalTime());
		$this->db->setSQL($this->db->sqlBind($default, 'encounter_review_of_systems', 'I'));
		$this->db->execOnly();
		$this->db->setSQL($this->db->sqlBind($default, 'encounter_review_of_systems_check', 'I'));
		$this->db->execOnly();
		$this->db->setSQL($this->db->sqlBind($default, 'encounter_soap', 'I'));
		$this->db->execOnly();
		$this->db->setSQL($this->db->sqlBind($default, 'encounter_dictation', 'I'));
		$this->db->execOnly();
		$this->db->setSQL($this->db->sqlBind($default, 'encounter_1500_options', 'I'));
		$this->db->execOnly();
		$params->eid = intval($eid);
		$this->poolArea->updateCurrentPatientPoolAreaByPid(array('eid' => $params->eid, 'priority' => $params->priority), $params->pid);
		$this->setEid($params->eid);

		//$this->addEncounterHistoryEvent('New Encounter Created');

        // Audit Log
        // Added by: Gino Rivera
        // Web Jul 31 2013
        // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
        $this->db->AuditLog('New encounter created');

		return array('success' => true, 'encounter' => $params);
	}

	/**
	 * @param stdClass $params
	 * @return array|mixed
	 *  Naming: "getPatientEncounters"
	 */
	public function getEncounter(stdClass $params)
	{
		$this->setEid($params->eid);
		$this->db->setSQL("SELECT * FROM encounters WHERE eid = '$params->eid'");
		$encounter                          = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		$encounter['vitals']                = $this->getVitalsByPid($encounter['pid']);
		$encounter['reviewofsystems']       = $this->getReviewOfSystemsByEid($params->eid);
		$encounter['reviewofsystemschecks'] = $this->getReviewOfSystemsChecksByEid($params->eid);
		$encounter['soap']                  = $this->getSoapByEid($params->eid);
		//$encounter['speechdictation']       = $this->getDictationByEid($params->eid);
		$encounter['hcfaoptions'] = $this->getEncounterHCFAOptionsByEid($params->eid);

        //$this->addEncounterHistoryEvent('Encounter viewed');

		if(!empty($encounter))
        {
            // Audit Log
            // Added by: Gino Rivera
            // Web Jul 31 2013
            // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
            $this->db->AuditLog('Encounter viewed');
			return array('success' => true, 'encounter' => $encounter);
		} else {
			return array('success' => false, 'error' => "Encounter ID $params->eid not found");
		}
	}

	public function getEncounterSummary(stdClass $params)
	{
		$this->setEid($params->eid);
		$this->db->setSQL("SELECT e.*,
								  p.fname,
								  p.mname,
								  p.lname,
								  p.DOB,
								  p.sex
							 FROM encounters AS e
					    LEFT JOIN patient AS p ON e.pid = p.pid
							WHERE e.eid = '$params->eid'");
		$e = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		$e['name'] = Person::fullname($e['fname'],$e['mname'],$e['lname']);
		$e['pic'] = $this->patient->getPatientPhotoSrcIdByPid($e['pid']);
		$e['age'] = $this->patient->getPatientAgeByDOB($e['DOB']);

		//$this->addEncounterHistoryEvent('Encounter viewed');

		if(!empty($e))
        {
            // Audit Log
            // Added by: Gino Rivera
            // Web Jul 31 2013
            // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
            $this->db->AuditLog('Encounter viewed');
			return array('success' => true, 'encounter' => $e);
		} else {
			return array('success' => false, 'error' => "Encounter ID $params->eid not found");
		}
	}

	public function updateEncounterPriority($params)
	{
		$data['priority'] = $params->priority;
		$this->db->setSQL($this->db->sqlBind($data, 'encounters', 'U', array('eid' => $params->eid)));
		$this->db->execLog();
		$this->poolArea->updateCurrentPatientPoolAreaByPid(array('eid' => $params->eid, 'priority' => $params->priority), $params->pid);
        // Audit Log
        // Added by: Gino Rivera
        // Web Jul 31 2013
        // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
        $this->db->AuditLog('Encounter priority updated');
    }

	/**
	 * @param stdClass $params
	 * @return array|mixed
	 */
	public function updateEncounter(stdClass $params)
	{
		return array("success" => true, 'encounter' => $params);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function closeEncounter(stdClass $params)
	{
		$this->setEid($params->eid);
		$data = get_object_vars($params);
		unset($data['eid'], $data['pid'], $data['signature'], $data['note'], $data['reminder']);
		$data['provider_uid'] = $_SESSION['user']['id'];
		$data['supervisor_uid'] = $_SESSION['user']['id'];
		if($this->user->verifyUserPass($params->signature)){
			if($params->note != ''){
				$this->patient->addPatientNoteByPid($params->pid, $params->note, $params->eid);
			}
			if($params->reminder != ''){
				$this->patient->addPatientReminderByPid($params->pid, $params->reminder, $params->eid);
			}
			$this->db->setSQL($this->db->sqlBind($data, 'encounters', 'U', array('eid' => $params->eid)));
			$this->db->execLog();

            //$this->addEncounterHistoryEvent('Encounter Closed');

            // Audit Log
            // Added by: Gino Rivera
            // Web Jul 31 2013
            // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
            $this->db->AuditLog('Encounter closed');

			return array('success' => true, 'data' => $data);
		} else {
			return array('success' => false);
		}

	}

	/******************************************************************************************************************/
	/**
	 * @param $pid
	 * @return array
	 */
	public function getVitalsByPid($pid)
	{
		$this->db->setSQL("SELECT * FROM encounter_vitals WHERE pid = '$pid' ORDER BY date DESC");
		$rows = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$row['height_in']     = intval($row['height_in']);
			$row['height_cm']     = intval($row['height_cm']);
			$row['administer_by'] = $row['uid'] != null ? $this->user->getUserNameById($row['uid']) : '';
			$row['authorized_by'] = $row['auth_uid'] != null ? $this->user->getUserNameById($row['auth_uid']) : '';
			array_push($rows, $row);
		}
		return $rows;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getVitalsByEid($eid)
	{
		$this->db->setSQL("SELECT * FROM encounter_vitals WHERE eid = '$eid' ORDER BY date DESC");
		$rows = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$row['height_in']     = intval($row['height_in']);
			$row['height_cm']     = intval($row['height_cm']);
			$row['administer_by'] = $row['uid'] != null ? $this->user->getUserNameById($row['uid']) : '';
			$row['authorized_by'] = $row['auth_uid'] != null ? $this->user->getUserNameById($row['auth_uid']) : '';
			array_push($rows, $row);
		}
		return $rows;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getVitals(stdClass $params)
	{
		$pid    = (isset($params->pid)) ? $params->pid : $_SESSION['patient']['pid'];
		$vitals = $this->getVitalsByPid($pid);
		if(count($vitals) >= 1){
			return $vitals;
		} else {
			return array();
		}
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addVitals(stdClass $params)
	{
		$this->setEid($params->eid);
		$data = get_object_vars($params);
		unset($data['administer_by'], $data['authorized_by'], $data['id'], $data['bp_diastolic_normal'], $data['bp_systolic_normal']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_vitals', 'I'));
		$this->db->execLog();
		$params->id            = $this->db->lastInsertId;
		$params->administer_by = $this->user->getUserNameById($params->uid);

		//$this->addEncounterHistoryEvent('Vitals added');

        // Audit Log
        // Added by: Gino Rivera
        // Web Jul 31 2013
        // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
        $this->db->AuditLog('Encounter vitals added');

		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateVitals(stdClass $params)
	{
		$this->setEid($params->eid);
		$data  = get_object_vars($params);
		unset($data['date'], $data['administer_by'], $data['authorized_by'], $data['id'], $data['bp_diastolic_normal'], $data['bp_systolic_normal']);
		$sql = $this->db->sqlBind($data, 'encounter_vitals', 'U', array('id' => $params->id));
		$this->db->setSQL($sql);
		$this->db->execLog();
		$params->administer_by = $params->uid != 0 ? $this->user->getUserNameById($params->uid) : '';
		$params->authorized_by = $params->auth_uid != 0 ? $this->user->getUserNameById($params->auth_uid) : '';
		return $params;
	}

	/******************************************************************************************************************/
	/**
	 * @param $eid
	 * @return array
	 */
	public function getSoapByEid($eid)
	{
		$this->db->setSQL("SELECT * FROM encounter_soap WHERE eid = '$eid' ORDER BY date DESC");
		$soap              = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		$soap['icdxCodes'] = $this->diagnosis->getICDByEid($eid, true);
		return $soap;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getSoapHistory(stdClass $params)
	{
		$soap = array();
		$this->db->setSQL("SELECT s.subjective, s.objective, s.assessment, s.plan, e.service_date
							 FROM encounter_soap AS s
					    LEFT JOIN encounters AS e ON s.eid = e.eid
							WHERE s.pid = '$params->pid'
							  AND e.eid != '$params->eid'
				 		 ORDER BY e.service_date DESC");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) AS $row){
			$row['service_date'] = date($_SESSION['global_settings']['date_time_display_format'], strtotime($row['service_date']));
			$icds              = '';
			foreach($this->diagnosis->getICDByEid($params->eid, true) as $code){
				$icds .= '<li><span style="font-weight:bold; text-decoration:none">' . $code['code'] . '</span> - ' . $code['long_desc'] . '</li>';
			}
			$row['assessment'] .= '<ul  class="ProgressNote-ul">' . $icds . '</ul>';
			$row['objective'] .= $this->getObjectiveExtraDataByEid($params->eid);
			$soap[] = $row;
		}
		return $soap;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateSoapById(stdClass $params)
	{
		$this->setEid($params->eid);
		$data = get_object_vars($params);
		unset($data['id'], $data['icdxCodes']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_soap', 'U', "id='" . $params->id . "'"));
		$this->db->execLog();
		$this->updateEncounterIcdxCodes($params);

        //$this->addEncounterHistoryEvent('SOAP updated');

        // Audit Log
        // Added by: Gino Rivera
        // Web Jul 31 2013
        // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
        $this->db->AuditLog('Encounter SOAP updated');

		return $params;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getReviewOfSystemsChecksByEid($eid)
	{
		$this->db->setSQL("SELECT * FROM encounter_review_of_systems_check WHERE eid = '$eid' ORDER BY date DESC");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getReviewOfSystemsByEid($eid)
	{
		$this->db->setSQL("SELECT * FROM encounter_review_of_systems WHERE eid = '$eid' ORDER BY date DESC");
		$record = $this->db->fetchRecord();
		foreach($record as $key => $val){
			$record[$key] = ($val == null) ? 'null' : $val;
		}
		return $record;
	}

	public function getEncounterCodes($params)
	{
		return $this->getEncounterServiceCodesByEid($params->eid);
	}

	public function getEncounterServiceCodesByEid($eid)
	{
		return $this->services->getCptByEid($eid);
	}

    //***********************************************************************************************
    //***********************************************************************************************
    //***********************************************************************************************
    //***********************************************************************************************
    //***********************************************************************************************
    public function getEncounterCptDxTree($params)
    {
        if(isset($params->eid)){
            $services = $this->services->getCptByEid($params->eid);
            foreach($services['rows'] AS $index => $row){
                $dx_children = array();
                $foo = explode(',',$row['dx_pointers']);
                foreach($foo AS $fo){
                    $dx = array();
                    $f = $this->diagnosis->getICDDataByCode($fo);
					if(!empty($f)){
						$dx['code'] = $f['code'];
						$dx['code_text_medium'] = $f['short_desc'];
						$dx['leaf'] = true;
						$dx['iconCls'] = 'icoDotYellow';
						$dx_children[] = $dx;
					}

                }
                $services['rows'][$index]['iconCls'] = 'icoDotGrey';
                $services['rows'][$index]['expanded'] = true;
                $services['rows'][$index]['children'] = $dx_children;
            }
            return $services['rows'];
        }else{
            return;
        }

    }
    public function addEncounterCptDxTree($params)
    {
        $dx_pointers = array();
        $dx_children = array();
        foreach($this->diagnosis->getICDByEid($params->eid, true) AS $dx){
            $dx_children[] = $dx;
            $dx_pointers[] = $dx['code'];
        }
        $service = new stdClass();
        $service->pid = $params->pid;
        $service->eid = $params->eid;
        $service->code = $params->code;
        $service->dx_pointers = implode(',',$dx_pointers);
        $newService = $this->services->addCptCode($service);
        $params->id = $newService['rows']->id;
        $params->dx_children = $dx_children;
        return $params;
    }
    public function updateEncounterCptDxTree($params){

        return $params;
    }
    public function removeEncounterCptDxTree($params)
    {
        $this->services->deleteCptCode($params);
        return $params;
    }
    //***********************************************************************************************
    //***********************************************************************************************
    //***********************************************************************************************
    //***********************************************************************************************
    //***********************************************************************************************

	/**
	 * @param $eid
	 * @return array
	 */
	public function getDictationByEid($eid)
	{
		$this->db->setSQL("SELECT * FROM encounter_dictation WHERE eid = '$eid' ORDER BY date DESC");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

    public function getEncounterIcdxCodes(stdClass $params)
    {
        if(isset($params->eid)){
            $this->setEid($params->eid);
            return $this->diagnosis->getICDByEid($params->eid, true);
        }else{
            return;
        }
    }

	public function updateEncounterIcdxCodes(stdClass $params)
	{
		$this->setEid($params->eid);

        // if $params->icdxCodes is a string explode it by (,)
        $newEncDxCodes =  is_string($params->icdxCodes) ? explode(',',$params->icdxCodes) : $params->icdxCodes;
        // get encounter Dx
        $encDxCodes = $this->diagnosis->getICDByEid($params->eid);

        // loop for each encounter Dx
        foreach($encDxCodes AS $encCode){

            // search for encounter Dx code inside icdxCodes array
            $key = array_search($encCode['code'], $newEncDxCodes);

            // if Dx found in icdxCodes array
            if($key !== false){

                // if encounter Dx is inactive set back to active
                if($encCode['active'] == false){
                    $dx = array();
                    $dx['uid']  = $params->uid;
                    $dx['code'] = trim($encCode['code']);
                    $dx['active'] = '1';
                    $this->db->setSQL($this->db->sqlBind($dx, 'encounter_dx', 'U', array('id' => $encCode['id'])));
                }

                // remove updated Dx code from $newEncDxCodes
                unset($newEncDxCodes[$key]);

            // if not found the set Dx active to 0
            }else{
                $dx = array();
                $dx['active'] = '0';
                $this->db->setSQL($this->db->sqlBind($dx, 'encounter_dx', 'U', array('id' => $encCode['id'])));
            }

            // exe the sql
            $this->db->execOnly();
        }

        // insert the new encounter codes remaining
        foreach($newEncDxCodes AS $newEncDxCode){
            $dx = array();
            $dx['pid']  = $params->pid;
            $dx['eid']  = $params->eid;
            $dx['uid']  = $params->uid;
            $dx['code'] = trim($newEncDxCode);
            $this->db->setSQL($this->db->sqlBind($dx, 'encounter_dx', 'I'));
            $this->db->execOnly();
        }

        $params->icdxCodes = $this->diagnosis->getICDByEid($params->eid, true);

		return $params;
	}

    public function isEncounterDxCodeDByEid($code, $eid){
        $this->db->setSQL("SELECT * FROM encounter_dx WHERE code = '$code' AND eid = '$eid'");
        $dx = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        if(!empty($dx)){
            return $dx;
        }else{
            return false;
        }
    }


	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateReviewOfSystemsChecksById(stdClass $params)
	{
		$this->setEid($params->eid);
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_review_of_systems_check', 'U', "id='" . $params->id . "'"));
		$this->db->execLog();

		//$this->addEncounterHistoryEvent('Review of System Checks updated');

        // Audit Log
        // Added by: Gino Rivera
        // Web Jul 31 2013
        // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
        $this->db->AuditLog('Encounter review of system checks updated');

		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateReviewOfSystemsById(stdClass $params)
	{
		$this->setEid($params->eid);
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_review_of_systems', 'U', "id='" . $params->id . "'"));
		$this->db->execLog();

		//$this->addEncounterHistoryEvent('Review of System updated');

        // Audit Log
        // Added by: Gino Rivera
        // Web Jul 31 2013
        // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
        $this->db->AuditLog('Encounter review of system updated');

		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateDictationById(stdClass $params)
	{
		$this->setEid($params->eid);
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_dictation', 'U', "id='" . $params->id . "'"));
		$this->db->execLog();

		//$this->addEncounterHistoryEvent('Speech Dictation updated');

        // Audit Log
        // Added by: Gino Rivera
        // Web Jul 31 2013
        // GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
        $this->db->AuditLog('Encounter speech dictation updated');

		return $params;
	}

	/**
	 * @param $eid
	 * @return array
	 *  Naming: "closePatientEncounter"
	 */
	public function getProgressNoteByEid($eid)
	{
		$this->db->setSQL("SELECT * FROM encounters WHERE eid = '$eid'");
		$encounter                 = $this->db->fetchRecord(PDO::FETCH_ASSOC);

        $soap['objective'] = '';
        $soap['assessment'] = '';

		$encounter['service_date']   = date('F j, Y, g:i a', strtotime($encounter['service_date']));
		$encounter['patient_name'] = $this->patient->getPatientFullNameByPid($encounter['pid']);
		$encounter['open_by']      = $this->user->getUserNameById($encounter['open_uid']);
		$encounter['signed_by']    = $this->user->getUserNameById($encounter['provider_uid']);
		/**
		 * Add vitals to progress note
		 */
		$vitals = $this->getVitalsByEid($eid);
		if(count($vitals)){
			$encounter['vitals'] = $vitals;
		}
		/**
		 * Add Review of Systems to progress note
		 */
		$ros = $this->getReviewOfSystemsByEid($eid);
		$foo = array();
		foreach($ros as $key => $value){
			if($key != 'id' && $key != 'pid' && $key != 'eid' && $key != 'uid' && $key != 'date'){
				if($value != null && $value != 'null'){
					$value = ($value == 1 || $value == '1') ? 'Yes' : 'No';
					$foo[] = array('name' => $key, 'value' => $value);
				}
			}

		}
		if(!empty($foo)){
			$encounter['reviewofsystems'] = $foo;
		}
		/**
		 * Add Review of Systems Checks to progress note
		 */
		$rosck = $this->getReviewOfSystemsChecksByEid($eid);
		$foo   = array();
		foreach($rosck as $key => $value){
			if($key != 'id' && $key != 'pid' && $key != 'eid' && $key != 'uid' && $key != 'date'){
				if($value != null && $value != 'null' && $value != '0' || $value != 0){
					$value = ($value == 1 || $value == '1') ? 'Yes' : 'No';
					$foo[] = array('name' => $key, 'value' => $value);
				}
			}
		}
		if(!empty($foo)){
			$encounter['reviewofsystemschecks'] = $foo;
		}
		/**
		 * Add SOAP to progress note
		 */
		$icdxs = '';
		foreach($this->diagnosis->getICDByEid($eid, true) as $code){
			$icdxs .= '<li><span style="font-weight:bold; text-decoration:none">' . $code['code'] . '</span> - ' . $code['long_desc'] . '</li>';
		}
		//$icdxs = substr($icdxs, 0, -2);
		$soap = $this->getSoapByEid($eid);
		$soap['objective'] .= $this->getObjectiveExtraDataByEid($eid);
		$soap['assessment'] = $soap['assessment'] . '<ul  class="ProgressNote-ul">' . $icdxs . '</ul>';
		$encounter['soap']  = $soap;
		/**
		 * Add Dictation to progress note
		 */
		$speech = $this->getDictationByEid($eid);
		if($speech['dictation']){
			$encounter['speechdictation'] = $speech;
		}
		/**
		 * return the encounter array of data
		 */
		return $encounter;
	}

	private function getObjectiveExtraDataByEid($eid)
	{
		$ExtraData   = '';
		$medications = $this->medical->getPatientMedicationsByEncounterID($eid);
		if(!empty($medications)){
			$lis = '';
			foreach($medications as $foo){
				$lis .= '<li>' . $foo['medication'] . ' ' . $foo['dose'] . ' ' . $foo['dose_mg'] . '<br>';
				$lis .= 'Instruction: ' . $foo['take_pills'] . ' ' . $foo['type'] . ' ' . $foo['by'] . ' ' . $foo['prescription_often'] . ' ' . $foo['prescription_when'] . '<br>';
				$lis .= 'Dispense: ' . $foo['dispense'] . '  Refill: ' . $foo['refill'] . ' </li>';
			}
			$ExtraData .= '<p>Medications:</p>';
			$ExtraData .= '<ul class="ProgressNote-ul">' . $lis . '</ul>';
		}
		$immunizations = $this->medical->getImmunizationsByEncounterID($eid);
		if(!empty($immunizations)){
			$lis = '';
			foreach($immunizations as $foo){
				$lis .= '<li>Immunization name: ' . $foo['immunization_name'] . '<br>';
				$lis .= 'Immunization ID: ' . $foo['immunization_id'] . '<br>';
				$lis .= 'Manufacturer: ' . $foo['manufacturer'] . '<br>';
				$lis .= 'Lot Number: ' . $foo['lot_number'] . '<br>';
				$lis .= 'Dosis: ' . $foo['dosis'] . '<br>';
				$lis .= 'Administered By: ' . $foo['administered_by'] . ' </li>';
			}
			$ExtraData .= '<p>Immunizations:</p>';
			$ExtraData .= '<ul class="ProgressNote-ul">' . $lis . '</ul>';
		}
		$allergies = $this->medical->getAllergiesByEncounterID($eid);
		if(!empty($allergies)){
			$lis = '';
			foreach($allergies as $foo){
				$lis .= '<li>Allergy: ' . $foo['allergy'] . ' (' . $foo['allergy_type'] . ')<br>';
				$lis .= 'Reaction: ' . $foo['reaction'] . '<br>';
				$lis .= 'Severity: ' . $foo['severity'] . '<br>';
				$lis .= 'Location: ' . $foo['location'] . '<br>';
				$lis .= 'Active?: ' . ($foo['end_date'] != null ? 'Yes' : 'No') . '</li>';
			}
			$ExtraData .= '<p>Allergies:</p>';
			$ExtraData .= '<ul class="ProgressNote-ul">' . $lis . '</ul>';
		}
		$surgeries = $this->medical->getPatientSurgeryByEncounterID($eid);
		if(!empty($surgeries)){
			$lis = '';
			foreach($surgeries as $foo){
				//			    $lis .= '<li>Immunization name: '.$foo['immunization_name'].'<br>';
				//			    $lis .= 'Immunization ID: ' .$foo['immunization_id'].'<br>';
				//			    $lis .= 'Administered By: '.$foo['administered_by'].' </li>';
			}
			$ExtraData .= '<p>Surgeries:</p>';
			$ExtraData .= '<ul class="ProgressNote-ul">' . $lis . '</ul>';
		}
		$dental = $this->medical->getPatientDentalByEncounterID($eid);
		if(!empty($dental)){
			$lis = '';
			foreach($dental as $foo){
				//			    $lis .= '<li>Immunization name: '.$foo['immunization_name'].'<br>';
				//			    $lis .= 'Immunization ID: ' .$foo['immunization_id'].'<br>';
				//			    $lis .= 'Administered By: '.$foo['administered_by'].' </li>';
			}
			$ExtraData .= '<p>Dental:</p>';
			$ExtraData .= '<ul class="ProgressNote-ul">' . $lis . '</ul>';
		}
		$activeProblems = $this->medical->getMedicalIssuesByEncounterID($eid);
		if(!empty($activeProblems)){
			$lis = '';
			foreach($activeProblems as $foo){
				$lis .= '<li>Immunization name: ' . $foo['diagnosis_code'] . '<br>';
				//			    $lis .= 'Immunization ID: ' .$foo['immunization_id'].'<br>';
				//			    $lis .= 'Administered By: '.$foo['administered_by'].' </li>';
			}
			$ExtraData .= '<p>Active Problems:</p>';
			$ExtraData .= '<ul class="ProgressNote-ul">' . $lis . '</ul>';
		}
		$preventiveCare = $this->preventiveCare->getPreventiveCareDismissPatientByEncounterID($eid);
		if(!empty($preventiveCare)){
			$lis = '';
			foreach($preventiveCare as $foo){
				$lis .= '<li>Description: ' . $foo['description'] . '<br>';
				$lis .= 'Reason: ' . $foo['reason'] . '<br>';
				$lis .= 'Observation: ' . $foo['observation'] . ' </li>';
			}
			$ExtraData .= '<p>Preventive Care:</p>';
			$ExtraData .= '<ul class="ProgressNote-ul">' . $lis . '</ul>';
		}
		return $ExtraData;
	}

	protected function addEncounterHistoryEvent($msg)
	{
		$data['eid'] = $this->eid;
		//$data['pid']    = $_SESSION['patient']['pid'];
		$data['date']  = date('Y-m-d H:i:s');
		$data['user']  = $this->user->getCurrentUserTitleLastName();
		$data['event'] = $msg;
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_history', 'I'));
		$this->db->execOnly();
	}

	public function getEncounterEventHistory($params)
	{
        $this->EncounterHistory = MatchaModel::setSenchaModel('App.model.administration.AuditLog');
        return $this->EncounterHistory->load($params)->all();
		//$this->db->setSQL("SELECT * FROM encounter_history WHERE eid = '$params->eid' ORDER BY `date` DESC");
		//return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function checkoutAlerts(stdClass $params)
	{
		$alerts = array();
		$this->db->setSQL("SELECT review_immunizations,
                                  review_allergies,
                                  review_active_problems,
                                  review_surgery,
                                  review_dental,
                                  review_medications,
                                  review_alcohol,
                                  review_smoke,
                                  review_pregnant
                             FROM encounters
                            WHERE eid = '$params->eid'");
		$records = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		foreach($records as $key => $rec){
			if($rec != 0 && $rec != null){
				unset($records[$key]);
			}
		}
		foreach($records as $key => $rec){
			$foo              = array();
			$foo['alert']     = 'Need to ' . str_replace('_', ' ', $key) . ' area';
			$foo['alertType'] = 1;
			$alerts[]         = $foo;
		}
		//TODO: vitals check
		return $alerts;
	}

	/**
	 * @param $date
	 * @return mixed
	 */
	public function parseDate($date)
	{
		return str_replace('T', ' ', $date);
	}

	public function checkForAnOpenedEncounterByPid(stdClass $params)
	{
		$date = strtotime('-1 day', strtotime($params->date));
		$date = date('Y-m-d H:i:s', $date);
		$this->db->setSQL("SELECT * FROM encounters
                           WHERE (pid='$params->pid'
                           AND   close_date is NULL)
                           AND service_date >= '$date'");
		$data = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		if(isset($data['eid'])){
			return true;
		} else {
			return false;
		}

	}

	public function getEncounterFollowUpInfoByEid($eid)
	{
		$this->db->setSQL("SELECT followup_time, followup_facility FROM encounters WHERE eid = '$eid'");
		$rec                      = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		$rec['followup_facility'] = intval($rec['followup_facility']);
		return $rec;
	}

	public function getEncounterMessageByEid($eid)
	{
		$this->db->setSQL("SELECT message FROM encounters WHERE eid = '$eid'");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

	public function getEncounterByDateFromToAndPatient($from, $to, $pid = null)
	{
		$sql = " SELECT encounters.pid,
	                    encounters.eid,
	                    encounters.service_date,
	                    patient.*
	               FROM encounters
	          LEFT JOIN patient ON encounters.pid = patient.pid
	              WHERE encounters.service_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if(isset($pid) && $pid != ''){
			$sql .= " AND encounters.pid = '$pid'";
		}
		$this->db->setSQL($sql);
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function onSaveItemsToReview(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['eid']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounters', 'U', array('eid' => $params->eid)));
		$this->db->execLog();
		return array('success' => true);

	}

	public function addEncounterHCFAOptions(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['eid']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_1500_options', 'U', array('eid' => $params->eid)));
		$this->db->execLog();
		return array('success' => true);
	}

	public function updateEncounterHCFAOptions(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['eid']);
		$this->db->setSQL($this->db->sqlBind($data, 'encounter_1500_options', 'U', array('eid' => $params->eid)));
		$this->db->execLog();
		return array('success' => true);
	}

	public function getEncounterHCFAOptionsByEid($eid)
	{
		$this->db->setSQL("SELECT * FROM encounter_1500_options WHERE eid = '$eid'");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

}


//$params = new stdClass();
//$params->pid = 3;
//$params->eid = 3;
//$params->uid = 85;
//$params->icdxCodes = array('401.9','V22.0','250.90');
//
//$e = new Encounter();
//echo '<pre>';
//$e->updateEncounterIcdxCodes($params);
//print_r($e->getSoapHistoryByPid(1));
