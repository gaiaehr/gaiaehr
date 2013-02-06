<?php
/*
 GaiaEHR (Electronic Health Records)
 Services.php
 Services dataProvider
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
if (!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/Time.php');
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/classes/rb.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');

/**
 * @brief       Billing Class.
 * @details     This class will handle all Billing
 *
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 *
 */
class AccountReceivable
{
    /**
     * @var dbHelper
     */
    private $db;
    /**
     * @var Services
     */
    protected $services;
    /**
     * @var
     */
    private $sid = null;

    private $patient;

    /**
     * __construct
     */
    function __construct()
    {
        $this->db = new dbHelper();
		$this->db->setTable('ar_session');
		$this->db->setField(array( 'NAME' => 'pid', 'TYPE' => 'BIGINT', 'LENGTH' => 20, 'NULL' => true, 'DEFAULT' => '' ) );
		$this->db->setField(array( 'NAME' => 'eid', 'TYPE' => 'BIGINT', 'LENGTH' => 20, 'NULL' => true, 'DEFAULT' => '' ) );
		$this->db->setField(array( 'NAME' => 'uid', 'TYPE' => 'BIGINT', 'LENGTH' => 20, 'NULL' => true, 'DEFAULT' => '' ) );
		$this->db->setField(array( 'NAME' => 'open_time', 'TYPE' => 'DATETIME', 'NULL' => true, 'DEFAULT' => '' ) );
		$this->db->setField(array( 'NAME' => 'close_time', 'TYPE' => 'DATETIME', 'NULL' => true, 'DEFAULT' => '' ) );
		$this->db->setField(array( 'NAME' => 'last_update', 'TYPE' => 'TIMESTAMP', 'NULL' => true, 'DEFAULT' => '' ) );
		$this->db->executeORM();
		
        $this->services = new Services();
        $this->patient = new Patient();
    }

    public function getArSessionBalanceByEid($eid)
    {
        $services = $this->services->getCptByEid($eid);
        $activities = $this->getArActivityByEid($eid);
        print_r($services);
        print_r($activities);
	}
    /**
     * This method will find the AR sid for the encounter
     * if not found will create one
     * @param stdClass $params
     */
    public function setSid(stdClass $params){
        if($this->sid == null){
            $this->sid = $this->getArSidByEid($params->eid);
            if($this->sid === false){
                $this->sid = $this->openArSession($params->pid,$params->eid,$params->uid);
            }
        }
    }

    /**
     *
     * @param stdClass $params required params: $params->pid, $params->eid, $params->uid
     * @return array
     */
<<<<<<< HEAD
    public function getArInvoice(stdClass $params)
    {
=======
    public function getArVisitCheckoutCharges(stdClass $params){
>>>>>>> aab33f35a64a4c1d1fa8fbccda9715cb0543f4bf
        $this->setSid($params);
        $invoice = array();

        $insurance = $this->patient->getPatientPrimaryInsuranceByPid($params->pid);
        $activities = $this->getArActivitiesBySid($this->sid);

<<<<<<< HEAD
        foreach($services['rows'] AS $service){
            $row['id'] = $service['id'];
            $row['code'] = $service['code'];
            $row['code_text_medium'] = $service['code_text_medium'];

            $row['ins'] = ($params->payer_type == 0 ? 0 : 1 );
            $row['charge'] = ($service['status'] == 0 ? '24.25' : $service['charge']);

            $invoice[] = $row;
        }

=======
        // if insurance, add copay
        if($insurance !== false){
            $invoice[] = array(
                'code' => 'COPAY',
                'code_text_medium' => 'COPAY',
                'charge' => $insurance['copay'],
            );
        // else,
        }else{
            $services = $this->services->getCptByEid($params->eid);
            foreach($services['rows'] AS $service){
                $row['id'] = $service['id'];
                $row['code'] = $service['code'];
                $row['code_text_medium'] = $service['code_text_medium'];
                $row['charge'] = ($service['status'] == 0 ? '00.00' : $service['charge']);
                $invoice[] = $row;
            }
        }
>>>>>>> aab33f35a64a4c1d1fa8fbccda9715cb0543f4bf
        return $invoice;
    }

    public function getPatientInsuranceCoPay($pid)
    {

    }

    /**
     * @param $sid
     * @return array
     */
    public function getArActivitiesBySid($sid)
    {
        $this->db->setSQL("SELECT * FROM ar_session_activity WHERE id = '$sid'");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

    /**
     * @param $eid
     * @return mixed
     */
    public function getArSidByEid($eid)
    {
        $this->db->setSQL("SELECT id FROM ar_session WHERE eid = '$eid'");
        $rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        if(!empty($rec)){
            return $rec['id'];
        }else{
            return false;
        }

    }

    /**
     * @param $pid
     * @param $eid
     * @param $uid
     * @return array
     */
    public function openArSession($pid, $eid, $uid)
    {
        $data = array();
        $data['pid'] = $pid;
        $data['eid'] = $eid;
        $data['uid'] = $uid;
        $data['open_time'] = Time::getLocalTime();
        $this->db->setSQL($this->db->sqlBind($data, 'ar_session', 'I'));
        return $this->db->execOnly();
    }

    /**
     * @param $eid
     * @return array
     */
    public function closeArSessionByEid($eid)
    {
        $data = array();
        $data['close_time'] = Time::getLocalTime();
        $this->db->setSQL($this->db->sqlBind($data, 'ar_session', 'U', array('eid' => $eid)));
        return $this->db->execOnly();
    }

    /**
     * @param $sid
     * @return array
     */
    public function closeArSessionBySid($sid)
    {
        $data = array();
        $data['close_time'] = Time::getLocalTime();
        $this->db->setSQL($this->db->sqlBind($data, 'ar_session', 'U', array('id' => $sid)));
        return $this->db->execOnly();
    }

    public function getPrimarySecondaryInsuranceByPid($pid)
    {
        // TODO: birthday rule
    }
	
}

//
//$params = new stdClass();
//$params->pid = 2;
//$params->eid = 2;
//$params->uid = 85;
//
//$t = new AccountReceivable();
//print '<pre>';
//print_r($t->getArInvoice($params));
