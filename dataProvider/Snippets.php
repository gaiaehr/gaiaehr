<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 1/18/13
 * Time: 12:59 AM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
class Snippets {

    private $db;

    function __construct(){

        $this->db = new dbHelper();
//        $this->db->setTable('soap_snippets');
//        $this->db->setField('parentId', 'VARCHAR', 20, true, false);
//        $this->db->setField('text', 'TEXT', 1000, true, false);
//        $this->db->setField('index', 'INT', 11, true, false);
//        $this->db->setField('category', 'VARCHAR', 50, true, false);
//        $this->db->setField('leaf', 'TINYINT', 1, true, false);
//        $this->db->executeORM();
    }

    public function getSoapSnippetsByCategory($params){
        if(isset($params->category)){
            $this->db->setSQL("SELECT * FROM soap_snippets WHERE parentId = 'root' AND category = '$params->category'");
        }else{
            $this->db->setSQL("SELECT * FROM soap_snippets WHERE parentId = '$params->id'");
        }
        return $this->db->fetchRecords();
    }

    public function addSoapSnippets($params){
        if(is_array($params)){
            foreach($params AS $index => $row){
                $data = get_object_vars($row);
                unset($data['id']);
                $this->db->setSQL($this->db->sqlBind($data, 'soap_snippets', 'I'));
                $this->db->execLog();
                $params[$index]->id = $this->db->lastInsertId;
            }
        }else{
            $data = get_object_vars($params);
            unset($data['id']);
            $this->db->setSQL($this->db->sqlBind($data, 'soap_snippets', 'I'));
            $this->db->execLog();
            $params->id = $this->db->lastInsertId;
        }
        return $params;
    }

    public function updateSoapSnippets($params){
        if(is_array($params)){
            foreach($params AS $row){
                $data = get_object_vars($row);
                unset($data['id']);
                $this->db->setSQL($this->db->sqlBind($data, 'soap_snippets', 'U', array('id' => $row->id)));
                $this->db->execLog();
            }
        }else{
            $data = get_object_vars($params);
            unset($data['id']);
            $this->db->setSQL($this->db->sqlBind($data, 'soap_snippets', 'U', array('id' => $params->id)));
            $this->db->execLog();
        }
        return $params;
    }

    public function deleteSoapSnippets($params){
        $this->db->setSQL("DELETE FROM soap_snippets WHERE id = '$params->id'");
        $this->db->execLog();
        return $params;
    }
}

//$t = new Templates();
//print '<pre>';
//print_r($t->getSoapTemplatesByCategory(''));
