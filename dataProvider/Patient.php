<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: patient.class.php
 * Date: 1/13/12
 * Time: 7:10 AM
 */
if(!isset($_SESSION)) {
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/dataProvider/Person.php');
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
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

	function __construct()
	{
		$this->db   = new dbHelper();
		$this->user = new User();
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
		$_SESSION['patient']['pid']  = $params->pid;
		$_SESSION['patient']['name'] = $this->getPatientFullNameByPid($params->pid);
		return;
	}

	/**
	 * @return mixed
	 */
	public function currPatientUnset()
	{
		$_SESSION['patient']['pid']  = null;
		$_SESSION['patient']['name'] = null;
		return;
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
		}
		;
		$this->createPatientQrCode($pid, $patient['fullname']);
		$this->createDefaultPhotoId($pid);
		return array('success' => true, 'patient'=> array('pid'=> $pid, 'fullname' => $patient['fullname']));
	}

	public function createNewPatientOnlyName($name)
	{
		$patient       = new stdClass();
		$foo           = explode(' ', $name);
		$data['fname'] = $foo[0];
		$data['mname'] = (isset($foo[1])) ? $foo[1] : '';
		unset($foo[0], $foo[1]);
		$data['lname'] = '';
		foreach($foo as $fo) {
			$patient->lname .= $fo;
		}
		$this->db->setSQL($this->db->sqlBind($data, 'form_data_demographics', 'I'));
		$this->db->execLog();
		$pid = $this->db->lastInsertId;
		if(!$this->createPatientDir($pid)) {
			return array('success' => false, 'error'=> 'Patient directory failed');
		}
		;
		$this->createPatientQrCode($pid, $patient['fullname']);
		return $pid;

	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function updatePatientDemographicData(stdClass $params)
	{
		$this->createPatientQrCode($params->pid, Person::fullname($params->fname, $params->mname, $params->lname));

		$data = get_object_vars($params);
		unset($data['pid']);
		foreach($data as $key => $val) {
			if($val == null) unset($data[$key]);
			if($val === false) $data[$key] = 0;
			if($val === true) $data[$key] = 1;
		}
		$this->db->setSQL($this->db->sqlBind($data, 'form_data_demographics', 'U', array('pid' => $params->pid)));
		$this->db->execLog();
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

	/**
	 * Form now this is just getting the latest open encounter for all the patients.
	 * TODO: create the table to handle tha pool area and fix this function
	 *
	 * @return array
	 */
	public function getPatientsByPoolArea()
	{
		//public function getPatientsByPoolArea(stdClass $params){
		$rows = array();
		$this->db->setSQL("SELECT DISTINCT p.pid, p.title, p.fname, p.mname, p.lname, MAX(e.eid)
                         FROM form_data_demographics AS p
                   RIGHT JOIN form_data_encounter AS e
                           ON p.pid = e.pid
                        WHERE e.close_date IS NULL
                     GROUP BY p.pid LIMIT 6");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
			$foo['name']      = Person::fullname($row['fname'], $row['mname'], $row['lname']);
			$foo['shortName'] = Person::ellipsis($foo['name'], 20);
			$foo['pid']       = $row['pid'];
			$foo['eid']       = $row['MAX(e.eid)'];
			$foo['img']       = 'ui_icons/user_32.png';
			array_push($rows, $foo);
		}
		return $rows;
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

	public function addNote(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		foreach($data as $key => $val) {
			if($key == 'note_body') {
				$note_body = $val;
				unset($data[$key]);
			}
			if($key == 'reminder_body') {
				$reminder_body = $val;
				unset($data[$key]);
			}
		}
		$data2 = $data;
		$data['body']  = $note_body;
		$data2['body'] = $reminder_body;
		$this->db->setSQL($this->db->sqlBind($data2, "patient_reminders", "I"));
		$this->db->execLog();
		$this->db->setSQL($this->db->sqlBind($data, "patient_notes", "I"));
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
		return $age;
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
		$this->db->setSQL("SELECT pregnant
                           FROM form_data_demographics
                           WHERE pid ='$pid'");
		$p = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $p['pregnant'];

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
//print_r($p->createNewPatientWithName('Ernesto J Rodriguez'));