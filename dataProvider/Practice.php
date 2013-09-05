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

class Practice
{
	/**
	 * @var MatchaCUP
	 */
	private $p = null;
	/**
	 * @var MatchaCUP
	 */
	private $i = null;
	/**
	 * @var MatchaCUP
	 */
	private $l = null;
	/**
	 * @var MatchaCUP
	 */
	private $address = null;
	/**
	 * @var MatchaCUP
	 */
	private $phone = null;


    public function __construct()
    {
	    if($this->phone == null) $this->phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
	    if($this->address == null) $this->address = MatchaModel::setSenchaModel('App.model.administration.Address');
        return;
    }

	private function setPharmacyModel(){
		if($this->p == null) $this->p = MatchaModel::setSenchaModel('App.model.administration.Pharmacies');
	}

	private function setLaboratoryModel(){
		if($this->l == null) $this->l = MatchaModel::setSenchaModel('App.model.administration.Laboratories');
	}

	private function setInsuranceCompanyModel(){
		if($this->i == null) $this->i = MatchaModel::setSenchaModel('App.model.administration.InsuranceCompany');
	}

	//**********************************************************************************
	public function getPharmacies(){
	    $this->setPharmacyModel();

	    $rows = array();
	    foreach($this->p->load()->all() as $row){
//            $row['address_full'] = $row['line1'].' '.$row['line2'].' '.$row['city'].','.$row['state'].' '.$row['zip'].'-'.$row['plus_four'].' '.$row['country'];
//            array_push($rows, $this->phone->load(array('foreign_id'=>$row['id']))->one());

		    $rows[] = $row;
        }

	    return $rows;
    }

    public function addPharmacy(stdClass $params){
	    $this->setPharmacyModel();
	    $row = new stdClass();
        $row->id = $params->id;
        $row->name = $params->name;
        $row->transmit_method = $params->transmit_method;
        $row->email = $params->email;
        $row->active = $params->active;
	    $row = $this->p->save($row);
	    $params->id = $row['id'];

        $params = $this->addAddress($params);
        $params = $this->addPhones($params);

        return $params;
    }

    public function updatePharmacy(stdClass $params){
	    $this->setPharmacyModel();

	    $o = new stdClass();
	    $o->name = $params->name;
	    $o->transmit_method = $params->transmit_method;
	    $o->email = $params->email;
	    $o->active = $params->active;
	    $this->p->save($o);

        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);

        return $params;
    }


	//**********************************************************************************
    public function getLaboratories(){
	    $this->setLaboratoryModel();

        $rows = array();
        foreach ($this->l->load()->all() as $row)
        {
//            $address = $this->address->load(array('foreign_id'=>$row['id']), array('address_id', 'line1', 'line2', 'city', 'state', 'zip', 'plus_four', 'country'));
//            array_push($rows, $address);
//            $row = $this->getPhones($row);
//            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    public function addLaboratory(stdClass $params){
	    $this->setLaboratoryModel();
	    $o = new stdClass();
	    $o->name = $params->name;
	    $o->transmit_method = $params->transmit_method;
	    $o->email = $params->email;
	    $o->active = $params->active;
	    $o = $this->l->save($o);
	    $params->id = $o['id'];

        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateLaboratory(stdClass $params){
	    $this->setLaboratoryModel();

        $data = get_object_vars($params);
        $row['id'] = $data['id'];
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $this->l->save((object)$row);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }


	//**********************************************************************************
    public function getInsurances(){

	    $this->setInsuranceCompanyModel();
        $rows = array();
        foreach($this->i->load()->all() as $row)
        {
//            $address = $this->address->load(array('foreign_id'=>$row['id']), array('address_id', 'line1', 'line2', 'city', 'state', 'zip', 'plus_four', 'country'));
//            array_push($rows, $address);
//            $row = $this->getPhones($row);
//            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    public function addInsurance(stdClass $params){
	    $this->setInsuranceCompanyModel();

	    $o = new stdClass();
	    $o->id = $params->id;
	    $o->name = $params->name;
	    $o->attn = $params->attn;
	    $o->cms_id = $params->cms_id;
	    $o->freeb_type = $params->freeb_type;
	    $o->x12_receiver_id = $params->x12_receiver_id;
	    $o->x12_default_partner_id = $params->x12_default_partner_id;
	    $o->alt_cms_id = $params->alt_cms_id;
	    $o->active = $params->active;
        $this->i->save($o);

        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateInsurance(stdClass $params){
	    $this->setInsuranceCompanyModel();
	    $o = new stdClass();
	    $o->name = $params->name;
	    $o->attn = $params->attn;
	    $o->cms_id = $params->cms_id;
	    $o->freeb_type = $params->freeb_type;
	    $o->x12_receiver_id = $params->x12_receiver_id;
	    $o->x12_default_partner_id = $params->x12_default_partner_id;
	    $o->alt_cms_id = $params->alt_cms_id;
	    $o->active = $params->active;
        $this->i->save($o);

        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);

        return $params;
    }

//    public function getInsuranceNumbers(stdClass $params)
//    {
//        return $params;
//    }
//
//    public function getX12Partners(stdClass $params)
//    {
//        return $params;
//    }


    private function addAddress($params){
	    $o = new stdClass();
	    $o->line1 = $params->line1;
        $o->line2 = $params->line2;
        $o->city = $params->city;
        $o->state = $params->state;
        $o->zip = $params->zip;
        $o->plus_four = $params->plus_four;
        $o->country = $params->country;
        $o->foreign_id = $params->id;
        $this->address->save($o);
        return $params;
    }

    private function updateAddress($params)
    {
	    $o = new stdClass();
	    $o->line1 = $params->line1;
	    $o->line2 = $params->line2;
	    $o->city = $params->city;
	    $o->state = $params->state;
	    $o->zip = $params->zip;
	    $o->plus_four = $params->plus_four;
        $o->country = $params->country;
        $this->address->save($o);
        return $params;
    }

    private function getPhones($row){
        foreach ($this->phone->load(array('foreign_id'=>$row['id'])) as $phoneRow){
            switch ($phoneRow['type']){
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

    private function addPhones($params, $foreignType = ''){
        $prow['country_code'] = $params->phone_country_code;
        $prow['area_code'] = $params->phone_area_code;
        $prow['prefix'] = $params->phone_prefix;
        $prow['number'] = $params->phone_number;
        $prow['type'] = 'phone';
        $prow['foreign_type'] = $foreignType;
        $prow['foreign_id'] = $params->id;

        $frow['country_code'] = $params->fax_country_code;
        $frow['area_code'] = $params->fax_area_code;
        $frow['prefix'] = $params->fax_prefix;
        $frow['number'] = $params->fax_number;
        $frow['type'] = 'fax';
	    $frow['foreign_type'] = $foreignType;
        $frow['foreign_id'] = $params->id;

	    $prow = $this->phone->save((object)$prow);
	    $frow = $this->phone->save((object)$frow);

	    $params->phone_id = $prow['id'];
	    $params->fax_id = $frow['id'];

//        $params->phone_full = $prow['country_code'] . ' ' . $prow['area_code'] . '-' . $prow['prefix'] . '-' . $prow['number'];
//        $params->fax_full = $frow['country_code'] . ' ' . $frow['area_code'] . '-' . $frow['prefix'] . '-' . $frow['number'];
        return $params;
    }

    private function updatePhones($params){
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
        $this->phone->save((object)$prow);
        $this->phone->save((object)$frow);
        $params->phone_full = $prow['country_code'] . ' ' . $prow['area_code'] . '-' . $prow['prefix'] . '-' . $prow['number'];
        $params->fax_full = $frow['country_code'] . ' ' . $frow['area_code'] . '-' . $frow['prefix'] . '-' . $frow['number'];
        return $params;
    }

}