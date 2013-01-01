<?php
/*
 GaiaEHR (Electronic Health Records)
 Immunizations.php
 Immunizations dataProvider
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
include_once ($_SESSION['root'] . '/classes/XMLParser.class.php');
/**
 * Created by JetBrains PhpStorm.
 * User: Plushy
 * Date: 8/19/12
 * Time: 10:12 AM
 * To change this template use File | Settings | File Templates.
 */

class Immunizations
{
    private $db;

    function __construct()
    {
        $this->db = new dbHelper();
        return;
    }

    public function getCVXCodesByStatus($status = 'Active')
    {
        $this->db->setSQL("SELECT * FROM cvx_codes WHERE status = '$status'");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

    public function updateCVXCode(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id']);
        $this->db->setSQL($this->db->sqlBind($data, 'cvx_codes', 'U', array('id' => $params->id)));
        $this->db->execLog();
    }

    public function updateCVXCodes($xmlFile = false)
    {
        $newCounter = 0;
        $xmlFile = ($xmlFile == false ? 'http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=cvx' : $xmlFile);
        $xml = simplexml_load_file($xmlFile);
        foreach ($xml AS $vac) {
            $vac = get_object_vars($vac);
            $data['cvx_code'] = trim($vac['Value'][2]);
            $data['name'] = $vac['Value'][1];
            $data['description'] = $vac['Value'][0];
            $data['note'] = (is_object($vac['Value'][3]) ? '' : $vac['Value'][3]);
            $data['status'] = $vac['Value'][4];
            $data['update_date'] = date('Y-m-d H:i:s', strtotime($vac['Value'][5]));

            $code = $data['code'];
            $this->db->setSQL("SELECT id FROM cvx_codes WHERE code = '$code'");
            $cvx = $this->db->fetchRecord(PDO::FETCH_ASSOC);
            if (isset($cvx['id'])) {
                $this->db->setSQL($this->db->sqlBind($data, 'cvx_codes', 'U', array('id' => $cvx['id'])));
                $this->db->execLog();
            } else {
                $this->db->setSQL($this->db->sqlBind($data, 'cvx_codes', 'I'));
                $this->db->execLog();
                $newCounter++;
            }
        }
        return array(
            'success' => true,
            'newCodes' => $newCounter
        );
    }

    public function updateMVXCodes($xmlFile = false)
    {
        $newCounter = 0;
        $xmlFile = ($xmlFile == false ? 'http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=tradename' : $xmlFile);
        $xml = simplexml_load_file($xmlFile);
        foreach ($xml AS $vac) {
            $vac = get_object_vars($vac);
            $data['cdc_product_name'] = trim($vac['Value'][0]);
            $data['description'] = trim($vac['Value'][1]);
            $data['cvx_code'] = trim($vac['Value'][2]);
            $data['manufacturer'] = (is_object($vac['Value'][3]) ? '' : $vac['Value'][3]);
            $data['mvx_code'] = trim($vac['Value'][4]);
            $data['mvx_status'] = $vac['Value'][5];
            $data['product_name_status'] = $vac['Value'][6];
            $data['update_date'] = date('Y-m-d H:i:s', strtotime($vac['Value'][7]));
            $mvx_code = $data['mvx_code'];
            $this->db->setSQL("SELECT id FROM cvx_mvx WHERE mvx_code = '$mvx_code'");
            $cvx = $this->db->fetchRecord(PDO::FETCH_ASSOC);
            if (isset($cvx['id'])) {
                $this->db->setSQL($this->db->sqlBind($data, 'cvx_mvx', 'U', array('id' => $cvx['id'])));
                $this->db->execLog();
            } else {
                $this->db->setSQL($this->db->sqlBind($data, 'cvx_mvx', 'I'));
                $this->db->execLog();
                $newCounter++;
            }
        }
        return array(
            'success' => true,
            'newCodes' => $newCounter
        );
    }

    public function getImmunizationLiveSearch(stdClass $params)
    {
        $this->db->setSQL("SELECT * FROM cvx_codes
							WHERE cvx_code 	  LIKE '$params->query%'
							   OR `name` 	  LIKE '$params->query%'
							   OR description LIKE '%$params->query%'");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        $total = count($records);
        $records = array_slice($records, $params->start, $params->limit);
        return array(
            'totals' => $total,
            'rows' => $records
        );
    }

    public function getMvx(stdClass $params)
    {
        return;
    }

    public function getMvxForCvx(stdClass $params)
    {
        $where = (isset($params->cvx_code) ? " WHERE cvx_code = '$params->cvx_code'" : '');
        $this->db->setSQL("SELECT * FROM cvx_mvx $where");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

}

//print '<pre>';
//$i = new Immunizations();
//////print $i->importCVXCodes();
////print_r($i->updateCVXCodes());
//print_r($i->updateMVXCodes());
