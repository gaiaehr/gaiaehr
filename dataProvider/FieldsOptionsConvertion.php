<?php
//if (!isset($_SESSION)) {
//    session_name("GaiaEHR");
//    session_start();
//    session_cache_limiter('private');
//}
//include_once('dbhelper.php');
//
///**
// * @brief       Brief Description
// * @details     Detail Description ...
// *
// * @author      Ernesto J . Rodriguez(Certun) < erodriguez@certun . com >
// * @version     Vega 1.0
// * @copyright   Gnu Public License(GPLv3)
// */
//class FieldsOptionsConvertion extends dbHelper
//{
//
//    public function convert(){
//
//        $rows = array();
//
//        $sql = ("SELECT DISTINCT field_id  FROM forms_field_options ORDER BY field_id");
//        $this->setSQL($sql);
//        $fields = $this->fetchRecords(PDO::FETCH_ASSOC);
//
//
//        foreach($fields as $field){
//            $fid =$field['field_id'];
//            $foo = array();
//
//            $sql = ("SELECT oname, ovalue  FROM forms_field_options WHERE field_id = $fid  ORDER BY field_id");
//            $this->setSQL($sql);
//            $options = $this->fetchRecords(PDO::FETCH_ASSOC);
//
//            foreach($options as $option){
//
//                if(is_numeric($option['ovalue'])) $option['ovalue'] = intval($option['ovalue']);
//
//                if($option['ovalue'] === 'true'){
//                    $option['ovalue'] = true;
//                }elseif($option['ovalue'] === 'false'){
//                    $option['ovalue'] = false;
//                }
//
//
//                $foo[$option['oname']] = $option['ovalue'];
//
//            }
//
//            $rows[] = array('field_id'=>$fid,'options'=>json_encode($foo));
//        }
//
////        $this->setSQL("DROP TABLE IF EXISTS forms_field_options_test");
////        $this->execOnly();
////
////        $this->setSQL("CREATE TABLE forms_field_options_test (
////        id int NOT NULL AUTO_INCREMENT,
////        PRIMARY KEY(id),
////        field_id TEXT COMMENT 'Field ID',
////        options TEXT COMMENT 'Field options data stored as JSON string'
////        )");
////        $this->execOnly();
////
////
////        foreach($rows as $row){
////
////            $sql = $this->sqlBind($row, 'forms_field_options_test', 'I');
////            $this->setSQL($sql);
////            $this->execOnly();
////
////        }
//
//
//        echo 'Total of Rows Inserted = ';
//        echo count($rows);
//        echo '<br>';
//        echo 'Here is the array of data inserted...';
//        echo '<br><br>';
//        return print_r($rows);
//    }
//
//}
//
//$o = new FieldsOptionsConvertion();
//echo '<pre>';
//$o->convert();