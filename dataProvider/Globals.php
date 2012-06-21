<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Globals.php
 * Date: 2/3/12
 * Time: 12:46 PM
 */
if(!isset($_SESSION)){
    session_name ( "GaiaEHR" );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root']."/classes/dbHelper.php");
class Globals extends dbHelper {

    /**
     * @return array
     */
    public function getGlobals(){

        $this->setSQL("SELECT gl_name, gl_index, gl_value FROM globals");
        // ****************************************************************************************************
        // $rows = $mitos_db->fetchRecords(PDO::FETCH_ASSOC) because we wwant to print all recods into one row
        // ****************************************************************************************************
        $rows = array();
        foreach($this->fetchRecords() as $row){
            $rows[$row[0]] = $row[2];
        }
        return $rows;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateGlobals(stdClass $params){

        $data = get_object_vars($params);

        foreach($data as $key => $value ){
            if(is_int($value)){
                $rec = trim($value);
            } else {
                $rec = $value;
            }
            $this->setSQL("UPDATE globals
                SET   gl_value ='". $rec ."'"."
                WHERE gl_name  ='". $key ."'");
            $this->execLog();
        }

        $this->setGlobals();

        return $params;
    }

    /**
     * @static
     * @return mixed
     */
    public static function setGlobals(){
        $conn = new dbHelper();
        $conn->setSQL("SELECT gl_name, gl_value FROM globals");
        foreach($conn->fetchRecords(PDO::FETCH_ASSOC) as $setting){
            $_SESSION['global_settings'][$setting['gl_name']] = $setting['gl_value'];

        }

	    $_SESSION['global_settings']['timezone_offset']  = -14400;

        return $_SESSION;
    }
}
