<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: OfficeNotes.php
 * Date: 2/1/12
 * Time: 9:05 PM
 */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['root'].'/classes/dbHelper.php');
class OfficeNotes extends dbHelper {

    public function getOfficeNotes(stdClass $params){
        $wherex = (isset($params->show))? 'WHERE activity = 1' : '';
        $this->setSQL("SELECT * FROM onotes $wherex ORDER BY date DESC LIMIT $params->start, $params->limit");
        $rows = array();
        foreach($this->fetchRecords(PDO::FETCH_ASSOC) as $row){
        	array_push($rows, $row);
        }
        return $rows;
    }

    public function addOfficeNotes(stdClass $params){

        $params->user = $_SESSION['user']['name'];
        $params->date = date('Y-m-d H:i:s');
        $params->activity = 1;

        $data = get_object_vars($params);
        $sql = $this->sqlBind($data, 'onotes', 'I');
        $this->setSQL($sql);
        $this->execLog();

        return $params;
    }

    public function updateOfficeNotes(stdClass $params)
    {
        $data = get_object_vars($params);
        $sql = $this->sqlBind($data, 'onotes', 'U', 'id="'.$params->id.'"');
        $this->setSQL($sql);
        $this->execLog();

        return $params;
    }
}
