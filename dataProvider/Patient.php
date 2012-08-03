<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: patient.class.php
 * Date: 1/13/12
 * Time: 7:10 AM
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/dataProvider/Person.php');
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/classes/Time.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/ACL.php');
//include_once($_SESSION['site']['root'] . '/dataProvider/PoolArea.php');
class Patient
{
	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var PoolArea
	 */
	//private $poolArea;

	function __construct()
	{
		$this->db   = new dbHelper();
		$this->user = new User();
		$this->acl = new ACL();
		//$this->poolArea = new PoolArea();
		return;
	}

	/**
	 * @return mixed
	 */
	protected function getCurrPid()
	{
		return $_SESSION['patient']['pid'];
	}

	/**
	 * @param \stdClass $params
	 * @internal param $pid
	 * @return mixed
	 */
	public function currPatientSet(stdClass $params)
	{
		include_once($_SESSION['site']['root'] . '/dataProvider/PoolArea.php');
		$poolArea = new PoolArea();
		$_SESSION['patient']['pid']  = $params->pid;
		$_SESSION['patient']['name'] = $this->getPatientFullNameByPid($params->pid);
		$p = $this->isPatientChartOutByPid($params->pid);
		if($p === false){
			$area = $poolArea->getCurrentPatientPoolAreaByPid($params->pid);
			$this->patientChartOutByPid($params->pid, $area['area_id']);
			$_SESSION['patient']['readOnly'] = false;
			return array('readOnly' => false);
		}else{
			$_SESSION['patient']['readOnly'] = true;
			return array('readOnly' => true,
			             'overrideReadOnly' => $this->acl->hasPermission('override_readonly'),
			             'user' => $this->user->getUserFullNameById($p['uid']),
			             'area' => $poolArea->getAreaTitleById($p['pool_area_id']),
			             'array'=>$p);
		}
	}

	/**
	 * @return mixed
	 */
	public function currPatientUnset()
	{
		$this->patientChartInByPid($_SESSION['patient']['pid']);
		$_SESSION['patient']['pid']  = null;
		$_SESSION['patient']['name'] = null;
		return;
	}

	public function isCurrPatientOnReadMode(){
		return $_SESSION['patient']['readOnly'];
	}

	public function createNewPatient(stdClass $params)
	{
		$data = get_object_vars($params);
		foreach($data as $key => $val) {
			if($val == null) unset($data[$key]);
			if($val === false) $data[$key] = 0;
			if($val === true) $data[$key] = 1;
		}
		$this->db->setSQL($this->db->sqlBind($data, 'form_data_demographics', 'I'));
		$this->db->execLog();
		$pid = $this->db->lastInsertId;
		$this->db->setSQL("SELECT pid, fname, mname, lname
                     FROM form_data_demographics
                    WHERE pid = '$pid'");
		$patient             = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		$patient['fullname'] = Person::fullname($patient['fname'], $patient['mname'], $patient['lname']);
		if(!$this->createPatientDir($pid)) {
			return array("success" => false, "error"=> 'Patient directory failed');
		};
		$this->createPatientQrCode($pid, $patient['fullname']);
		$this->createDefaultPhotoId($pid);
		return array('success' => true, 'patient'=> array('pid'=> $pid, 'fullname' => $patient['fullname']));
	}

	public function createNewPatientOnlyName($name)
	{

		$data = array();
		$foo           = explode(' ', $name);

		$data['fname'] = trim($foo[0]);
		if(count($foo) == 2){
			$data['lname'] = trim($foo[1]);
		}elseif(count($foo) >= 3){
			$data['mname'] = (isset($foo[1])) ? trim($foo[1]) : '';
			unset($foo[0], $foo[1]);
			$data['lname'] = '';
			foreach($foo as $fo) {
				$data['lname'] .= $data['lname']. ' ' . $fo . ' ';
			}
		}

		$this->db->setSQL($this->db->sqlBind($data, 'form_data_demographics', 'I'));
		$this->db->execLog();
		$pid = $this->db->lastInsertId;
		if(!$this->createPatientDir($pid)) {
			return array('success' => false, 'error'=> 'Patient directory failed');
		}
		$this->createPatientQrCode($pid, $name);
		$this->createDefaultPhotoId($pid);
		return array('success' => true, 'patient'=> array('pid'=> $pid, 'fullname' => $name));
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function updatePatientDemographicData(stdClass $params)
	{

		$data = get_object_vars($params);
		unset($data['pid']);
		foreach($data as $key => $val) {
			if($val == null) unset($data[$key]);
			if($val === false) $data[$key] = 0;
			if($val === true) $data[$key] = 1;
		}
		$this->db->setSQL($this->db->sqlBind($data, 'form_data_demographics', 'U', array('pid' => $params->pid)));
		$this->db->execLog();

		$faullname = $params->fname . ' ' . $params->mname . ' ' . $params->lname;

		$this->createPatientQrCode($params->pid, Person::fullname($params->fname, $params->mname, $params->lname));


		return $params;
	}

	/**
	 * @param $pid
	 * @return string
	 */
	public function getPatientFullNameByPid($pid)
	{
		$this->db->setSQL("SELECT fname,mname,lname FROM form_data_demographics WHERE pid = '$pid'");
		$p = $this->db->fetchRecord();
		return Person::fullname($p['fname'], $p['mname'], $p['lname']);
	}

	public function getDOBByPid($pid)
	{
		$this->db->setSQL("SELECT DOB FROM form_data_demographics WHERE pid = '$pid'");
		$p = $this->db->fetchRecord();
		return $p['DOB'];
	}

	public function getPatientSexIntByPid($pid)
	{
		$this->db->setSQL("SELECT sex FROM form_data_demographics WHERE pid = '$pid'");
		$p   = $this->db->fetchRecord();
		$sex = (strtolower($p['sex']) == strtolower('FEMALE')) ? 1 : 2;
		return $sex;
	}

	/**
	 * @param \stdClass $params
	 * @internal param $search
	 * @internal param $start
	 * @internal param $limit
	 * @return array
	 */
	public function patientLiveSearch(stdClass $params)
	{
		$this->db->setSQL("SELECT pid,pubpid,fname,lname,mname,DOB,SS
                             FROM form_data_demographics
                            WHERE fname LIKE '$params->query%'
                               OR lname LIKE '$params->query%'
                               OR mname LIKE '$params->query%'
                               OR pid 	LIKE '$params->query%'
                               OR SS 	LIKE '%$params->query'");
		$rows = array();
		foreach($this->db->fetchRecords(PDO::FETCH_CLASS) as $row) {
			$row->fullname = Person::fullname($row->fname, $row->mname, $row->lname);
			unset($row->fname, $row->mname, $row->lname);
			array_push($rows, $row);
		}
		$total = count($rows);
		$rows  = $this->db->filterByStartLimit($rows, $params);
		return array('totals'=> $total, 'rows'=> $rows);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getPatientDemographicData(stdClass $params)
	{
		$pid = $_SESSION['patient']['pid'];
		$this->db->setSQL("SELECT * FROM form_data_demographics WHERE pid = '$pid'");
		$rows = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
			array_push($rows, $row);
		}
		return $rows;

	}

	/**
	 * @param $pid
	 * @return array
	 */
	public function getPatientDemographicDataByPid($pid)
	{
		$this->db->setSQL("SELECT * FROM form_data_demographics WHERE pid = '$pid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);

	}

	private function createPatientDir($pid)
	{
		$root = $_SESSION['site']['root'];
		$site = $_SESSION['site']['site'];
		$path = $root . '/sites/' . $site . '/patients/' . $pid;
		if(!file_exists($path)) {
			if(mkdir($path, 0777, true)) {
				chmod($path, 0777);
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	public function createPatientQrCode($pid, $fullname)
	{
		//set it to writable location, a place for temp generated PNG files
		$root         = $_SESSION['site']['root'];
		$site         = $_SESSION['site']['site'];
		$path         = $root . '/sites/' . $site . '/patients/' . $pid;
		$data         = '{"name":"' . $fullname . '","pid":' . $pid . ',"ehr": "GaiaEHR"}';
		$PNG_TEMP_DIR = $path;
		include($root . "/lib/phpqrcode/qrlib.php");
		$filename = $PNG_TEMP_DIR . '/patientDataQrCode.png';
		QRcode::png($data, $filename, 'Q', 2, 2);
	}

	public function createDefaultPhotoId($pid){
		$root = $_SESSION['site']['root'];
		$site = $_SESSION['site']['site'];
		$newImg = $root . '/sites/' . $site . '/patients/' . $pid .'/patientPhotoId.jpg';
		copy($root.'/ui_icons/patientPhotoId.jpg', $newImg);
		return;
	}

	public function getPatientAddressById($pid)
	{
		$this->db->setSQL("SELECT * FROM form_data_demographics WHERE pid = '$pid'");
		$p       = $this->db->fetchRecord();
		$address = $p['address'] . ' <br>' . $p['city'] . ',  ' . $p['state'] . ' ' . $p['country'];
		return $address;
	}

	public function getPatientArrivalLogWarningByPid($pid)
	{
		$this->db->setSQL("SELECT pid
							 FROM form_data_demographics
							WHERE pid = '$pid'
							  AND (sex IS NULL
							  OR DOB IS NULL)");
		$alert = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return !empty($alert);
	}



	public function addPatientNoteAndReminder(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id'],$data['message']);
		$data2 = $data;
		$data['body'] = $data['new_reminder'];
		$data2['body'] = $data['new_note'];

		unset($data['new_note'],$data['new_reminder'],$data2['new_note'],$data2['new_reminder']);

		$this->db->setSQL($this->db->sqlBind($data2, 'patient_reminders', 'I'));
		$this->db->execLog();
		$this->db->setSQL($this->db->sqlBind($data, 'patient_notes', 'I'));
		$this->db->execLog();
		if($this->db->lastInsertId == 0) {
			return array('success' => false);
		} else {
			return array('success' => true);
		}
	}

	public function getPatientNotes(stdClass $params)
	{
		$notes = array();
		$this->db->setSQL("SELECT * FROM patient_notes WHERE pid = '$params->pid'");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
			$row['user_name'] = $this->user->getUserNameById($row['uid']);
			$notes[]          = $row;
		}
		return $notes;
	}

	public function getPatientReminders(stdClass $params)
	{
		$reminders = array();
		$this->db->setSQL("SELECT * FROM patient_reminders WHERE pid = '$params->pid'");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
			$row['user_name'] = $this->user->getUserNameById($row['uid']);
			$reminders[]      = $row;
		}
		return $reminders;
	}

	///////////////////////////////////////////////////////
	public function getPatientDOBByPid($pid)
	{
		$this->db->setSQL("SELECT DOB
                           FROM form_data_demographics
                           WHERE pid ='$pid'");
		$patient = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $patient['DOB'];
	}

	/**
	 * @param $dob
	 * @internal param $birthday
	 * @return array
	 */
	public function getPatientAgeByDOB($dob)
	{
		$today             = new DateTime(date("Y-m-d"));
		$appt              = new DateTime(date($dob));
		$days_until_appt   = $appt->diff($today)->d;
		$months_until_appt = $appt->diff($today)->m;
		$years_until_appt  = $appt->diff($today)->y;
		$age['days']       = $days_until_appt;
		$age['months']     = $months_until_appt;
		$age['years']      = $years_until_appt;
		return array('age'=>(($age['years'] != 0 || $age['months'] !=0 )?(($age['years'] != 0)?(($age['years']==1)?$age['years'].' year':$age['years'.' year']): (($age['months']==1)?$age['months'].' month':$age['months'].' months')):(($age['days']==1)?$age['days'].' day':$age['days'].' days')),
                     'DMY'=>$age);
	}

	/**
	 * @param $pid
	 * @return array
	 */
	public function getPatientSexByPid($pid)
	{
		$this->db->setSQL("SELECT sex
                           FROM form_data_demographics
                           WHERE pid ='$pid'");
		$p = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $p['sex'];
	}

	public function getPatientPregnantStatusByPid($pid)
	{
		$this->db->setSQL("SELECT *
                           FROM form_data_encounter
                           WHERE pid ='$pid'
                           ORDER BY eid desc ");
		$p = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $p['review_pregnant'];

	}

	public function getPatientActiveProblemsById($pid, $tablexx, $columnName)
	{
		$this->db->setSQL("SELECT $columnName
                           FROM $tablexx
                           WHERE pid ='$pid'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec) {
			if($rec['end_date'] != null || $rec['end_date'] != '0000-00-00 00:00:00') {
				$records[] = $rec;
			}
		}
		return $records;
	}

	public function getPatientDocuments(stdClass $params)
	{
        $records = array();
        if(isset($params->eid)){
            $this->db->setSQL("SELECT * FROM patient_documents WHERE eid = '$params->eid'");
            foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
                $row['user_name'] = $this->user->getUserNameById($row['uid']);
                $records[]      = $row;
            }
        }elseif(isset($params->pid)){
            $this->db->setSQL("SELECT * FROM patient_documents WHERE pid = '$params->pid'");
            foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
                $row['user_name'] = $this->user->getUserNameById($row['uid']);
                $records[]      = $row;
            }
        }

        return $records;
	}

    private function getPatientSurgeryByPatientID($pid)
    {
        $this->db->setSQL("SELECT * FROM patient_surgery WHERE pid='$pid'");
        $records = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $rec){
            $rec['alert'] = ($rec['end_date']== null || $rec['end_date'] == '0000-00-00 00:00:00') ? 1 : 0 ;
            $records[]= $rec;
        }

        return $records;
    }
    public function getPatientInsurancesCardsUrlByPid($pid)
    {
		$records =array();
        $this->db->setSQL("SELECT url
                           FROM patient_documents
                           WHERE pid='$pid'
                           AND  docType='Primary Insurance'
                           ORDER BY id DESC");
	    $records['Primary']= $this->db->fetchRecord(PDO::FETCH_ASSOC);

        $this->db->setSQL("SELECT url
                           FROM patient_documents
                           WHERE pid='$pid'
                           AND  docType='Secondary Insurance'
                           ORDER BY id DESC");
	    $records['Secondary']= $this->db->fetchRecord(PDO::FETCH_ASSOC);

        $this->db->setSQL("SELECT url
                           FROM patient_documents
                           WHERE pid='$pid'
                           AND  docType='Tertiary Insurance'
                           ORDER BY id DESC");
	    $records['Tertiary']= $this->db->fetchRecord(PDO::FETCH_ASSOC);


        return $records;
    }

    public function getMeaningfulUserAlertByPid(stdClass $params)
	{
        $record = array();
        $this->db->setSQL("SELECT lenguage,
                                  race,
                                  ethnicity,
                                  fname,
                                  lname,
                                  sex,
                                  DOB
                           FROM form_data_demographics
                           WHERE pid = '$params->pid'");
        $patientdata = $this->db->fetchRecord(PDO::FETCH_ASSOC);

        foreach($patientdata as $key => $val){
            $val = ($val == null || $val == '') ? false : true;
            $record[] = array('name' => $key, 'val' => $val);
        }

        return $record;
	}

	public function addPatientNoteByPid($pid, $body, $eid = null){
		$data['pid'] = $pid;
		$data['uid'] = $_SESSION['user']['id'];
		$data['date'] = Time::getLocalTime();
		$data['body'] = $body;
		$data['eid'] = $eid;

		$this->db->setSQL($this->db->sqlBind($data, 'patient_notes', 'I'));
		$this->db->execLog();
		return;
	}

	public function addPatientReminderByPid($pid, $body, $eid = null){
		$data['pid'] = $pid;
		$data['uid'] = $_SESSION['user']['id'];
		$data['date'] = Time::getLocalTime();
		$data['body'] = $body;
		$data['eid'] = $eid;

		$this->db->setSQL($this->db->sqlBind($data, 'patient_reminders', 'I'));
		$this->db->execLog();
		return;
	}

	public function getPatientPhotoSrcIdByPid($pid = null){
		$pid = ($pid == null) ? $_SESSION['patient']['pid'] : $pid;
		$site = $_SESSION['site']['site'];
		return  'sites/' . $site . '/patients/' . $pid .'/patientPhotoId.jpg';
	}


	//******************************************************************************************************************
	//******************************************************************************************************************

	public function patientChartOutByPid($pid, $pool_area_id){
		$data['pid'] = $pid;
		$data['uid'] = $_SESSION['user']['id'];
		$data['chart_out_time'] = Time::getLocalTime();
		$data['pool_area_id'] = $pool_area_id;
		$this->db->setSQL($this->db->sqlBind($data, 'patient_out_chart', 'I'));
		$this->db->execLog();
	}

	public function patientChartInByPid($pid){
		if(!$_SESSION['patient']['readOnly']){
		$chart_in_time = Time::getLocalTime();
		$this->db->setSQL("UPDATE patient_out_chart SET chart_in_time = '$chart_in_time' WHERE pid = $pid AND chart_in_time IS NULL");
		$this->db->execLog();
		}
	}

	public function patientChartInByUserId($uid){
		$chart_in_time = Time::getLocalTime();
		$this->db->setSQL("UPDATE patient_out_chart SET chart_in_time = '$chart_in_time' WHERE uid = $uid AND chart_in_time IS NULL");
		$this->db->execLog();
	}

	public function isPatientChartOutByPid($pid){
		$this->db->setSQL("SELECT id, uid, pool_area_id FROM patient_out_chart WHERE pid = '$pid' AND chart_in_time IS NULL");
		$chart = $this->db->fetchRecord();
		if(empty($chart)){
			return false;
		}else{
			return $chart;
		}
	}





	//******************************************************************************************************************
	//******************************************************************************************************************

	/**
	 * @param $date
	 * @return mixed
	 */
	private function parseDate($date)
	{
		return str_replace('T', ' ', $date);
	}

}
//$p = new Patient();
//echo '<pre>';
////print_r($p->getPatientArrivalLogWarningByPid(2));
//print $p->getPatientArrivalLogWarningByPid(1);
