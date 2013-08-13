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

if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ('Reports.php');
include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/Encounter.php');
include_once ($_SESSION['root'] . '/dataProvider/i18nRouter.php');

class Clinical extends Reports
{
	private $db;
	private $user;
	private $patient;
	private $encounter;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct()
	{
		parent::__construct();
		$this -> db = new MatchaHelper();
		$this -> user = new User();
		$this -> patient = new Patient();
		$this -> encounter = new Encounter();
		return;
	}

	public function createClinicalReport(stdClass $params)
	{
		ob_end_clean();
		$Url = $this -> ReportBuilder($params->html, 10);
		return array(
			'success' => true,
			'url' => $Url
		);
	}


	public function getClinicalList(stdClass $params)
	{
		$params->to = ($params->to == '') ? date('Y-m-d') : $params->to;

		$sql = " SELECT * FROM patient";

        if (isset($params->from) ||
            isset($params->to) ||
            isset($params->sex) ||
            isset($params->race) ||
            isset($params->pid) ||
            isset($params->ethnicity) ||
            isset($params->age_from) ||
            isset($params->age_to)) $sql .= " WHERE";

        if (isset($params->from) && isset($params->to)) $sql .= " date_created BETWEEN '$params->from 00:00:00' AND '$params->to 23:59:59'";
		if (isset($params->sex) && ($params->sex != '' && $params->sex != 'Both')) $sql .= " AND sex = '$params->sex'";
		if (isset($params->race) && $params->race != '') $sql .= " AND race = '$params->race'";
		if (isset($params->pid) && $params->pid != '') $sql .= " AND pid = '$params->pid'";
		if (isset($params->ethnicity) && $params->ethnicity != '') $sql .= " AND ethnicity= '$params->ethnicity'";

		$this -> db -> setSQL($sql);
		$data = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		$newarray = array();
		if(($params->age_from == null && $params->age_to == null) || ($params->age_from == '' && $params->age_to == ''))
        {
            $params->age_from=0;
            $params->age_to=100;
		}
		foreach ($data as  $key =>$data1)
		{
			$age = $this->patient->getPatientAgeByDOB($data1['DOB']);
			$num =$age['DMY']['years'];
			if($params->age_from == null)
            {
				if($params->age_to != null)
                {
					if($params->age_to>=$num) array_push($newarray,$data[$key]);
				}
			}
			else if($params->age_to == null)
            {
				if($params->age_from != null)
                {
					if($params->age_from <= $num)	array_push($newarray,$data[$key]);
				}
			}
			else if($params->age_from <= $num && $params->age_to >= $num ) array_push($newarray,$data[$key]);
		}
		foreach ($newarray AS $num=>$rec)
		{
			$ethnicity= $this->getEthnicityByKey($rec['ethnicity']);
			$age = $this->patient->getPatientAgeByDOB($rec['DOB']);
			$newarray[$num]['fullname']=$this->patient->getPatientFullNameByPid($rec['pid']);
			$newarray[$num]['age']= ($age['DMY']['years']==0)?'months':$age['DMY']['years'];
			$newarray[$num]['ethnicity']= $ethnicity['option_name'];
		}
		return $newarray;
	}

	public function getEthnicityByKey($key)
    {
		$sql = " SELECT option_name
	               FROM combo_lists_options
	              WHERE option_value ='$key'";
		$this -> db -> setSQL($sql);
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}


}

//$e = new Clinical();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//print_r($e->getClinical('','','','2010-03-08','2013-03-08',0,10,'',''));
