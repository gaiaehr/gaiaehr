<?php
/*
 GaiaEHR (Electronic Health Records)
 Orders.php
 Orders dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
if (!isset($_SESSION)) {
    session_name("GaiaEHR");
    session_start();
    session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/DocumentHandler.php');

class Orders
{

    function __construct()
    {
        $this->db = new dbHelper();
        $this->doc = new DocumentHandler();
        return;
    }

//    public function addOrdersLabs($params)
//    {
//        foreach ($params->labs as $lab) {
//            $foo = array();
//            $foo['uid'] = $_SESSION['user']['id'];
//            $foo['pid'] = $_SESSION['patient']['pid'];
//            $foo['document_id'] = $params->document_id;
//            $foo['labs'] = $lab->laboratories;
//            $this->db->setSQL($this->db->sqlBind($foo, 'patient_orders', 'I'));
//            $this->db->execLog();
//        }
//    }

    public function getPatientLabOrders($params)
    {
        $this->db->setSQL("SELECT patient_orders.*,
                                  patient_documents.url AS docUrl
                             FROM patient_orders
                        LEFT JOIN patient_documents ON patient_orders.document_id = patient_documents.id
                            WHERE patient_orders.order_type = 'lab'
                              AND patient_orders.pid = '$params->pid'");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

    public function addPatientLabOrder($params)
    {
        $data = get_object_vars($params);
        unset($data['id'],$data['docUrl']);
        $params->docType = 'Laboratory Order';
        $result = $this->doc->createDocument($params);
        if($result['success']){
            $params->docUrl = $result['doc']['url'];
            $data['document_id'] = $result['doc']['id'];
            $this->db->setSQL($this->db->sqlBind($data, 'patient_orders', 'I'));
            $this->db->execLog();
            $params->id = $this->db->lastInsertId;
        }
        return $params;
    }

    public function updatePatientLabOrder($params)
    {
        $data = get_object_vars($params);
        unset($data['id'],$data['docUrl']);
        $params->docType = 'Laboratory Order';
        $result = $this->doc->createDocument($params);
        if($result['success']){
            $params->docUrl = $result['doc']['url'];
            $data['document_id'] = $result['doc']['id'];
            $this->db->setSQL($this->db->sqlBind($data, 'patient_orders', 'U', array('id' => $params->id)));
            $this->db->execLog();
        }
        return $params;
    }

    public function getPatientXrayCtOrders($params)
    {
        $this->db->setSQL("SELECT patient_orders.*,
                                  patient_documents.url AS docUrl
                             FROM patient_orders
                        LEFT JOIN patient_documents ON patient_orders.document_id = patient_documents.id
                            WHERE patient_orders.order_type = 'rad'
                              AND patient_orders.pid = '$params->pid'");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

    public function addPatientXrayCtOrder($params)
    {
        $data = get_object_vars($params);
        unset($data['id'],$data['docUrl']);
        $params->docType = 'Radiology Order';
        $result = $this->doc->createDocument($params);
        if($result['success']){
            $params->docUrl = $result['doc']['url'];
            $data['document_id'] = $result['doc']['id'];
            $this->db->setSQL($this->db->sqlBind($data, 'patient_orders', 'I'));
            $this->db->execLog();
            $params->id = $this->db->lastInsertId;
        }
        return $params;
    }

    public function updatePatientXrayCtOrder($params)
    {
        $data = get_object_vars($params);
        unset($data['id'],$data['docUrl']);
        $params->docType = 'Radiology Order';
        $result = $this->doc->createDocument($params);
        if($result['success']){
            $params->docUrl = $result['doc']['url'];
            $data['document_id'] = $result['doc']['id'];
            $this->db->setSQL($this->db->sqlBind($data, 'patient_orders', 'U', array('id' => $params->id)));
            $this->db->execLog();
        }
        return $params;
    }

}
