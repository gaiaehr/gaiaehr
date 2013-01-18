<?php
/*
 GaiaEHR (Electronic Health Records)
 Prescriptions.php
 Precriptions dataProvider
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
class Prescriptions
{
    function __construct()
    {
        $this->db = new dbHelper();
        $this->doc = new DocumentHandler();
        return;
    }

//    public function addDocumentsPatientInfo($params)
//    {
//        $foo = array();
//        $foo['pid'] = $_SESSION['patient']['pid'];
//        $foo['uid'] = $_SESSION['user']['id'];
//        $foo['created_date'] = date('Y-m-d H:i:s');
//        $foo['document_id'] = $params->document_id;
//        $this->db->setSQL($this->db->sqlBind($foo, 'patient_prescriptions', 'I'));
//        $this->db->execLog();
//        $prescription_id = $this->db->lastInsertId;
//		foreach ($params->medications as $med)
//		{
//			$foo = array();
//			$foo['pid'] = $_SESSION['patient']['pid'];
//			$foo['eid'] = $params -> eid;
//			$foo['prescription_id'] = $prescription_id;
//			$foo['medication'] = $med -> medication;
//			$foo['RXCUI'] = $med -> RXCUI;
//			$foo['DIRECTIONS'] = $med -> take_pills.$med -> type.' '.$med -> route.' '.$med -> prescription_often.' '.$med -> prescription_when;
//			$foo['take_pills'] = $med -> take_pills;
//			$foo['form'] = $med -> form;
//			$foo['route'] = $med -> route;
//			$foo['prescription_often'] = $med -> prescription_often;
//			$foo['prescription_when'] = $med -> prescription_when;
//			$foo['STRENGTH'] = $med -> dose;
//			$foo['dispense'] = $med -> dispense;
//			$foo['refill'] = $med -> refill;
//			$foo['begin_date'] = $med -> begin_date;
//			$foo['end_date'] = $med -> end_date;
//			$this -> db -> setSQL($this -> db -> sqlBind($foo, 'patient_medications', 'I'));
//			$this -> db -> execLog();
//		}
//    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function getPrescriptions(stdClass $params)
    {

        $this->db->setSQL("SELECT patient_prescriptions.*,
                                  patient_documents.url AS docUrl
                             FROM patient_prescriptions
                        LEFT JOIN patient_documents ON patient_prescriptions.document_id = patient_documents.id
                            WHERE patient_prescriptions.pid = '$params->pid'
                         ORDER BY id DESC");

        $prescriptions = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        foreach($prescriptions AS $index => $row){
            $prescriptions[$index]['medications'] = $this->getMedicationsByPrescriptionId($row['id']);
        }
        return $prescriptions;
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function addPrescription(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id'], $data['medications'], $data['docUrl']);
        $params->orderItems = array(
            array(
                'Description',
                'Instructions',
                'Dispense',
                'Refill',
                'Dx'
            )
        );
        foreach($params->medications AS $row){
            $params->orderItems[] = array(
                $row->STR.', '.$row->dose.' '.$row->route.' '.$row->form,
                $row->prescription_when,
                $row->dispense,
                $row->refill,
                $row->ICDS
            );
        }
        $params->docType = 'Rx';
        $params->templateId = 5;
        $result = $this->doc->createDocument($params);

        if($result['success']){
            $data['document_id'] = $params->document_id = $result['doc']['id'];
            $params->docUrl = $result['doc']['url'];
            $this->db->setSQL($this->db->sqlBind($data, 'patient_prescriptions', 'I'));
            $this->db->execLog();
            $params->id = $this->db->lastInsertId;
            if($params->id != 0){
                $params->medications = $this->addPrescriptionMedication($params->medications, $params->id);
            }
        }
        return $params;
    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function updatePrescription(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id'], $data['medications'], $data['docUrl']);

        $params->orderItems = array(
            array(
                'Description',
                'Instructions',
                'Dispense',
                'Refill',
                'Dx'
            )
        );
        foreach($params->medications AS $row){
            $params->orderItems[] = array(
                $row->STR.', '.$row->dose.' '.$row->route.' '.$row->form,
                $row->prescription_when,
                $row->dispense,
                $row->refill,
                $row->ICDS
            );
        }
        $this->doc->deleteDocumentById($params->document_id);

        $params->docType = 'Rx';
        $params->templateId = 5;
        $result = $this->doc->createDocument($params);


        if($result['success']){
            $data['document_id'] = $params->document_id = $result['doc']['id'];
            $params->docUrl = $result['doc']['url'];
            $this->db->setSQL($this->db->sqlBind($data, 'patient_prescriptions', 'U', array('id' => $params->id)));
            $this->db->execLog();
            foreach($params->medications AS $index => $med){
                if($med->id == 0){
                    $params->medications[$index] = $this->addPrescriptionMedication($med, $params->id);
                }else{
                    $params->medications[$index] = $this->updatePrescriptionMedication($med);
                }
            }
        }
        return $params;
    }

    /**
     * @param $params
     * @return array
     */
    public function clonePrescription($params)
    {
        if(is_array($params)){
            foreach ($params as $row) {
                $data = get_object_vars($row);
                unset($data['form'], $data['id'], $data['dose']);
                $data['STRENGTH'] = $row->dose;
                $data['DIRECTIONS'] = $row->take_pills . $row->form . ' ' . $row->route . ' ' . $row->prescription_often . ' ' . $params->prescription_when;
                $data['pid'] = $_SESSION['patient']['pid'];
                $this->db->setSQL($this->db->sqlBind($data, 'patient_medications', 'I'));
                $this->db->execLog();

            }
        }else{
            $data = get_object_vars($params);
            unset($data['form'], $data['id'], $data['dose']);
            $data['STRENGTH'] = $params->dose;
            $data['DIRECTIONS'] = $params->take_pills . $params->form . ' ' . $params->route . ' ' . $params->prescription_often . ' ' . $params->prescription_when;
            $data['pid'] = $_SESSION['patient']['pid'];
            $this->db->setSQL($this->db->sqlBind($data, 'patient_medications', 'I'));
            $this->db->execLog();
            $params->id = $this->db->lastInsertId;
        }
        return $params;
    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function getMedicationsByPrescriptionId($params)
    {
        $prescription_id = is_numeric($params) ? $params : $params->prescription_id;
        if($prescription_id != 0){
            $this->db->setSQL("SELECT *
					             FROM patient_medications
							    WHERE prescription_id = '$prescription_id'
						     ORDER BY id DESC");
            return $this->db->fetchRecords(PDO::FETCH_ASSOC);
        }else{
            return array();
        }
    }

    /**
     * @param $params
     * @param int $prescriptionId
     * @return mixed
     */
    public function addPrescriptionMedication($params, $prescriptionId = 0)
    {
        if(is_array($params)){
            $count = 0;
            foreach($params AS $param){
                $param->prescription_id = $prescriptionId;
                $data = get_object_vars($param);
                unset($data['id']);
                $this->db->setSQL($this->db->sqlBind($data, 'patient_medications', 'I'));
                $this->db->execLog();
                $param->id = $this->db->lastInsertId;
                $params[$count] = $param;
                $count++;
            }
        }else{
            $params->prescription_id = $prescriptionId;
            $data = get_object_vars($params);
            unset($data['id']);
            $data['prescription_id'] = $prescriptionId;
            $this->db->setSQL($this->db->sqlBind($data, 'patient_medications', 'I'));
            $this->db->execLog();
            $params->id = $this->db->lastInsertId;
        }
        return $params;

    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function updatePrescriptionMedication(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id']);
        $this->db->setSQL($this->db->sqlBind($data, 'patient_medications', 'U', array('id' => $params->id)));
        $this->db->execLog();
        return $params;
    }

    /**
     * @param $params
     * @return array
     */
    public function getSigCodesByQuery($params){
        $this->db->setSQL("SELECT option_value, option_name
						     FROM combo_lists_options
							WHERE (option_value LIKE '$params->query%'
							   OR  option_name  LIKE '$params->query%')
							  AND  list_id = '86'
						 ORDER BY option_value ASC");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }
}
//print '<pre>';
//$p = new Prescriptions();
//$params = new stdClass();
//$params->query = 't';
//print_r($p->getSigCodesByQuery($params));
