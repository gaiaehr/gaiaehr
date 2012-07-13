<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Facilities.php
 * Date: 2/3/12
 * Time: 10:38 AM
 */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'].'/classes/dbHelper.php');

class Facilities {
    /**
     * @var dbHelper
     */
    private $db;
    /**
     * Creates the dbHelper instance
     */
    function __construct(){
        $this->db = new dbHelper();
        return;
    }
    /**
     * @param stdClass $params
     * @return array
     */
    public function getFacilities(stdClass $params){

        if(isset($params->active)){
            $wherex = 'active = '.$params->active ;
        } else {
            $wherex = 'active = 1';
        }
        if(isset($params->sort)){
            $orderx = $params->sort[0]->property.' '.$params->sort[0]->direction;
        } else {
            $orderx = 'name';
        }
        $sql = "SELECT * FROM facility WHERE $wherex ORDER BY $orderx LIMIT $params->start,$params->limit";
        $this->db->setSQL($sql);
        $rows = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){

            if (strlen($row['pos_code']) <= 1){
                $row['pos_code'] = '0'.$row['pos_code'];
            } else {
                $row['pos_code'] = $row['pos_code'];
            }
            array_push($rows, $row);
        }

        return $rows;

    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addFacility(stdClass $params){

        $data = get_object_vars($params);

        $sql = $this->db->sqlBind($data, "facility", "I");
        $this->db->setSQL($sql);
        $this->db->execLog();

        $params->id = $this->db->lastInsertId;

        return $params;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateFacility(stdClass $params){

        $data = get_object_vars($params);

        $id = $data['id'];
        unset($data['id']);

        $sql = $this->db->sqlBind($data, "facility", "U", "id='$id'");
        $this->db->setSQL($sql);
        $this->db->execLog();

        return $params;
    }

    /**
     * Not in used. For Now you can only set the Facility "inactive"
     *
     * @param stdClass $params
     * @return stdClass
     */
    public function deleteFacility(stdClass $params){


        $sql = "UPDATE facility SET active = '0' WHERE id='$params->id'";

        $this->db->setSQL($sql);
        $this->db->execLog();

        return $params;
    }

    public function getFacilityInfo ($fid){

        $this->db->setSQL("SELECT name, phone, street, city, state, postal_code
                        	 FROM facility
                            WHERE id = '$fid'");
        $i = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        $facilityInfo = 'Facility: '.$i['name'].' '.$i['phone'].' '.$i['street'].' '.$i['city'].' '.$i['state'].' '.$i['postal_code'];


        return $facilityInfo;
    }

    public function getActiveFacilities (){
        $this->db->setSQL("SELECT * FROM facility WHERE active = '1'");
	    return $this->db->fetchRecord(PDO::FETCH_ASSOC);
    }


    public function getBillingFacilities (){
        $this->db->setSQL("SELECT * FROM facility WHERE active = '1' AND billing_location = '1'");
	    return $this->db->fetchRecord(PDO::FETCH_ASSOC);

    }


}