<?php
if(!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
/**
 * Created by JetBrains PhpStorm.
 * User: Plushy
 * Date: 8/19/12
 * Time: 10:12 AM
 * To change this template use File | Settings | File Templates.
 */

class SeviceCodes
{
    private $db;

    function __construct()
    {
        $this->db = new dbHelper();
        return;


    }
    /**
     * CPT CODES SECTION!!!
     */
    /**
     * @param stdClass $params
     * @return array|stdClass
     */
    public function getCptCodes(stdClass $params)
    {
        if($params->filter === 0) {
            $record = $this->getCptRelatedByEidIcds($params->eid);
        } elseif($params->filter === 1) {
            $record = $this->getCptUsedByPid($params->pid);
        } elseif($params->filter === 2) {
            $record = $this->getCptUsedByClinic($params->pid);
        } else {
            $record = $this->getCptByEid($params->eid);
        }
        return $record;
    }

    public function addCptCode(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['code_text'], $data['code_text_medium']);
        foreach($data as $key => $val) {
            if($val == null || $val == '') {
                unset($data[$key]);
            }
        }
        $this->db->setSQL($this->db->sqlBind($data, 'encounter_codes_cpt', 'I'));
        $this->db->execLog();
        $params->id = $this->db->lastInsertId;
        return array('totals'=> 1, 'rows'  => $params);
    }

    public function updateCptCode(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id'], $data['eid'], $data['code'], $data['code_text'], $data['code_text_medium']);
        $params->id = intval($params->id);
        $this->db->setSQL($this->db->sqlBind($data, 'encounter_codes_cpt', 'U', "id='$params->id'"));
        $this->db->execLog();
        return array('totals'=> 1, 'rows'  => $params);
    }

    public function deleteCptCode(stdClass $params)
    {
        $this->db->setSQL("SELECT status FROM encounter_codes_cpt WHERE id = '$params->id'");
        $cpt = $this->db->fetchRecord();
        if($cpt['status'] == 0) {
            $this->db->setSQL("DELETE FROM encounter_codes_cpt WHERE id ='$params->id'");
            $this->db->execLog();
        }
        return array('totals'=> 1, 'rows'  => $params);
    }

    /**
     * @param $eid
     * @return array
     */
    public function getCptRelatedByEidIcds($eid)
    {
        $this->db->setSQL("SELECT DISTINCT cpt.code, cpt.code_text
                             FROM cpt_codes as cpt
                       RIGHT JOIN cpt_icd as ci ON ci.cpt = cpt.code
                        LEFT JOIN encounter_codes_icdx as eci ON eci.code = ci.icd
                            WHERE eci.eid = '$eid'");
        $records = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
            if($row['code'] != null || $row['code'] != '') {
                $records[] = $row;
            }
        }
        return array('totals'=> count($records),
            'rows'  => $records);
    }

    /**
     * @param $eid
     * @return array
     */
    public function getCptByEid($eid)
    {
        $this->db->setSQL("SELECT DISTINCT ecc.*, cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_codes_cpt AS ecc
                        left JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                            WHERE ecc.eid = '$eid' ORDER BY ecc.id ASC");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        return array('totals'=> count($records),
            'rows'  => $records);
    }

    /**
     * @param $eid
     * @return array
     */
    public function getHCPCByEid($eid)
    {
        $this->db->setSQL("SELECT DISTINCT ech.*, hc.code, hc.code_text, hc.code_text_short
                             FROM encounter_codes_hcpcs AS ech
                        left JOIN hcpcs_codes AS hc ON ech.code = hc.code
                            WHERE ech.eid = '$eid' ORDER BY ech.id ASC");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        return array('totals'=> count($records),
            'rows'  => $records);
    }

    /**
     * @param $pid
     * @return array
     */
    public function getCptUsedByPid($pid)
    {
        $this->db->setSQL("SELECT DISTINCT cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_codes_cpt AS ecc
                        left JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                        LEFT JOIN form_data_encounter AS e ON ecc.eid = e.eid
                            WHERE e.pid = '$pid'
                         ORDER BY e.start_date DESC");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        return array('totals'=> count($records),
            'rows'  => $records);
    }

    /**
     * @return array
     */
    public function getCptUsedByClinic()
    {
        $this->db->setSQL("SELECT DISTINCT cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                             FROM encounter_codes_cpt AS ecc
                        left JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                         ORDER BY cpt.code DESC");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        return array('totals'=> count($records),
            'rows'  => $records);
    }

}
