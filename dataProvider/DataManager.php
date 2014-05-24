<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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

include_once (ROOT . '/dataProvider/Laboratories.php');
include_once (ROOT . '/dataProvider/Immunizations.php');
include_once (ROOT . '/dataProvider/Services.php');

class DataManager
{
    /**
     * @var MatchaHelper
     */
    private $db;

    private $labs;
    private $immu;
    private $serv;

    function __construct()
    {
        $this->db = new MatchaHelper();
        $this->labs = new Laboratories();
        $this->immu = new Immunizations();
        $this->serv = new Services();
        return;
    }

    public function getServices(stdClass $params)
    {
        if($params->code_type == 'CVX') {
            $records = array();
            $cvx = $this->immu->getCVXCodesByStatus();
            foreach ($cvx AS $row) {
                $foo = array();
                $foo['code'] = $row['cvx_code'];
                $foo['code_type'] = 'CVX';
                $foo['code_text'] = $row['name'];
                $foo['code_text_short'] = $row['description'];
                $foo['active'] = ($row['status'] == 'Active' ? true : false);


	            if(($params->active) && $params->active){
		            if($foo['active']) $records[] = $foo;
	            }else{
		            $records[] = $foo;
	            }

            }
            $total = count($records);
            $records = array_slice($records, $params->start, $params->limit);
            return array(
                'totals' => $total,
                'rows' => $records
            );
        }

        if($params->code_type == 'CPT4') {
            $records = array();
            $cpt = $this->serv->getCptCodesList($params);
            $total = count($cpt);
            $cpt = array_slice($cpt, $params->start, $params->limit);
            foreach ($cpt AS $row) {
                $row['code_type'] = 'CPT4';
                $records[] = $row;
            }
            return array(
                'totals' => $total,
                'rows' => $records
            );
        }

        if($params->code_type == 'HCPCS') {
            $records = array();
            $hcpcs = $this->serv->getHCPCList($params);
            $total = count($hcpcs);
            $hcpcs = array_slice($hcpcs, $params->start, $params->limit);
            foreach ($hcpcs AS $row) {
                $row['code_type'] = 'HCPCS';
                $records[] = $row;
            }
            return array(
                'totals' => $total,
                'rows' => $records
            );
        }

        $records = $this->labs->getAllLoincPanels($params);
        $total = count($records);
        $records = array_slice($records, $params->start, $params->limit);
        return array(
            'totals' => $total,
            'rows' => $records
        );

    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addService(stdClass $params){
        if ($params->code_type == 'CPT4') {
            $tableX = 'cpt_codes';
        } elseif ($params->code_type == 'HCPCS') {
            $tableX = 'hcpcs_codes';
        } elseif ($params->code_type == 'Immunizations') {
            $tableX = 'immunizations';
        } else {
            $tableX = 'labs';
        }
        $data = get_object_vars($params);
        foreach ($data as $key => $val) {
            if ($val == null || $val == '') {
                unset($data[$key]);
            }
        }
        unset($data['id']);
        $sql = $this->db->sqlBind($data, $tableX, 'I');
        $this->db->setSQL($sql);
        $this->db->execLog();
        $params->id = $this->db->lastInsertId;
        return array(
            'totals' => 1,
            'rows' => $params
        );
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateService(stdClass $params){
	    try{
		    $data = get_object_vars($params);
		    foreach ($data as $key => $val) {
			    if ($val == null || $val == '') {
				    unset($data[$key]);
			    }
		    }
		    unset($data['id']);
		    if ($params->code_type == 'CPT4') {
			    unset($data['code_type']);
			    $data['active'] = $params->active ? '1' : '0';
			    $sql = $this->db->sqlBind($data, 'cpt_codes', 'U', array('id' => $params->id));
		    } elseif ($params->code_type == 'HCPCS') {
			    $sql = $this->db->sqlBind($data, 'hcpcs_codes', 'U', array('id' => $params->id));
		    } elseif ($params->code_type == 'Immunizations') {
			    $sql = $this->db->sqlBind($data, 'immunizations', 'U', array('id' => $params->id));
		    } else {
			    $this->labs->updateLabPanel($params);
		    }

		    if(isset($sql)){
			    $this->db->setSQL($sql);
			    $this->db->execLog();
		    }

		    return $params;
	    }catch (Exception $e){
		    return array('success' => false, 'message' => $e->getMessage());
	    }

    }

    /**
     * CPT CODES SECTION!!!
     */
    /**
     * @param stdClass $params
     * @return array|stdClass
     */
    public function getCptCodes(stdClass $params){
        if ($params->filter === 0) {
            $record = $this->getCptRelatedByEidIcds($params->eid);
        } elseif ($params->filter === 1) {
            $record = $this->getCptUsedByPid($params->pid);
        } elseif ($params->filter === 2) {
            $record = $this->getCptUsedByClinic($params->pid);
        } else {
            $record = $this->getCptByEid($params->eid);
        }
        return $record;
    }

    public function addCptCode(stdClass $params){
        $data = get_object_vars($params);
        unset($data['code_text'], $data['code_text_medium']);
        foreach ($data as $key => $val) {
            if ($val == null || $val == '') {
                unset($data[$key]);
            }
        }
        $this->db->setSQL($this->db->sqlBind($data, 'encounter_services', 'I'));
        $this->db->execLog();
        $params->id = $this->db->lastInsertId;
        return array(
            'totals' => 1,
            'rows' => $params
        );
    }

    public function updateCptCode(stdClass $params){
        $data = get_object_vars($params);
        unset($data['id'], $data['eid'], $data['code'], $data['code_text'], $data['code_text_medium']);
        $params->id = intval($params->id);
        $this->db->setSQL($this->db->sqlBind($data, 'encounter_services', 'U', "id='$params->id'"));
        $this->db->execLog();
        return array(
            'totals' => 1,
            'rows' => $params
        );
    }

    public function deleteCptCode(stdClass $params){
        $this->db->setSQL("SELECT status FROM encounter_services WHERE id = '$params->id'");
        $cpt = $this->db->fetchRecord();
        if ($cpt['status'] == 0) {
            $this->db->setSQL("DELETE FROM encounter_services WHERE id ='$params->id'");
            $this->db->execLog();
        }
        return array(
            'totals' => 1,
            'rows' => $params
        );
    }

    /**
     * @param $eid
     * @return array
     */
    public function getCptRelatedByEidIcds($eid){
        $this->db->setSQL("SELECT DISTINCT cpt.code, cpt.code_text
                             FROM cpt_codes AS cpt
                       RIGHT JOIN cpt_icd AS ci ON ci.cpt = cpt.code
                        LEFT JOIN encounter_dx AS eci ON eci.code = ci.icd
                            WHERE eci.eid = '$eid'");
        $records = array();
        foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
            if ($row['code'] != null || $row['code'] != '') {
                $records[] = $row;
            }
        }
        return array(
            'totals' => count($records),
            'rows' => $records
        );
    }

    /**
     * @param $eid
     * @return array
     */
    public function getCptByEid($eid)
    {
        $this->db->setSQL("SELECT DISTINCT ecc.*, cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_services AS ecc
                        LEFT JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                            WHERE ecc.eid = '$eid' ORDER BY ecc.id ASC");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        return array(
            'totals' => count($records),
            'rows' => $records
        );
    }

    /**
     * @param $pid
     * @return array
     */
    public function getCptUsedByPid($pid)
    {
        $this->db->setSQL("SELECT DISTINCT cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_services AS ecc
                        LEFT JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                        LEFT JOIN encounters AS e ON ecc.eid = e.eid
                            WHERE e.pid = '$pid'
                         ORDER BY e.service_date DESC");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        return array(
            'totals' => count($records),
            'rows' => $records
        );
    }

    /**
     * @return array
     */
    public function getCptUsedByClinic()
    {
        $this->db->setSQL("SELECT DISTINCT cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_services AS ecc
                        LEFT JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                         ORDER BY cpt.code DESC");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        return array(
            'totals' => count($records),
            'rows' => $records
        );
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
//$t = new Services();
//print '<pre>';
//print_r($t->getLastRevisionByCode('ICD9'));
