<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

include_once(ROOT . '/dataProvider/Person.php');
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/ACL.php');
include_once(ROOT . '/dataProvider/PatientContacts.php');

class Patient {

	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var
	 */
	private $patient;

	/**
	 * @var MatchaCUP
	 */
	public $p;
	/**
	 * @var MatchaCUP
	 */
	private $e;
	/**
	 * @var MatchaCUP
	 */
	private $d;
	/**
	 * @var MatchaCUP
	 */
	private $c;

    /**
     * @var MatchaCUP
     */
    private $patientContacts;

	/**
	 * @var PoolArea
	 */
	//private $poolArea;
	function __construct($pid = null) {
		$this->user = new User();
		$this->acl = new ACL();
		$this->setPatient($pid);
		return;
	}

	/**
	 * @return MatchaCUP
	 */
	public function setPatientModel() {
        if(!isset($this->p))
            return $this->p = MatchaModel::setSenchaModel('App.model.patient.Patient');
	}

	/**
	 * @return MatchaCUP
	 */
	public function setDocumentModel() {
		if($this->d == null){
			return $this->d = MatchaModel::setSenchaModel('App.model.patient.PatientDocuments');
		}
		return $this->d;
	}

	/**
	 * @return MatchaCUP
	 */
	public function setChartCheckoutModel() {
		if($this->c == null){
			return $this->c = MatchaModel::setSenchaModel('App.model.patient.PatientChartCheckOut');
		}
		return $this->c;
	}

	/**
	 * @return MatchaCUP
	 */
	public function setPatientEncounterModel() {
		if($this->e == null){
			$this->e = MatchaModel::setSenchaModel('App.model.patient.Encounter');
		}
		return $this->e;
	}

	/**
	 * @param stdClass $params
	 *
	 * @return mixed
	 */
	public function getPatients($params) {
		$this->setPatientModel();
        $Records = $this->p->load($params)->all();
        // Compile custom fields
        foreach($Records as $Index => $Record){
            $Records[$Index]['name'] = Person::fullname($Record['fname'], $Record['mname'], $Record['lname']);
        }
		return $Records;
	}

	/**
	 * @param stdClass $params
	 *
	 * @return mixed
	 */
	public function savePatient($params) {
		$this->setPatientModel();

		if(isset($params->fullname)){
			$params->qrcode = $this->createPatientQrCode($this->patient['pid'], $params->fullname);
		}else if(isset($params->fname) && isset($params->mname) && isset($params->lname)){
			$params->qrcode = $this->createPatientQrCode(
                $this->patient['pid'],
                Person::fullname($params->fname, $params->mname, $params->lname)
            );
		}

		$params->update_uid = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '0';
		$params->update_date = date('Y-m-d H:i:s');
		$this->patient = (object) $this->p->save($params);
		$this->createPatientDir($this->patient->pid);
		return $this->patient;
	}


	/**
	 * @param $pid
	 *
	 * @return mixed
	 */
	protected function setPatient($pid) {
		return $this->getPatientByPid($pid);
	}

	/**
	 * @return array
	 */
	public function getPatient() {
		return $this->patient;
	}

	/**
	 * @param $pid
	 *
	 * @return mixed
	 */
	public function getPatientByPid($pid) {
		$this->setPatientModel();
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		$this->patient = $this->p->load($params)->one();
		if($this->patient !== false){
			$this->patient['pic'] = $this->patient['image'];
			$this->patient['age'] = $this->getPatientAge();
			$this->patient['name'] = $this->getPatientFullName();
		}
		unset($params);
		return $this->patient;
	}

	/**
	 * @param $pubpid
	 *
	 * @return mixed
	 */
	public function getPatientByPublicId($pubpid) {
		$this->setPatientModel();
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pubpid';
		$params->filter[0]->value = $pubpid;
		$this->patient = $this->p->load($params)->one();
		if($this->patient !== false){
			$this->patient['pic'] = $this->patient['image'];
			$this->patient['age'] = $this->getPatientAge();
			$this->patient['name'] = $this->getPatientFullName();
		}
		unset($params);
		return $this->patient;
	}

	/**
	 * @param $username
	 *
	 * @return mixed
	 */
	public function getPatientByUsername($username) {

		$this->setPatientModel();
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'portal_username';
		$params->filter[0]->value = $username;
		$this->patient = $this->p->load($params)->one();

		if($this->patient !== false){
			$this->patient['pic'] = $this->patient['image'];
			$this->patient['age'] = $this->getPatientAge();
			$this->patient['name'] = $this->getPatientFullName();
		}

		unset($params);
		return $this->patient;
	}

	/**
	 * @param $pid
	 *
	 * @return mixed
	 */
	public function unsetPatient($pid) {
		if($pid != null)
			$this->patientChartInByPid($pid);
		return;
	}

	/**
	 * @param $pid
	 *
	 * @return array
	 */
	public function getPatientDemographicDataByPid($pid) {
		$this->setPatient($pid);
		return $this->patient;
	}

	/**
	 * @param stdClass $params
	 *
	 * @return array
	 */
	public function getPatientDemographicData(stdClass $params) {
		return $this->setPatient($params->pid);
	}

	/**
	 * @param $name
	 *
	 * @return array
	 */
	public function createNewPatientOnlyName($name) {
		$params = new stdClass();
		$foo = explode(' ', $name);
		$params->fname = trim($foo[0]);
		$params->mname = '';
		$params->lname = '';
		if(count($foo) == 2){
			$params->lname = trim($foo[1]);
		} elseif(count($foo) >= 3) {
			$params->mname = (isset($foo[1])) ? trim($foo[1]) : '';
			unset($foo[0], $foo[1]);
			$params->lname = '';
			foreach($foo as $fo){
				$params->lname .= $params->lname . ' ' . $fo . ' ';
			}
			$params->lname = trim($params->lname);
		}
		$params->create_uid = $_SESSION['user']['id'];
		$params->create_date = date('Y-m-d H:i:s');
		$params->update_date = date('Y-m-d H:i:s');
		$patient = $this->savePatient($params);
		return [
			'success' => true,
			'patient' => [
				'pid' => $patient->pid
			]
		];
	}

	/**
	 * @param stdClass $params
	 *
	 * @return mixed
	 */
	public function createNewPatient(stdClass $params) {
		return $this->savePatient($params);
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	public function getPatientSetDataByPid($params) {

		include_once(ROOT . '/dataProvider/PoolArea.php');
		$this->setPatient($params->pid);
		$poolArea = new PoolArea();

		$area = $poolArea->getCurrentPatientPoolAreaByPid($this->patient['pid']);
		$chart = $this->patientChartOutByPid($this->patient['pid'], $area['area_id']);

		return [
			'patient' => [
				'pid' => $this->patient['pid'],
				'pubpid' => $this->patient['pubpid'],
				'name' => $this->getPatientFullName(),
				'pic' => $this->patient['image'],
				'sex' => $this->getPatientSex(),
				'dob' => $this->getPatientDOB(),
				'age' => $this->getPatientAge(),
				'area' => $area['poolArea'],
				'priority' => (empty($area) ? null : $area['priority']),
				'rating' => (isset($this->patient['rating']) ? $this->patient['rating'] : 0),
			    'record' => $this->patient
			],
			'chart' => [
				'readOnly' => $chart->read_only == '1',
				'overrideReadOnly' => $this->acl->hasPermission('override_readonly'),
				'outUser' => isset($chart->outChart->uid) ? $this->user->getUserFullNameById($chart->outChart->uid) : 0,
				'outArea' => isset($chart->outChart->pool_area_id) ? $poolArea->getAreaTitleById($chart->outChart->pool_area_id) : 0,
			]
		];
	}

	/**
	 * @return array
	 */
	public function getPatientAge() {
		return $this->getPatientAgeByDOB($this->patient['DOB']);
	}

	/**
	 * @param $dob
	 * @param string $from
	 *
	 * @return array
	 * @internal param $birthday
	 */
	public static function getPatientAgeByDOB($dob, $from = 'now') {
		if($dob != '0000-00-00 00:00:00'){
			$from = $from == 'now' ? new DateTime(date('Y-m-d')) : $from;
			$t = new DateTime(date($dob));
			$age['days'] = $t->diff($from)->d;
			$age['months'] = $t->diff($from)->m;
			$age['years'] = $t->diff($from)->y;
			if($age['years'] >= 2){
				$ageStr = $age['years'] . ' yr(s)';
			} else {
				if($age['years'] >= 1){
					$ageStr = 12 + $age['months'] . ' mo(s)';
				} else {
					if($age['months'] >= 1){
						$ageStr = $age['months'] . ' mo(s) and ' . $age['days'] . ' day(s)';
					} else {
						$ageStr = $age['days'] . ' day(s)';
					}
				}
			}
			return [
				'DMY' => $age,
				'str' => $ageStr
			];
		} else {
			return [
				'DMY' => [
					'years' => 0,
					'months' => 0,
					'days' => 0
				],
				'str' => '<span style="color:red">Age</span>'
			];
		}
	}

	/**
	 * @param stdClass $params
	 *
	 * @return object
	 */
	public function setPatientRating(stdClass $params) {
		$this->setPatientModel();
		return $this->p->save($params);
	}

	public function createPatientQrCode($pid, $fullname) {
		$data = '{"name":"' . $fullname . '","pid":' . $pid . ',"ehr": "GaiaEHR"}';
		include(ROOT . '/lib/phpqrcode/qrlib.php');
		ob_start();
		QRCode::png($data, false, 'Q', 2, 2);
		$imageString = base64_encode(ob_get_contents());
		ob_end_clean();
		return 'data:image/jpeg;base64,' . $imageString;
	}

	/**
	 * @return string
	 */
	public function getPatientFullName() {
		return Person::fullname($this->patient['fname'], $this->patient['mname'], $this->patient['lname']);
	}

	/**
	 * @param $pid
	 *
	 * @return string
	 */
	public function getPatientFullNameByPid($pid) {
		$this->setPatientModel();
		$p = $this->p->sql("SELECT fname,mname,lname FROM patient WHERE pid = '$pid'")->one();
		return Person::fullname($p['fname'], $p['mname'], $p['lname']);
	}

	/**
	 * @param $pid
	 *
	 * @return string
	 */
	public function getPatientFullAddressByPid($pid) {
        $patientContact = new PatientContacts();
        $record = $patientContact->getSelfContact($pid);
        if(isset($record)) {
            return Person::fulladdress(
                $record['street_mailing_address'],
                null,
                $record['city'],
                $record['state'],
                $record['zip']
            );
        }
	}

	public function patientLiveSearch(stdClass $params) {
		$this->setPatientModel();
		$conn = Matcha::getConn();
		$whereValues = [];
		$where = [];
		$queries = explode(' ', $params->query);
		foreach($queries as $index => $query){
			$query = trim($query);
			$where[] = " (pubpid REGEXP :pubpid{$index} OR fname LIKE :fname{$index} OR lname LIKE :lname{$index} OR mname LIKE :mname{$index} OR DOB LIKE :DOB{$index} OR pid LIKE :pid{$index} OR SS LIKE :ss{$index}) ";

			$whereValues[':fname'.$index] = $query . '%';
			$whereValues[':lname'.$index] = $query . '%';
			$whereValues[':mname'.$index] = $query . '%';

			if($index == 0){
				if(preg_match('/^(.)-(.*)-(.{2})$/', $query, $matches)){
					$whereValues[':pubpid' . $index] = '^' . $matches[1] . '-' . str_pad($matches[2], 15, '0', STR_PAD_LEFT) . '-' . $matches[3] . '$';
				}elseif(preg_match('/^(.)-(.*)$/', $query, $matches)){
					$whereValues[':pubpid' . $index] = '^' . $matches[1] . '-' . str_pad($matches[2], 15, '0', STR_PAD_LEFT);
				}elseif(preg_match('/(.*)-(.{2})$/', $query, $matches)){
					$whereValues[':pubpid' . $index] = str_pad($matches[1], 15, '0', STR_PAD_LEFT) . '-' . $matches[2];
				}else{
					$whereValues[':pubpid'.$index] = trim($query, '-') . '-.{2}$';
				}
			}else{
				$whereValues[':pubpid'.$index] = trim($query, '-') . '-.{2}$';
			}

			$whereValues[':DOB'.$index] = $query . '%';
			$whereValues[':pid'.$index] = $query . '%';
			$whereValues[':ss'.$index] = '%' . $query;
		}
		$sth = $conn->prepare('SELECT pid, pubpid, fname, lname, mname, DOB, SS, sex
 								 FROM `patient` WHERE ' . implode(' AND ', $where) . ' LIMIT 300');
		$sth->execute($whereValues);
		$patients = $sth->fetchAll(PDO::FETCH_ASSOC);
		return [
			'totals' => count($patients),
			'rows' => array_slice($patients, $params->start, $params->limit)
		];
	}

    /**
     * createPatientDir
     * Creates the patient directory to store
     * documents and media into the file system
     *
     * @param $pid
     * @return bool
     */
	private function createPatientDir($pid) {
        try
        {
            $path = site_path . '/patients/' . $pid;
            if(!file_exists($path))
            {
                if(mkdir($path, 0777, true))
                {
                    chmod($path, 0777);
                }
                else
                {
                    throw new Exception('Could not create the directory for the patient.');
                }
            }
            else
            {
                if(!is_writable($path)) chmod($path, 0777);
            }
            return true;
        }
        catch(Exception $Error)
        {
            error_log($Error->getMessage());
            return $Error->getMessage();
        }
	}

	public function getPatientAddressById($pid) {
        $patientContact = new PatientContacts();
        $record = $patientContact->getSelfContact($pid);
        $address = '';
        if(isset($record)) {
            $address = $record['address'].' <br>'.$record['city'].',  '.$record['state'].' '.$record['country'];
        }
		return $address;
	}

	public function getPatientArrivalLogWarningByPid($pid) {
		$this->setPatientModel();
		$alert = $this->p->sql("SELECT pid FROM patient WHERE pid = '$pid' AND (sex IS NULL OR DOB IS NULL)")->one();
		return $alert !== false;
	}

	/** Patient Charts */
	public function patientChartOutByPid($pid, $pool_area_id) {
		$this->setChartCheckoutModel();
		$outChart = $this->isPatientChartOutByPid($pid);
		$params = new stdClass();
		$params->pid = $pid;
		$params->uid = $_SESSION['user']['id'];
		$params->chart_out_time = date('Y-m-d H:i:s');
		$params->pool_area_id = $pool_area_id;
		$params->read_only = $outChart === false ? '0' : '1';
		$params = (object) $this->c->save($params);
		$params->outChart = $outChart;
		return $params;
	}

	public function patientChartInByPid($pid) {
		$this->setChartCheckoutModel();
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'uid';
		$filters->filter[0]->value = $_SESSION['user']['id'];
		$filters->filter[2] = new stdClass();
		$filters->filter[2]->property = 'pid';
		$filters->filter[2]->value = $pid;
		$filters->filter[3] = new stdClass();
		$filters->filter[3]->property = 'chart_in_time';
		$filters->filter[3]->value = null;
		$chart = $this->c->load($filters)->one();
		unset($filters);
		if($chart !== false){
			$chart = (object) $chart;
			$chart->chart_in_time = date('Y-m-d H:i:s');
			return $this->c->save($chart);
		}
		return false;
	}

	public function patientChartInByUserId($uid) {
		$this->setChartCheckoutModel();
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'uid';
		$filters->filter[0]->value = $uid;
		$filters->filter[1] = new stdClass();
		$filters->filter[1]->property = 'chart_in_time';
		$filters->filter[1]->value = null;
		$chart = $this->c->load($filters)->one();
		unset($filters);
		if($chart !== false){
			$chart = (object) $chart;
			$chart->chart_in_time = date('Y-m-d H:i:s');
			return $this->c->save($chart);
		}
		return false;
	}

	public function isPatientChartOutByPid($pid) {
		$this->setChartCheckoutModel();
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'pid';
		$filters->filter[0]->value = $pid;
		$filters->filter[1] = new stdClass();
		$filters->filter[1]->property = 'chart_in_time';
		$filters->filter[1]->value = null;
		$result = $this->c->load($filters)->one();
		unset($filters);
		return $result;
	}

	public function getDOBByPid($pid) {
		$this->setPatientModel();
		$p = $this->p->load(['pid'=>$pid])->one();
		return $p['DOB'];
	}

	public function getPatientDOB() {
		return $this->patient['DOB'];
	}

	public function getPatientDOBByPid($pid) {
		return $this->getDOBByPid($pid);
	}

	public function getPatientAgeByPid($pid) {
		return $this->getPatientAgeByDOB($this->getDOBByPid($pid));
	}

	public function getPatientPid() {
		return $this->patient['pid'];
	}

	public function getPatientSex() {
		return $this->patient['sex'];
	}

	public function getPatientSS() {
		return $this->patient['SS'];
	}

	public function getPatientSexByPid($pid) {
		$this->setPatientModel();
		$p = $this->p->load(['pid'=>$pid])->one();
		return $p['sex'];
	}

	public function getPatientSexIntByPid($pid) {
		return (strtolower($this->getPatientSexByPid($pid)) == 'female' ? 1 : 2);
	}

	public function getPatientPregnantStatusByPid($pid) {
		$this->setPatientEncounterModel();
		$p = $this->e->sql("SELECT e.* FROM encounters as e WHERE e.eid = (
							SELECT  MAX(ee.eid) as eid FROM encounters as ee WHERE ee.pid = '$pid')")->one();
		return $p['review_pregnant'];
	}

	public function getPatientActiveProblemsById($pid, $tablexx, $columnName) {
		$records = $this->p->sql("SELECT $columnName FROM $tablexx WHERE pid ='$pid'")->all();
		$rows = [];
		foreach($records as $record){
			if(!isset($record['end_date']) || $record['end_date'] != null || $record['end_date'] != '0000-00-00 00:00:00'){
				$rows[] = $record;
			}
		}
		return $records;
	}

	public function getPatientDocuments(stdClass $params) {
		$this->setDocumentModel();
		$docs = $this->d->load($params)->all();
		if(isset($docs['data'])){
			foreach($docs['data'] AS $index => $row){
				$docs['data'][$index]['user_name'] = $this->user->getUserNameById($row['uid']);
			}
		}
		return $docs;
	}

	public function getMeaningfulUserAlertByPid(stdClass $params) {
		$record = [];

		$this->setPatientModel();
		$patient = $this->p->load($params->pid, ['language', 'race', 'ethnicity', 'fname', 'lname', 'sex', 'DOB',])->one();
		foreach($patient as $key => $val){
			$val = ($val == null || $val == '') ? false : true;
			$record[] = [
				'name' => $key,
				'val' => $val
			];
		}
		return $record;
	}

	public function getPatientPhotoSrc() {
		return $this->patient['image'];
	}

	public function getPatientPhotoSrcIdByPid($pid) {
		$this->setPatientModel();
		$patient = $this->p->load($pid)->one();
		return $patient['image'];
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function getPossibleDuplicatesByFilters($params) {
		$this->setPatientModel();

//		$sql = "SELECT *
//				  FROM `patient`
// 				 WHERE `fname` SOUNDS LIKE 'sudipto'"

		return [];
	}

	/**
	 * @param $params
	 * @param $includeDateOfBirth
	 *
	 * @return mixed
	 */
	public function getPossibleDuplicatesByDemographic($params, $includeDateOfBirth = false) {
		$this->setPatientModel();
		$sql = "SELECT *
				  FROM `patient`
 				 WHERE `fname` SOUNDS LIKE '{$params->fname}'
 				   AND `lname` SOUNDS LIKE '{$params->lname}'
 				   AND `sex` = '{$params->sex}'";
        $this->patientContacts = new PatientContacts();
		if($includeDateOfBirth){
			$sql = " AND `DOB` = '{$params->DOB}'";
		}

		if(isset($params->pid) && $params->pid != 0){
			$sql .= " AND `pid` != '{$params->pid}'";
		}

		$results = $this->p->sql($sql)->all();
        foreach($results as $index => $record ){
            $contact = $this->patientContacts->getSelfContact($record['pid']);
            $results[$index]['name'] = Person::fullname(
                $record['fname'],
                $record['mname'],
                $record['lname']
            );
            if(isset($contact)) {
                $results[$index]['fulladdress'] = Person::fulladdress(
                    isset($contact['street_mailing_address']) ? $contact['street_mailing_address'] : '',
                    null,
                    isset($contact['city']) ? $contact['city'] : '',
                    isset($contact['state']) ? $contact['state'] : '',
                    isset($contact['zip']) ? $contact['zip'] : ''
                );
                $results[$index]['phones'] = isset($contact['phone_local_number'])
                    ?
                    $contact['phone_use_code'] . '-' . $contact['phone_area_code'] . '-' . $contact['phone_local_number']
                    :
                    '';
            }
        }
		return [
            'total' => count($results),
            'data' => $results
        ];
	}

}

