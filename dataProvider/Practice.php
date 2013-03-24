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

if (!isset($_SESSION)) {
    session_name("GaiaEHR");
    session_start();
    session_cache_limiter('private');
}

include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');

class Practice extends MatchaHelper
{

    /**
     * Data Object
     */
    private $Pharmacy = NULL;
    private $Address = NULL;
    private $Phone = NULL;
    private $Laboratory = NULL;
    private $Insurance = NULL;

    public function __construct()
    {
        return;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getters and Setters
    //------------------------------------------------------------------------------------------------------------------
    public function getPharmacies()
    {
        if($this->Pharmacy == NULL) $this->Pharmacy = MatchaModel::setSenchaModel('App.model.Pharmacy');
        if($this->Phone == NULL) $this->Phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
        $rows = array();
        foreach($this->Pharmacy->load()->all() as $row)
        {
            $row['address_full'] = $row['line1'].' '.$row['line2'].' '.$row['city'].','.$row['state'].' '.$row['zip'].'-'.$row['plus_four'].' '.$row['country'];
            array_push($rows, $this->Phone->load(array('foreign_id'=>$row['id']))->one());
        }
        return $rows;
    }

    public function addPharmacy(stdClass $params)
    {
        if($this->Pharmacy == NULL) $this->Pharmacy = MatchaModel::setSenchaModel('App.model.Pharmacy');
        $params->id = $this->getNextPharmacyInsuranceId();
        $data = get_object_vars($params);
        $row['id'] = $data['id'];
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $this->Pharmacy->save($row);
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updatePharmacy(stdClass $params)
    {
        if($this->Pharmacy == NULL) $this->Pharmacy = MatchaModel::setSenchaModel('App.model.Pharmacy');
        $data = get_object_vars($params);
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $this->Pharmacy->save($row);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }

    public function getLaboratories()
    {
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.LaboratoryGrid');
        $rows = array();
        foreach ($this->Laboratory->load()->all() as $row)
        {
            $row = $this->getPhones($row);
            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    public function addLaboratory(stdClass $params)
    {
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.LaboratoryGrid');
        $params->id = $this->getNextPharmacyInsuranceId();
        $data = get_object_vars($params);
        $row['id'] = $data['id'];
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $this->Laboratory->save($row);
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateLaboratory(stdClass $params)
    {
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.LaboratoryGrid');
        $data = get_object_vars($params);
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $this->Laboratory->save($row);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }

    public function getInsurances()
    {
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.InsuranceGrid');
        $rows = array();
        foreach($this->Insurance->load()->all() as $row)
        {
            $row = $this->getPhones($row);
            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    public function addInsurance(stdClass $params)
    {
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.InsuranceGrid');
        $params->id = $this->getNextPharmacyInsuranceId();
        $data = get_object_vars($params);
        $row['id'] = $data['id'];
        $row['name'] = $data['name'];
        $row['attn'] = $data['attn'];
        $row['cms_id'] = $data['cms_id'];
        $row['freeb_type'] = $data['freeb_type'];
        $row['x12_receiver_id'] = $data['x12_receiver_id'];
        $row['x12_default_partner_id'] = $data['x12_default_partner_id'];
        $row['alt_cms_id'] = $data['alt_cms_id'];
        $row['active'] = $data['active'];
        $this->Insurance-save($row);
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateInsurance(stdClass $params)
    {
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.InsuranceGrid');
        $data = get_object_vars($params);
        $row['name'] = $data['name'];
        $row['attn'] = $data['attn'];
        $row['cms_id'] = $data['cms_id'];
        $row['freeb_type'] = $data['freeb_type'];
        $row['x12_receiver_id'] = $data['x12_receiver_id'];
        $row['x12_default_partner_id'] = $data['x12_default_partner_id'];
        $row['alt_cms_id'] = $data['alt_cms_id'];
        $row['active'] = $data['active'];
        $this->Laboratory->save($row);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Extra methods
    // This methods are used by the view to gather extra data from the store or the model
    //------------------------------------------------------------------------------------------------------------------
    public function getInsuranceNumbers(stdClass $params)
    {
        return $params;
    }

    public function getX12Partners(stdClass $params)
    {
        return $params;
    }

    private function addAddress($params)
    {
        if($this->Address == NULL) $this->Address = MatchaModel::setSenchaModel('App.model.administration.Address');
        $data = get_object_vars($params);
        $record['line1'] = $data['line1'];
        $record['line2'] = $data['line2'];
        $record['city'] = $data['city'];
        $record['state'] = $data['state'];
        $record['zip'] = $data['zip'];
        $record['plus_four'] = $data['plus_four'];
        $record['country'] = $data['country'];
        $record['foreign_id'] = $data['id'];
        $this->Address->save($record);
        $params->address_full = $params->line1 . ' ' . $params->line2 . ' ' . $params->city . ',' . $params->state . ' ' . $params->zip . '-' . $params->plus_four . ' ' . $params->country;
        return $params;
    }

    private function updateAddress($params)
    {
        if($this->Address == NULL) $this->Address = MatchaModel::setSenchaModel('App.model.administration.Address');
        $data = get_object_vars($params);
        $record['line1'] = $data['line1'];
        $record['line2'] = $data['line2'];
        $record['city'] = $data['city'];
        $record['state'] = $data['state'];
        $record['zip'] = $data['zip'];
        $record['plus_four'] = $data['plus_four'];
        $record['country'] = $data['country'];
        $this->Address->save($record);
        $params->address_full = $params->line1 . ' ' . $params->line2 . ' ' . $params->city . ',' . $params->state . ' ' . $params->zip . '-' . $params->plus_four . ' ' . $params->country;
        return $params;
    }

    private function getPhones($row)
    {
        if($this->Phone == NULL) $this->Phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
        foreach ($this->Phone->load(array('id'=>$row['id']))-all() as $phoneRow) {
            switch ($phoneRow['type']) {
                case "2" :
                    $row['phone_id'] = $phoneRow['id'];
                    $row['phone_country_code'] = $phoneRow['country_code'];
                    $row['phone_area_code'] = $phoneRow['area_code'];
                    $row['phone_prefix'] = $phoneRow['prefix'];
                    $row['phone_number'] = $phoneRow['number'];
                    $row['phone_full'] = $phoneRow['country_code'] . ' ' . $phoneRow['area_code'] . '-' . $phoneRow['prefix'] . '-' . $phoneRow['number'];
                    break;
                case "5" :
                    $row['fax_id'] = $phoneRow['id'];
                    $row['fax_country_code'] = $phoneRow['country_code'];
                    $row['fax_area_code'] = $phoneRow['area_code'];
                    $row['fax_prefix'] = $phoneRow['prefix'];
                    $row['fax_number'] = $phoneRow['number'];
                    $row['fax_full'] = $phoneRow['country_code'] . ' ' . $phoneRow['area_code'] . '-' . $phoneRow['prefix'] . '-' . $phoneRow['number'];
                    break;
            }
        }
        return $row;
    }

    private function addPhones($params)
    {
        if($this->Phone == NULL) $this->Phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
        $data = get_object_vars($params);
        $phoneNum['country_code'] = $data['phone_country_code'];
        $phoneNum['area_code'] = $data['phone_area_code'];
        $phoneNum['prefix'] = $data['phone_prefix'];
        $phoneNum['number'] = $data['phone_number'];
        $phoneNum['type'] = 2;
        $phoneNum['foreign_id'] = $data['id'];
        $faxNum['country_code'] = $data['fax_country_code'];
        $faxNum['area_code'] = $data['fax_area_code'];
        $faxNum['prefix'] = $data['fax_prefix'];
        $faxNum['number'] = $data['fax_number'];
        $faxNum['type'] = 5;
        $faxNum['foreign_id'] = $data['id'];
        $this->Phone->save($phoneNum);
        $this->Phone->save($faxNum);
        $params->phone_full = $phoneNum['country_code'] . ' ' . $phoneNum['area_code'] . '-' . $phoneNum['prefix'] . '-' . $phoneNum['number'];
        $params->fax_full = $faxNum['country_code'] . ' ' . $faxNum['area_code'] . '-' . $faxNum['prefix'] . '-' . $faxNum['number'];
        return $params;
    }

    private function updatePhones($params)
    {
        if($this->Phone == NULL) $this->Phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
        $data = get_object_vars($params);
        $phoneNum['country_code'] = $data['phone_country_code'];
        $phoneNum['area_code'] = $data['phone_area_code'];
        $phoneNum['prefix'] = $data['phone_prefix'];
        $phoneNum['number'] = $data['phone_number'];
        $faxNum['country_code'] = $data['fax_country_code'];
        $faxNum['area_code'] = $data['fax_area_code'];
        $faxNum['prefix'] = $data['fax_prefix'];
        $faxNum['number'] = $data['fax_number'];
        $this->Phone->save($phoneNum);
        $this->Phone->save($faxNum);
        $params->phone_full = $phoneNum['country_code'] . ' ' . $phoneNum['area_code'] . '-' . $phoneNum['prefix'] . '-' . $phoneNum['number'];
        $params->fax_full = $faxNum['country_code'] . ' ' . $faxNum['area_code'] . '-' . $faxNum['prefix'] . '-' . $faxNum['number'];
        return $params;
    }

    private function getNextPharmacyInsuranceId()
    {
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.InsuranceGrid');
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.LaboratoryGrid');
        if($this->Pharmacy == NULL) $this->Pharmacy = MatchaModel::setSenchaModel('App.model.Pharmacy');
        return max($this->Pharmacy->nextId(), $this->Laboratory->nextId(), $this->Insurance->nextId());
    }

}

//$params = new stdClass();
//$params->name= 'test';
//$params->transmit_method = 'test';
//$params->email = 'test';
//$params->active  = 'test';
//$p = new Practice();
//print $p->addPharmacy($params);
