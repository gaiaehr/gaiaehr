<?php
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Laboratories.php');
//include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
//include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
//include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
//include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
/**
 * @brief       Services Class.
 * @details     This class will handle all services
 *
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 *
 */
class DataManager
{
	/**
	 * @var dbHelper
	 */
	private $db;

    private $labs;

	function __construct()
	{
		$this->db = new dbHelper();
        $this->labs = new Laboratories();
        return;
	}


    public function getServices(stdClass $params)
    {
        /*
        * define $code_table
        */

        if($params->code_type == 'CPT4') {
            $tableX = 'cpt_codes';
        } elseif($params->code_type == 'HCPCS'){
            $tableX = 'hcpcs_codes';
        }elseif($params->code_type == 'Immunizations') {
            $tableX = 'immunizations';
        } else {
            return $this->labs->getAllLabs($params);
        }


        $sortX = isset($params->sort) ? $params->sort[0]->property . ' ' . $params->sort[0]->direction : 'code ASC';
        if($params->query == ''){
            $this->db->setSQL("SELECT DISTINCT * FROM $tableX WHERE code IS NOT NULL AND active = '$params->active' ORDER BY $sortX");
        }else{
            $this->db->setSQL("SELECT DISTINCT * FROM $tableX WHERE code IS NOT NULL AND active = '$params->active' AND (code_text LIKE '%$params->query%' OR code LIKE '$params->query%') ORDER BY $sortX");
        }
        $records = $this->db->fetchRecords(PDO::FETCH_CLASS);
        $total   = count($records);
        $recs = array_slice($records,$params->start,$params->limit);
        $records = array();
        foreach($recs as $rec) {
            $rec->code_type = $params->code_type;
            $records[]      = $rec;
        }
        return array('totals'=> $total,
            'rows'  => $records);
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addService(stdClass $params)
    {
        if($params->code_type == 'CPT4') {
            $tableX = 'cpt_codes';
        } elseif($params->code_type == 'HCPCS'){
            $tableX = 'hcpcs_codes';
        }elseif($params->code_type == 'Immunizations') {
            $tableX = 'immunizations';
        } else {
            $tableX = 'labs';
        }

        $data = get_object_vars($params);

        foreach($data as $key=>$val ){
            if($val == null || $val == '')
                unset($data[$key]);
        }
        unset($data['id']);
        $sql = $this->db->sqlBind($data, $tableX, 'I');
        $this->db->setSQL($sql);
        $this->db->execLog();
        $params->id = $this->db->lastInsertId;
        return array('totals'=> 1, 'rows'  => $params);
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateService(stdClass $params)
    {
        $data = get_object_vars($params);

        foreach($data as $key=>$val ){
            if($val == null || $val == '')
                unset($data[$key]);
        }

        if($params->code_type == 'CPT4') {
            $tableX = 'cpt_codes';
            unset($data['code_type']);
        } elseif($params->code_type == 'HCPCS'){
            $tableX = 'hcpcs_codes';
        } elseif($params->code_type == 'Immunizations') {
            $tableX = 'immunizations';
        } else {
            $tableX = 'labs_panels';
            $data['code_text_short'] = $params->code_text_short;
            unset($data['code_text'],$data['code_type'],$data['code']);
        }


        unset($data['id']);

        $sql = $this->db->sqlBind($data, $tableX, 'U', "id='$params->id'");
        $this->db->setSQL($sql);
        $this->db->execLog();
        return $params;
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
