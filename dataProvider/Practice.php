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
        $rows = array();
        if($this->Pharmacy == NULL) $this->Pharmacy = MatchaModel::setSenchaModel('App.model.Pharmacy');
        if($this->Phone == NULL) $this->Phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
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
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.Laboratories');
        if($this->Address == NULL) $this->Address = MatchaModel::setSenchaModel('App.model.administration.Address');
        $rows = array();
        foreach ($this->Laboratory->load()->all() as $row)
        {
            $address = $this->Address->load(array('foreign_id'=>$row['id']), array('address_id', 'line1', 'line2', 'city', 'state', 'zip', 'plus_four', 'country'));
            array_push($rows, $address);
            $row = $this->getPhones($row);
            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    public function addLaboratory(stdClass $params)
    {
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.Laboratories');
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
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.Laboratories');
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
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.Insurance');
        if($this->Address == NULL) $this->Address = MatchaModel::setSenchaModel('App.model.administration.Address');
        $rows = array();
        foreach($this->Insurance->load()->all() as $row)
        {
            $address = $this->Address->load(array('foreign_id'=>$row['id']), array('address_id', 'line1', 'line2', 'city', 'state', 'zip', 'plus_four', 'country'));
            array_push($rows, $address);
            $row = $this->getPhones($row);
            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    public function addInsurance(stdClass $params)
    {
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.Insurance');
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
        $this->Insurance->save($row);
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateInsurance(stdClass $params)
    {
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.Insurance');
        $data = get_object_vars($params);
        $row['name'] = $data['name'];
        $row['attn'] = $data['attn'];
        $row['cms_id'] = $data['cms_id'];
        $row['freeb_type'] = $data['freeb_type'];
        $row['x12_receiver_id'] = $data['x12_receiver_id'];
        $row['x12_default_partner_id'] = $data['x12_default_partner_id'];
        $row['alt_cms_id'] = $data['alt_cms_id'];
        $row['active'] = $data['active'];
        $this->Insurance->save($row);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }

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
        $arow['line1'] = $data['line1'];
        $arow['line2'] = $data['line2'];
        $arow['city'] = $data['city'];
        $arow['state'] = $data['state'];
        $arow['zip'] = $data['zip'];
        $arow['plus_four'] = $data['plus_four'];
        $arow['country'] = $data['country'];
        $arow['foreign_id'] = $data['id'];
        $this->Address->save($arow);
        $params->address_full = $params->line1 . ' ' . $params->line2 . ' ' . $params->city . ',' . $params->state . ' ' . $params->zip . '-' . $params->plus_four . ' ' . $params->country;
        return $params;
    }

    private function updateAddress($params)
    {
        if($this->Address == NULL) $this->Address = MatchaModel::setSenchaModel('App.model.administration.Address');
        $data = get_object_vars($params);
        $arow['line1'] = $data['line1'];
        $arow['line2'] = $data['line2'];
        $arow['city'] = $data['city'];
        $arow['state'] = $data['state'];
        $arow['zip'] = $data['zip'];
        $arow['plus_four'] = $data['plus_four'];
        $arow['country'] = $data['country'];
        $this->Address->save($arow);
        $params->address_full = $params->line1 . ' ' . $params->line2 . ' ' . $params->city . ',' . $params->state . ' ' . $params->zip . '-' . $params->plus_four . ' ' . $params->country;
        return $params;
    }

    private function getPhones($row)
    {
        if($this->Phone == NULL) $this->Phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
        foreach ($this->Phone->load(array('foreign_id'=>$row['id'])) as $phoneRow)
        {
            switch ($phoneRow['type'])
            {
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
        $prow['country_code'] = $data['phone_country_code'];
        $prow['area_code'] = $data['phone_area_code'];
        $prow['prefix'] = $data['phone_prefix'];
        $prow['number'] = $data['phone_number'];
        $prow['type'] = 2;
        $prow['foreign_id'] = $data['id'];
        $frow['country_code'] = $data['fax_country_code'];
        $frow['area_code'] = $data['fax_area_code'];
        $frow['prefix'] = $data['fax_prefix'];
        $frow['number'] = $data['fax_number'];
        $frow['type'] = 5;
        $frow['foreign_id'] = $data['id'];
        $this->Phone->save($prow);
        $this->Phone->save($frow);
        $params->phone_full = $prow['country_code'] . ' ' . $prow['area_code'] . '-' . $prow['prefix'] . '-' . $prow['number'];
        $params->fax_full = $frow['country_code'] . ' ' . $frow['area_code'] . '-' . $frow['prefix'] . '-' . $frow['number'];
        return $params;
    }

    private function updatePhones($params)
    {
        if($this->Phone == NULL) $this->Phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
        $data = get_object_vars($params);
        $prow['foreign_id'] =  $data['id'];
        $prow['country_code'] = $data['phone_country_code'];
        $prow['area_code'] = $data['phone_area_code'];
        $prow['prefix'] = $data['phone_prefix'];
        $prow['number'] = $data['phone_number'];
        $prow['type'] = 2;
        $frow['foreign_id'] =  $data['id'];
        $frow['country_code'] = $data['fax_country_code'];
        $frow['area_code'] = $data['fax_area_code'];
        $frow['prefix'] = $data['fax_prefix'];
        $frow['number'] = $data['fax_number'];
        $prow['type'] = 5;
        $this->Phone->save($prow);
        $this->Phone->save($frow);
        $params->phone_full = $prow['country_code'] . ' ' . $prow['area_code'] . '-' . $prow['prefix'] . '-' . $prow['number'];
        $params->fax_full = $frow['country_code'] . ' ' . $frow['area_code'] . '-' . $frow['prefix'] . '-' . $frow['number'];
        return $params;
    }

    private function getNextPharmacyInsuranceId()
    {
        if($this->Pharmacy == NULL) $this->Pharmacy = MatchaModel::setSenchaModel('App.model.Pharmacy');
        if($this->Laboratory == NULL) $this->Laboratory = MatchaModel::setSenchaModel('App.model.administration.Laboratories');
        if($this->Insurance == NULL) $this->Insurance = MatchaModel::setSenchaModel('App.model.administration.Insurance');
        $prec = $this->Pharmacy->nextId();
        $lrec = $this->Laboratory->nextId();
        $irec = $this->Insurance->nextId();
        return max($prec['maxid'], $lrec['maxid'], $irec['maxid']) + 1;
    }

}

//$params = new stdClass();
//$params->name= 'test';
//$params->transmit_method = 'test';
//$params->email = 'test';
//$params->active  = 'test';
//$p = new Practice();
//print $p->addPharmacy($params);
