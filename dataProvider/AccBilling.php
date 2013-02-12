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
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/AccVoucher.php');
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
class AccBilling
{
    /**
     * @var dbHelper
     */
    private $db;
    /**
     * @var AccountReceivable
     */
    private $av;
    /**
     * @var Services
     */
    private $services;
    /**
     * @var Patient
     */
    private $patient;

    function __construct()
    {
        $this->db = new dbHelper();
        $this->av = new AccVoucher();

        $this->services = new Services();
        $this->patient = new Patient();
    }

    /**
     * @param stdClass $params required params: $params->pid, $params->eid, $params->uid
     * @return array
     */
    public function getVisitServicesCharges(stdClass $params)
    {
        $invoice = array();
        $insurance = $this->patient->getPatientPrimaryInsuranceByPid($params->pid);
        if($insurance !== false){
            $invoice[] = array(
                'code' => 'COPAY',
                'code_text_medium' => 'COPAY',
                'charge' => $insurance['copay'],
            );
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
        return $invoice;
    }


    /**
     * @param stdClass $params
     *
     * $params->pid         int
     * $params->uid         int
     * $params->date        date
     * $params->notes       string
     * $params->services    array
     * $params->payment     array
     *
     * @return \stdClass
     */
    public function setVisitVoucher(stdClass $params)
    {


        $vid = $this->av->createVoucher(null,null,null);


        return $params;


    }





}

//
//$params = new stdClass();
//$params->filter = 2;
//$params->pid = '7';
//$params->eid = '1';
//$params->start = 0;
//$params->limit = 25;
//
//$t = new Billing();
//print '<pre>';
//print_r($t->getLastRevisionByCode('ICD9'));
