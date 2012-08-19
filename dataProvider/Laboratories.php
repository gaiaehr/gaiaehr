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

class Laboratories
{
    private $db;

    function __construct()
    {
        $this->db = new dbHelper();
        return;
    }

    public function getAllLabs(stdClass $params)
    {

        $sortX = isset($params->sort) ? $params->sort[0]->property . ' ' . $params->sort[0]->direction : 'sequence ASC';
        $records = array();
        $this->db->setSQL("SELECT lp.id,
								  lp.parent_id,
								  lp.parent_loinc,
								  lp.sequence,
								  lp.default_unit,
								  loinc.SHORTNAME AS code_text_short,
								  lp.parent_name AS code_text,
								  lp.loinc_number AS code,
								  lp.active
						     FROM labs_panels AS lp
						     LEFT JOIN labs_loinc AS loinc on loinc.LOINC_NUM = lp.parent_loinc
						    WHERE parent_name LIKE '%$params->query%'
					          AND id = parent_id
					     ORDER BY $sortX");
        $recs = $this->db->fetchRecords(PDO::FETCH_CLASS);
        $total = count($recs);
        $recs = array_slice($recs,$params->start,$params->limit);
        foreach($recs as $rec) {
            $rec->code_type = $params->code_type;
            $records[]      = $rec;
        }
        return array('totals'=> $total, 'rows'  => $records);
    }
    /**
     * @param stdClass $params
     * @return array
     */
    public function getLabObservations(stdClass $params)
    {
        return $this->getLabObservationFieldsByParentId($params->selectedId);
    }
    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateLabObservation(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id']);
        //		foreach($data as $key => $val){
        //			if($val == null || $val == '') unset($data[$key]);
        //		}
        $this->db->setSQL($this->db->sqlBind($data, 'labs_panels', 'U', "id='$params->id'"));
        $this->db->execLog();
        return $params;
    }


    public function getActiveLaboratoryTypes()
    {
        $records = array();
        $this->db->setSQL("SELECT id, code_text_short, parent_name, loinc_name
						     FROM labs_panels
						    WHERE id = parent_id
						      AND active = '1'
					     ORDER BY parent_name ASC");
        $rows = $this->db->fetchRecords(PDO::FETCH_CLASS);
        foreach($rows as $row) {
            $row->label = ($row->code_text_short == '' || $row->code_text_short == null) ? $row->parent_name : $row->code_text_short;
            $row->fields = $this->getLabObservationFieldsByParentId($row->id);
            $records[] = $row;
        }
        return $records;
    }

    public function getLabObservationFieldsByParentId($id)
    {
        $records = array();
        $this->db->setSQL("SELECT lp.*,
								  loinc.SUBMITTED_UNITS
							 FROM labs_panels AS lp
						LEFT JOIN labs_loinc AS loinc ON lp.loinc_number = loinc.LOINC_NUM
							WHERE parent_id = '$id'
							  AND parent_id != id
						ORDER BY sequence");
        foreach($this->db->fetchRecords(PDO::FETCH_CLASS) as $row){
            $row->default_unit = ($row->default_unit == null || $row->default_unit == '') ? $row->SUBMITTED_UNITS : $row->default_unit;
            $records[] = $row;
        }
        return $records;
    }
}
