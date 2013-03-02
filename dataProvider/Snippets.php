<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

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

if(!isset($_SESSION)){
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');
class Snippets {

    private $db;

    function __construct(){
        $this->db = new MatchaHelper();
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
