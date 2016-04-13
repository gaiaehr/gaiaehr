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
include_once(ROOT . '/classes/Address.php');
include_once(ROOT . '/classes/Phone.php');
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

    public function __construct(){
	    if(is_null($this->phone)) $this->phone = MatchaModel::setSenchaModel('App.model.administration.Phone');
	    if(is_null($this->address)) $this->address = MatchaModel::setSenchaModel('App.model.administration.Address');
    }

    public function __destruct(){
	    unset($this->p, $this->l, $this->i, $this->phone, $this->address);
    }

	private function setPharmacyModel(){
        if(!isset($this->p)) $this->p = MatchaModel::setSenchaModel('App.model.administration.Pharmacies');
	}

	private function setLaboratoryModel(){
        if(!isset($this->l)) $this->l = MatchaModel::setSenchaModel('App.model.administration.Laboratories');
	}

	private function setInsuranceCompanyModel(){
        if(!isset($this->i)) $this->i = MatchaModel::setSenchaModel('App.model.administration.InsuranceCompany');
	}

	//**********************************************************************************
	public function getPharmacies(){
	    $this->setPharmacyModel();
		$records = array();
	    foreach($this->p->load()->all() as $record){
		    $record = $this->getAddress($record);
		    $record = $this->getPhones($record);
		    $records[] = $record;
        }
	    return $records;
    }

    public function addPharmacy(stdClass $params){
	    $this->setPharmacyModel();
	    $record = $this->p->save($params);
	    $params->id = $record->id;
	    unset($record);
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updatePharmacy(stdClass $params){
	    $this->setPharmacyModel();
	    $this->p->save($params);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }


	//**********************************************************************************
    public function getLaboratories(){
	    $this->setLaboratoryModel();
	    $records = array();
        foreach ($this->l->load()->all() as $record){
	        $record = $this->getAddress($record);
	        $record = $this->getPhones($record);
	        $records[] = $record;
        }
        return $records;
    }

    public function addLaboratory(stdClass $params){
	    $this->setLaboratoryModel();
	    $record = $this->l->save($params);
	    $params->id = $record['id'];
	    unset($record);
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateLaboratory(stdClass $params){
	    $this->setLaboratoryModel();
        $this->l->save($params);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }


	//**********************************************************************************
    public function getInsurances(){
	    $this->setInsuranceCompanyModel();
	    $records = array();
        foreach($this->i->load()->all() as $record){
	        $record = $this->getAddress($record);
	        $record = $this->getPhones($record);
	        $records[] = $record;
        }
        return $records;
    }


    public function addInsurance(stdClass $params){
	    $this->setInsuranceCompanyModel();
	    $record = $this->i->save($params);
	    $params->id = $record['id'];
	    unset($record);
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateInsurance(stdClass $params){
	    $this->setInsuranceCompanyModel();
        $this->i->save($params);
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }

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
	    $record = $this->address->save($o);
	    $params->address_id = $record->id;
	    unset($o, $record);
	    if(is_object($this->p)){
		    $this->p->save($params);
	    }elseif(is_object($this->l)){
		    $this->l->save($params);
	    }elseif(is_object($this->i)){
		    $this->i->save($params);
	    }
	    $params->address_full = Address::fullAddress(
            $params->line1,
            $params->line2,
            $params->city,
            $params->state,
            $params->zip,
            $params->plus_four,
            $params->country
        );
	    return $params;
    }

    private function updateAddress($params)
    {
	    $o = new stdClass();
	    $o->id = $params->address_id;
	    $o->line1 = $params->line1;
	    $o->line2 = $params->line2;
	    $o->city = $params->city;
	    $o->state = $params->state;
	    $o->zip = $params->zip;
	    $o->plus_four = $params->plus_four;
        $o->country = $params->country;
        $this->address->save($o);
	    unset($o);
	    $params->address_full = Address::fullAddress(
            $params->line1,
            $params->line2,
            $params->city,
            $params->state,
            $params->zip,
            $params->plus_four,
            $params->country
        );
	    return $params;
    }

    private function getAddress($record){
	    $a = $this->address->load($record['address_id'])->one();
	    $record['line1'] = $a['line1'];
	    $record['line2'] = $a['line2'];
	    $record['city'] = $a['city'];
	    $record['state'] = $a['state'];
	    $record['zip'] = $a['zip'];
	    $record['plus_four'] = $a['plus_four'];
	    $record['country'] = $a['country'];
	    $record['address_full'] = Address::fullAddress(
            $a['line1'],
            $a['line2'],
            $a['city'],
            $a['state'],
            $a['zip'],
            $a['plus_four'],
            $a['country']
        );
        return $record;
    }
    private function getPhones($record){
	    $p = $this->phone->load($record['phone_id'])->one();
	    $record['phone_country_code'] = $p['country_code'];
	    $record['phone_area_code'] = $p['area_code'];
	    $record['phone_prefix'] = $p['prefix'];
	    $record['phone_number'] = $p['number'];
	    $record['phone_full'] = Phone::fullPhone($p['country_code'], $p['area_code'], $p['prefix'], $p['number']);
	    unset($p);
	    $f = $this->phone->load($record['fax_id'])->one();
	    $record['fax_country_code'] = $f['country_code'];
	    $record['fax_area_code'] = $f['area_code'];
	    $record['fax_prefix'] = $f['prefix'];
	    $record['fax_number'] = $f['number'];
	    $record['fax_full'] = Phone::fullPhone($f['country_code'], $f['area_code'], $f['prefix'], $f['number']);
		unset($f);
        return $record;
    }

    private function addPhones($params, $foreignType = ''){

	    $p = new stdClass();
	    $p->country_code = $params->phone_country_code;
	    $p->area_code = $params->phone_area_code;
	    $p->prefix = $params->phone_prefix;
	    $p->number = $params->phone_number;
	    $p->number_type = 'phone';
	    $p->foreign_type = $foreignType;
	    $p->foreign_id = $params->id;
	    $record = $this->phone->save($p);
	    $params->phone_id = $record->id;
	    $params->phone_full = Phone::fullPhone(
            $record->country_code,
            $record->area_code,
            $record->prefix,
            $record->number
        );
		unset($p, $record);
	    $f = new stdClass();
	    $f->country_code = $params->fax_country_code;
	    $f->area_code = $params->fax_area_code;
	    $f->prefix = $params->fax_prefix;
	    $f->number = $params->fax_number;
	    $f->number_type = 'fax';
	    $f->foreign_type = $foreignType;
	    $f->foreign_id = $params->id;
	    $record = $this->phone->save($f);
	    $params->fax_id = $record->id;
	    $params->fax_full = Phone::fullPhone(
            $record->country_code,
            $record->area_code,
            $record->prefix,
            $record->number
        );
	    unset($f, $record);
	    if(is_object($this->p)){
		    $this->p->save($params);
	    }elseif(is_object($this->l)){
		    $this->l->save($params);
	    }elseif(is_object($this->i)){
		    $this->i->save($params);
	    }
	    return $params;
    }

    private function updatePhones($params, $foreignType = ''){
	    $p = new stdClass();
	    $p->id = $params->phone_id;
	    $p->country_code = $params->phone_country_code;
	    $p->area_code = $params->phone_area_code;
	    $p->prefix = $params->phone_prefix;
	    $p->number = $params->phone_number;
	    $p->number_type = 'phone';
	    $p->foreign_type = $foreignType;
	    $p->foreign_id = $params->id;
	    $record = $this->phone->save($p);
	    $params->phone_full = Phone::fullPhone(
            $record->country_code,
            $record->area_code,
            $record->prefix,
            $record->number
        );
	    unset($p, $record);
	    $f = new stdClass();
	    $f->id = $params->fax_id;
	    $f->country_code = $params->fax_country_code;
	    $f->area_code = $params->fax_area_code;
	    $f->prefix = $params->fax_prefix;
	    $f->number = $params->fax_number;
	    $f->number_type = 'fax';
	    $f->foreign_type = $foreignType;
	    $f->foreign_id = $params->id;
	    $record = $this->phone->save($f);
	    $params->fax_full = Phone::fullPhone(
            $record->country_code,
            $record->area_code,
            $record->prefix,
            $record->number
        );
	    unset($f, $record);
	    return $params;
    }
}
