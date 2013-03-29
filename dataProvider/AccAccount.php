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

include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');

class AccAccount
{
    /**
     * @var MatchaHelper
     */
    protected $db;
    /**
     * @var Services
     */
	protected $services;
    /**
     * @var Patient
     */
	protected $patient;

	protected $account = NULL;
	protected $accountType = NULL;

    function __construct()
    {
        if($this->acount == NULL) $this->account = Matcha::setSenchaModel('App.model.account.Account');
	    if($this->acountType == NULL) $this->accountType = Matcha::setSenchaModel('App.model.account.AccountType');
        $this->services = new Services();
        $this->patient  = new Patient();
        return;
    }

	public function getVisitCheckOutCharges(stdClass $params)
    {
        $invoice = array();
        $insurance = $this->patient->getPatientPrimaryInsuranceByPid($params->pid);
        if($insurance !== false){
            $invoice[] = array(
                'code' => 'COPAY',
                'name' => 'COPAY',
                'amountOriginal' => $insurance['copay'],
                'amount' => $insurance['copay'],
            );
        }else{
            $services = $this->services->getCptByEid($params->eid);
            foreach($services['rows'] AS $service){
                $row['code'] = $service['code'];
                $row['name'] = $service['code_text_medium'];
                $row['amountOriginal'] = ($service['status'] == 0 ? '00.00' : $service['charge']);
                $row['amount'] = $row['amountOriginal'];
                $invoice[] = $row;
            }
        }
        return $invoice;
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
