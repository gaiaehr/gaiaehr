<?php
/*
 GaiaEHR (Electronic Health Records)
 Practice.php
 Practice dataProvider
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
    session_name("GaiaEHR");
    session_start();
    session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
/**
 * @brief       Brief Description
 * @details     Detail Description ...
 *
 * @author      Ernesto J . Rodriguez(Certun) < erodriguez@certun . com >
 * @version     Vega 1.0
 * @copyright   Gnu Public License(GPLv3)
 */
class Practice extends dbHelper
{
    /**
     * Pharmacies stuff
     * @return array
     */
    public function getPharmacies()
    {
        $rows = array();
        $this->setSQL("SELECT p.id AS id,
                              p.name,
                              p.transmit_method,
                              p.email,
                              p.active,
                              a.id AS address_id,
                              a.line1,
                              a.line2,
                              a.city,
                              a.state,
                              a.zip,
                              a.plus_four,
                              a.country,
                              a.foreign_id AS address_foreign_id
                         FROM pharmacies AS p
                    LEFT JOIN addresses AS a ON p.id = a.foreign_id
                     ORDER BY p.name DESC");
        foreach($this->fetchRecords(PDO::FETCH_ASSOC) as $row) {
            $this->setSQL("SELECT * FROM phone_numbers WHERE phone_numbers.foreign_id =" . $row['id'] . "");
            $row = $this->getPhones($row);
            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addPharmacy(stdClass $params)
    {
        $params->id = $this->getNextPharmacyInsuranceId();
        $data = get_object_vars($params);
        $row['id'] = $data['id'];
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $sql = $this->sqlBind($row, 'pharmacies', 'I');
        $this->setSQL($sql);
        $this->execLog();
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);

        return $params;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updatePharmacy(stdClass $params)
    {
        $data = get_object_vars($params);
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $sql = $this->sqlBind($row, 'pharmacies', 'U', array('id' => $data['id']));
        $this->setSQL($sql);
        $this->execLog();
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }

    /**
     * Laboratories stuff
     * @return array
     */
    public function getLaboratories()
    {
        $rows = array();
        $this->setSQL("SELECT p.id AS id,
                              p.name,
                              p.transmit_method,
                              p.email,
                              p.active,
                              a.id AS address_id,
                              a.line1,
                              a.line2,
                              a.city,
                              a.state,
                              a.zip,
                              a.plus_four,
                              a.country,
                              a.foreign_id AS address_foreign_id
                         FROM laboratories AS p
                    LEFT JOIN addresses AS a ON p.id = a.foreign_id
                     ORDER BY p.name DESC");
        foreach ($this->fetchRecords(PDO::FETCH_ASSOC) as $row) {
            $this->setSQL("SELECT * FROM phone_numbers WHERE phone_numbers.foreign_id =" . $row['id'] . "");
            $row = $this->getPhones($row);
            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addLaboratory(stdClass $params)
    {
        $params->id = $this->getNextPharmacyInsuranceId();
        $data = get_object_vars($params);
        $row['id'] = $data['id'];
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $sql = $this->sqlBind($row, 'laboratories', 'I');
        $this->setSQL($sql);
        $this->execLog();
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);

        return $params;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateLaboratory(stdClass $params)
    {
        $data = get_object_vars($params);
        $row['name'] = $data['name'];
        $row['transmit_method'] = $data['transmit_method'];
        $row['email'] = $data['email'];
        $row['active'] = $data['active'];
        $sql = $this->sqlBind($row, 'laboratories', 'U', array('id' => $data['id']));
        $this->setSQL($sql);
        $this->execLog();
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }



    /**
     * Insurance stuff
     * @return array
     */
    public function getInsurances()
    {
        $rows = array();
        $this->setSQL("SELECT i.id AS id,
                              i.name,
                              i.attn,
                              i.cms_id,
                              i.freeb_type,
                              i.x12_receiver_id,
                              i.x12_default_partner_id,
                              i.active,
                              i.alt_cms_id,
                              a.id AS address_id,
                              a.line1,
                              a.line2,
                              a.city,
                              a.state,
                              a.zip,
                              a.plus_four,
                              a.country,
                              a.foreign_id AS address_foreign_id
                         FROM insurance_companies AS i
                    LEFT JOIN addresses AS a ON i.id = a.foreign_id
                     ORDER BY i.name DESC");
        foreach($this->fetchRecords(PDO::FETCH_ASSOC) as $row) {
            $this->setSQL("SELECT * FROM phone_numbers WHERE phone_numbers.foreign_id =" . $row['id'] . "");
            $row = $this->getPhones($row);
            $row['address_full'] = $row['line1'] . ' ' . $row['line2'] . ' ' . $row['city'] . ',' . $row['state'] . ' ' . $row['zip'] . '-' . $row['plus_four'] . ' ' . $row['country'];
            array_push($rows, $row);
        }
        return $rows;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addInsurance(stdClass $params)
    {
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
        $sql = $this->sqlBind($row, 'insurance_companies', 'I');
        $this->setSQL($sql);
        $this->execLog();
        $params = $this->addAddress($params);
        $params = $this->addPhones($params);
        return $params;
    }

    public function updateInsurance(stdClass $params)
    {
        $data = get_object_vars($params);
        $row['name'] = $data['name'];
        $row['attn'] = $data['attn'];
        $row['cms_id'] = $data['cms_id'];
        $row['freeb_type'] = $data['freeb_type'];
        $row['x12_receiver_id'] = $data['x12_receiver_id'];
        $row['x12_default_partner_id'] = $data['x12_default_partner_id'];
        $row['alt_cms_id'] = $data['alt_cms_id'];
        $row['active'] = $data['active'];
        $sql = $this->sqlBind($row, 'insurance_companies', 'U', array('id' => $data['id']));
        $this->setSQL($sql);
        $this->execLog();
        $params = $this->updateAddress($params);
        $params = $this->updatePhones($params);
        return $params;
    }

    /**
     * Insurance Numbers stuff
     * @param stdClass $params
     * @return stdClass
     */
    public function getInsuranceNumbers(stdClass $params)
    {

        return $params;
    }

    /**
     * X12 Partners stuff
     * @param stdClass $params
     * @return stdClass
     */
    public function getX12Partners(stdClass $params)
    {

        return $params;
    }

    /**
     * @param $params
     * @internal param $data
     * @return mixed
     */
    private function addAddress($params)
    {
        $data = get_object_vars($params);
        $arow['line1'] = $data['line1'];
        $arow['line2'] = $data['line2'];
        $arow['city'] = $data['city'];
        $arow['state'] = $data['state'];
        $arow['zip'] = $data['zip'];
        $arow['plus_four'] = $data['plus_four'];
        $arow['country'] = $data['country'];
        $arow['foreign_id'] = $data['id'];
        $sql = $this->sqlBind($arow, 'addresses', 'I');
        $this->setSQL($sql);
        $this->execOnly();
        $params->address_full = $params->line1 . ' ' . $params->line2 . ' ' . $params->city . ',' . $params->state . ' ' . $params->zip . '-' . $params->plus_four . ' ' . $params->country;
        return $params;
    }

    /**
     * @param $params
     * @internal param $data
     * @return mixed
     */
    private function updateAddress($params)
    {
        $data = get_object_vars($params);
        $arow['line1'] = $data['line1'];
        $arow['line2'] = $data['line2'];
        $arow['city'] = $data['city'];
        $arow['state'] = $data['state'];
        $arow['zip'] = $data['zip'];
        $arow['plus_four'] = $data['plus_four'];
        $arow['country'] = $data['country'];
        $sql = $this->sqlBind($arow, 'addresses', 'U', array('foreign_id' => $data['id']));
        $this->setSQL($sql);
        $this->execLog();
        $params->address_full = $params->line1 . ' ' . $params->line2 . ' ' . $params->city . ',' . $params->state . ' ' . $params->zip . '-' . $params->plus_four . ' ' . $params->country;
        return $params;
    }

    /**
     * @param $row
     * @return array
     */
    private function getPhones($row)
    {
        $this->setSQL("SELECT * FROM phone_numbers WHERE phone_numbers.foreign_id =" . $row['id'] . "");
        foreach ($this->fetchRecords(PDO::FETCH_ASSOC) as $phoneRow) {
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

    /**
     * @param $params
     * @internal param $data
     * @return mixed
     */
    private function addPhones($params)
    {
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
        $sql = $this->sqlBind($prow, 'phone_numbers', 'I');
        $this->setSQL($sql);
        $this->execOnly();
        $sql = $this->sqlBind($frow, 'phone_numbers', 'I');
        $this->setSQL($sql);
        $this->execOnly();

        $params->phone_full = $prow['country_code'] . ' ' . $prow['area_code'] . '-' . $prow['prefix'] . '-' . $prow['number'];
        $params->fax_full = $frow['country_code'] . ' ' . $frow['area_code'] . '-' . $frow['prefix'] . '-' . $frow['number'];

        return $params;
    }

    private function updatePhones($params)
    {
        $data = get_object_vars($params);

        $prow['country_code'] = $data['phone_country_code'];
        $prow['area_code'] = $data['phone_area_code'];
        $prow['prefix'] = $data['phone_prefix'];
        $prow['number'] = $data['phone_number'];

        $frow['country_code'] = $data['fax_country_code'];
        $frow['area_code'] = $data['fax_area_code'];
        $frow['prefix'] = $data['fax_prefix'];
        $frow['number'] = $data['fax_number'];
        $sql = $this->sqlBind($prow, 'phone_numbers', 'U', array(
            'foreign_id' => $data['id'],
            'type' => 2
        ));
        $this->setSQL($sql);
        $this->execLog();
        $sql = $this->sqlBind($frow, 'phone_numbers', 'U', array(
            'foreign_id' => $data['id'],
            'type' => 5
        ));
        $this->setSQL($sql);
        $this->execLog();

        $params->phone_full = $prow['country_code'] . ' ' . $prow['area_code'] . '-' . $prow['prefix'] . '-' . $prow['number'];
        $params->fax_full = $frow['country_code'] . ' ' . $frow['area_code'] . '-' . $frow['prefix'] . '-' . $frow['number'];

        return $params;
    }

    /**
     * @return mixed
     */
    private function getNextPharmacyInsuranceId()
    {
        $this->setSQL("SELECT MAX(id) AS maxid FROM pharmacies");
        $prec = $this->fetchRecord();
        $this->setSQL("SELECT MAX(id) AS maxid FROM laboratories");
        $lrec = $this->fetchRecord();
        $this->setSQL("SELECT MAX(id) AS maxid FROM insurance_companies");
        $irec = $this->fetchRecord();
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
