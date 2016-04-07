<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

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

include_once (ROOT . '/dataProvider/Services.php');
include_once (ROOT . '/dataProvider/Insurance.php');

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
     * @var Insurance
     */
	protected $insurance;
	/**
	 * @var MatchaCUP
	 */
	protected $account = NULL;
	/**
	 * @var MatchaCUP
	 */
	protected $accountType = NULL;

    function __construct()
    {
        $this->services = new \Services();
        $this->insurance  = new \Insurance();
        return;
    }

	public function getVisitCheckOutCharges(\stdClass $params)
    {
        $invoice = array();
        $insurance = $this->insurance->getPatientPrimaryInsuranceByPid($params->pid);
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
