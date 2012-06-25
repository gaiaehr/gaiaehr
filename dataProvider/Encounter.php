<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Encounter.php
 * Date: 1/21/12
 * Time: 3:26 PM
 */
if(!isset($_SESSION)){
    session_name ("GaiaEHR");
    session_start();
    session_cache_limiter('private');
}

include_once($_SESSION['site']['root'].'/classes/dbHelper.php');
include_once($_SESSION['site']['root'].'/dataProvider/Patient.php');
include_once($_SESSION['site']['root'].'/dataProvider/User.php');
include_once($_SESSION['site']['root'].'/dataProvider/Services.php');


class Encounter {
    /**
     * @var dbHelper
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

    function __construct()
    {
        $this->db = new dbHelper();
        $this->user = new User();
        $this->patient = new Patient();
        $this->services = new Services();

        return;
    }

    private function setEid($eid){
        $this->eid = $eid;
    }

    /**
     * @return array
     * NOTES: What is ck?
     *  Naming: "checkOpenEncounters"
    */
    public function checkOpenEncounters()

{
        $fields[] = "*";
        $where[]  = "pid = '".$_SESSION['patient']['pid']."'";
        $where[]  = "close_date IS NULL";

        $this->db->setSQL( $this->db->sqlSelectBuilder("form_data_encounter", $fields, $where) );
        $total = $this->db->rowCount();
        if($total >= 1){
            return array('encounter' => true);
        }else{
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
        $fields[] = "*";
        if(isset($params->sort)){
            $order[$params->sort[0]->direction] = $params->sort[0]->property;
        } else {
            $order['DESC'] = 'start_date';
        }
        $where[] = "pid = '".$_SESSION['patient']['pid']."'";

        $this->db->setSQL( $this->db->sqlSelectBuilder('form_data_encounter', $fields, $where, $order) );
        $rows = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
            $row['status'] = ($row['close_date']== null)? 'open' : 'close';
        	array_push($rows, $row);
        }

        return $rows;
    }
    /**
     * @param stdClass $params
     * @return array
     *  Naming: "getPatientEncounters"
     */
    public function getEncountersPastDue(stdClass $params)
    {
        $fields[] = "*";
        if(isset($params->sort)){
            $order[$params->sort[0]->direction] = $params->sort[0]->property;
        } else {
            $order['DESC'] = 'start_date';
        }
        $where[] = "pid = '".$_SESSION['patient']['pid']."'";

        $this->db->setSQL( $this->db->sqlSelectBuilder('form_data_encounter', $fields, $where, $order) );
        $rows = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
            $row['status'] = ($row['close_date']== null)? 'open' : 'close';
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
        $params->pid        = $_SESSION['patient']['pid'];
        $params->open_uid   = $_SESSION['user']['id'];

        $data = get_object_vars($params);
        foreach($data as $key => $val){
            if($val == '') {
                unset($data[$key]);
            }
        }

        $data['start_date'] = $this->parseDate($data['start_date']);

        $sql = $this->db->sqlBind($data, 'form_data_encounter', 'I');
        $this->db->setSQL($sql);
        $this->db->execLog();
        $eid = $this->db->lastInsertId;

        $default = array('pid'=>$params->pid, 'eid'=>$eid);

        $this->db->setSQL($this->db->sqlBind($default, 'form_data_review_of_systems', 'I'));
        $this->db->execOnly();
        $this->db->setSQL($this->db->sqlBind($default, 'form_data_review_of_systems_check', 'I'));
        $this->db->execOnly();
        $this->db->setSQL($this->db->sqlBind($default, 'form_data_soap', 'I'));
        $this->db->execOnly();
        $this->db->setSQL($this->db->sqlBind($default, 'form_data_dictation', 'I'));
        $this->db->execOnly();

        $params->eid = intval($eid);

        $this->setEid($params->eid);
        $this->addEncounterHistoryEvent('New Encounter Created');

        return array('success'=>true,'encounter'=>$params);
    }

    /**
     * @param stdClass $params
     * @return array|mixed
     *  Naming: "getPatientEncounters"
     */
    public function getEncounter(stdClass $params)
    {
        $this->setEid($params->eid);
        $fields[] = '*';
        $where[]  = "eid = '$params->eid'";

        $this->db->setSQL( $this->db->sqlSelectBuilder('form_data_encounter', $fields, $where) );
        $encounter = $this->db->fetchRecord(PDO::FETCH_ASSOC);

        $encounter['vitals']                = $this->getVitalsByPid($encounter['pid']);
        $encounter['reviewofsystems']       = $this->getReviewOfSystemsByEid($params->eid);
        $encounter['reviewofsystemschecks'] = $this->getReviewOfSystemsChecksByEid($params->eid);
        $encounter['soap']                  = $this->getSoapByEid($params->eid);
        $encounter['speechdictation']       = $this->getDictationByEid($params->eid);
        //$encounter['cptcodes']              = $this->services->getCptByEid($params->eid);


        //$this->addEncounterHistoryEvent('Encounter viewed');

        if($encounter != null){
            return array('success' => true, 'encounter' => $encounter);
        }else{
            return array('success' => false);
        }
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
     *  Naming: "getPatientVitals"
     */
    public function closeEncounter(stdClass $params)
    {
        $this->setEid($params->eid);

        $data['close_date'] = $params->close_date;
        $data['close_uid'] = $_SESSION['user']['id'];

        if($this->user->verifyUserPass($params->signature)){
            $sql = $this->db->sqlBind($data, 'form_data_encounter', 'U', "eid='".$params->eid."'");
            $this->db->setSQL($sql);
            $this->db->execLog();

            $this->addEncounterHistoryEvent('Encounter Closed');

            return array('success'=> true);
        }else{
            return array('success'=> false);
        }


    }

	/******************************************************************************************************************/
    /**
     * @param $pid
     * @return array
     */
    public function getVitalsByPid($pid)
    {
        $this->db->setSQL("SELECT * FROM form_data_vitals WHERE pid = '$pid' ORDER BY date DESC");
        $rows = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
            $row['height_in'] = intval($row['height_in']);
            $row['height_cn'] = intval($row['height_cn']);
            $row['administer_by'] = $this->user->getUserNameById($row['uid']);
	        $row['authorized_by'] = $this->user->getUserNameById($row['auth_uid']);

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
        $this->db->setSQL("SELECT * FROM form_data_vitals WHERE eid = '$eid' ORDER BY date DESC");
        $rows = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
            $row['height_in'] = intval($row['height_in']);
            $row['height_cn'] = intval($row['height_cn']);
            $row['administer_by'] = $this->user->getUserNameById($row['uid']);
            $row['authorized_by'] = $this->user->getUserNameById($row['auth_uid']);

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
        $pid =  (isset($params->pid)) ? $params->pid : $_SESSION['patient']['pid'];
        $vitals = $this->getVitalsByPid($pid);
        if(count($vitals) >= 1){
            return $vitals;
        }else{
            return array("success" => true);
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
        unset($data['administer_by'],$data['authorized_by'],$data['id'],$data['bp_diastolic_normal'],$data['bp_systolic_normal']);
        $data['date'] = $this->parseDate($data['date']);
        $sql = $this->db->sqlBind($data, 'form_data_vitals', 'I');
        $this->db->setSQL($sql);
        $this->db->execLog();
	    $params->administer_by = $this->user->getUserNameById($params->uid);
        $this->addEncounterHistoryEvent('Vitals added');
        return $params;
    }
	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateVitals(stdClass $params){
		$this->setEid($params->eid);

		$params->date = $this->parseDate($params->date);

		$data = get_object_vars($params);
        unset($data['date'],$data['administer_by'],$data['authorized_by'],$data['id'],$data['bp_diastolic_normal'],$data['bp_systolic_normal']);

		$sql = $this->db->sqlBind($data, 'form_data_vitals', 'U', "id='$params->id'");
        $this->db->setSQL($sql);
        $this->db->execLog();

		$params->administer_by = $this->user->getUserNameById($params->uid);
		$params->authorized_by = $this->user->getUserNameById($params->auth_uid);

		return $params;
	}

	/******************************************************************************************************************/
    /**
     * @param $eid
     * @return array
     */
    public function getSoapByEid($eid)
    {
        $this->db->setSQL("SELECT * FROM form_data_soap WHERE eid = '$eid' ORDER BY date DESC");
        $soap = $this->db->fetchRecords(PDO::FETCH_ASSOC);

        $icdxs = array();
        foreach($this->services->getIcdxByEid($eid) as $code){
            $icdxs[] = $code;
        }
        $soap[0]['icdxCodes'] = $icdxs;
        return $soap;
    }
	/**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateSoapById(stdClass $params){

        $this->setEid($params->eid);

        $data = get_object_vars($params);
        unset($data['id'],$data['icdxCodes']);
        $this->db->setSQL($this->db->sqlBind($data, 'form_data_soap', 'U', "id='".$params->id."'"));
        $this->db->execLog();

        $this->db->setSQL("DELETE FROM encounter_codes_icdx WHERE eid = '$params->eid'");
        $this->db->execOnly();

        $this->updateEncounterIcdxCodes($params);


        $this->addEncounterHistoryEvent('SOAP updated');
        return $params;
    }


    /**
     * @param $eid
     * @return array
     */
    public function getReviewOfSystemsChecksByEid($eid)
    {
        $this->db->setSQL("SELECT * FROM form_data_review_of_systems_check WHERE eid = '$eid' ORDER BY date DESC");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }
    /**
     * @param $eid
     * @return array
     */
    public function getReviewOfSystemsByEid($eid)
    {
        $this->db->setSQL("SELECT * FROM form_data_review_of_systems WHERE eid = '$eid' ORDER BY date DESC");
        $record = $this->db->fetchRecord();
        foreach($record as $key => $val){
            $record[$key] =  ($val == null)? 'null' : $val;
        }
        return $record;
    }


    public function getEncounterCodes($params)
    {
        $records = array();
        foreach($this->services->getIcdxByEid($params->eid) as $fo){
            $fo['type'] = 'ICD';
            $records[] = $fo;
        }
        $foo = $this->services->getCptByEid($params->eid);
        foreach($foo['rows'] as $fo){
            $fo['type'] = 'CPT';
            $records[] = $fo;
        }
        $foo = $this->services->getHCPCByEid($params->eid);
        foreach($foo['rows'] as $fo){
            $fo['type'] = 'HCPC';
            $records[] = $fo;
        }

        return $records;
    }


    /**
     * @param $eid
     * @return array
     */
    public function getDictationByEid($eid)
    {
        $this->db->setSQL("SELECT * FROM form_data_dictation WHERE eid = '$eid' ORDER BY date DESC");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }


    public function updateEncounterIcdxCodes(stdClass $params){
        $this->setEid($params->eid);

        if(!is_string($params->icdxCodes)){
            foreach($params->icdxCodes as $icdcCode){
                $icdc['eid'] = $params->eid;
                $icdc['code'] = trim($icdcCode);
                $this->db->setSQL($this->db->sqlBind($icdc, 'encounter_codes_icdx', 'I'));
                $this->db->execOnly();
            }
        }else{
            $icdc['eid'] = $params->eid;
            $icdc['code'] = trim($params->icdxCodes);
            $this->db->setSQL($this->db->sqlBind($icdc, 'encounter_codes_icdx', 'I'));
            $this->db->execOnly();
        }

        return $params;
    }

    public function getEncounterIcdxCodes(stdClass $params){
        $this->setEid($params->eid);
        return $this->services->getIcdxByEid($params->eid);
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
        $this->db->setSQL($this->db->sqlBind($data, 'form_data_review_of_systems_check', 'U', "id='".$params->id."'"));
        $this->db->execLog();

        $this->addEncounterHistoryEvent('Review of System Checks updated');
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
        $this->db->setSQL($this->db->sqlBind($data, 'form_data_review_of_systems', 'U', "id='".$params->id."'"));
        $this->db->execLog();

        $this->addEncounterHistoryEvent('Review of System updated');
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
        $this->db->setSQL($this->db->sqlBind($data, 'form_data_dictation', 'U', "id='".$params->id."'"));
        $this->db->execLog();

        $this->addEncounterHistoryEvent('Speech Dictation updated');
        return $params;
    }
    /**
     * @param $eid
     * @return array
     *  Naming: "closePatientEncounter"
     */
    public function getProgressNoteByEid($eid)
    {
        $this->db->setSQL("SELECT * FROM form_data_encounter WHERE eid = '$eid'");
        $encounter = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        $encounter['start_date']            = date('F j, Y, g:i a',strtotime($encounter['start_date']));
        $encounter['patient_name']          = $this->patient->getPatientFullNameByPid($encounter['pid']);
        $encounter['open_by']               = $this->user->getUserNameById($encounter['open_uid']);
        $encounter['signed_by']             = $this->user->getUserNameById($encounter['close_uid']);

        /**
         * Add vitals to progress note
         */
        $vitals = $this->getVitalsByEid($eid);
        if(count($vitals)) $encounter['vitals'] = $vitals;

        /**
         * Add Review of Systems to progress note
         */
        $ros = $this->getReviewOfSystemsByEid($eid);
        $foo = array();
        foreach($ros as $key => $value){
            if($key != 'id' && $key != 'pid' && $key != 'eid' && $key != 'uid' && $key != 'date'){
                if($value != null && $value != 'null'){
                    $value = ($value == 1 || $value == '1')? 'Yes' : 'No';
                    $foo[] = array('name' => $key, 'value' => $value);
                }
            }

        }
        if(!empty($foo)) $encounter['reviewofsystems'] = $foo;

        /**
         * Add Review of Systems Checks to progress note
         */
        $rosck = $this->getReviewOfSystemsChecksByEid($eid);
        $foo = array();
        foreach($rosck[0] as $key => $value){
            if($key != 'id' && $key != 'pid' && $key != 'eid' && $key != 'uid' && $key != 'date'){
                if($value != null && $value != 'null' && $value != '0' || $value != 0){
                    $value = ($value == 1 || $value == '1')? 'Yes' : 'No';
                    $foo[] = array('name' => $key, 'value' => $value);
                }
            }
        }
        if(!empty($foo)) $encounter['reviewofsystemschecks'] = $foo;

        /**
         * Add SOAP to progress note
         */
        $icdxs = '';
        foreach($this->services->getIcdxByEid($eid) as $code){
            $icdxs .= $code['code'].', ';
        }

        $icdxs = substr($icdxs, 0, -2);
        $soap = $this->getSoapByEid($eid);
        $soap[0]['assessment'] = $soap[0]['assessment'].' <span style="font-weight:bold; text-decoration:none">[ '.$icdxs.' ]</span> ';
        $encounter['soap'] = $soap;

        /**
         * Add Dictation to progress note
         */
        $speech = $this->getDictationByEid($eid);
        if($speech[0]['dictation']){
            $encounter['speechdictation'] = $speech;
        }

        /**
         * return the encounter array of data
         */
        return $encounter;
    }

    protected function addEncounterHistoryEvent($msg){
        $data['eid']    = $this->eid;
        //$data['pid']    = $_SESSION['patient']['pid'];
        $data['date']   = date('Y-m-d H:i:s');
        $data['user']   = $this->user->getCurrentUserTitleLastName();
        $data['event']  = $msg;
        $this->db->setSQL($this->db->sqlBind($data, 'encounter_history', 'I'));
        $this->db->execOnly();
    }

    public function getEncounterEventHistory($params){
        $this->db->setSQL("SELECT * FROM encounter_history WHERE eid = '$params->eid' ORDER BY `date` DESC");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

    public function checkoutAlerts(stdClass $params){
        $alerts = array();
        $this->db->setSQL("SELECT review_immunizations,
                                  review_allergies,
                                  review_active_problems,
                                  review_surgery,
                                  review_dental,
                                  review_medications
                             FROM form_data_encounter
                            WHERE eid = '$params->eid'");
        $records = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        foreach($records as $key =>$rec){
            if($rec == 1){
                unset($records[$key]);
            }
        }
        foreach($records as $key =>$rec){
	        $foo = array();
	        $foo['alert'] = 'Need to '.str_replace('_', ' ', $key).' area' ;
	        $foo['alertType'] = 1 ;
	        $alerts[] = $foo;
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
        $this->db->setSQL("SELECT * FROM form_data_encounter
                           WHERE (pid='$params->pid'
                           AND   close_date is NULL)
                           AND start_date >= '$date'");
        $data = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        if(isset($data['eid'])){
            return true;
        }else{
            return false;
        }

    }


//
}
//
//$params = new stdClass();
//$params->pid = 2;
//$params->date = '2012-06-25 10:48:00';
//
//$e = new Encounter();
//echo '<pre>';
//print_r($e->checkForAnOpenedEncounterByPid($params));
