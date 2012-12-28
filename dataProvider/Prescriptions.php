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
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/Encounter.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/Facilities.php');
include_once ($_SESSION['root'] . '/dataProvider/Documents.php');
class Prescriptions
{

    function __construct()
    {
        $this->db = new dbHelper();
        $this->user = new User();
        $this->patient = new Patient();
        $this->services = new Services();
        $this->facility = new Facilities();
        $this->documents = new Documents();
        return;
    }

    public function addDocumentsPatientInfo($params)
    {
        $foo = array();
        $foo['pid'] = $_SESSION['patient']['pid'];
        $foo['uid'] = $_SESSION['user']['id'];
        $foo['created_date'] = date('Y-m-d H:i:s');
        $foo['document_id'] = $params->document_id;
        $this->db->setSQL($this->db->sqlBind($foo, 'patient_prescriptions', 'I'));
        $this->db->execLog();
        $prescription_id = $this->db->lastInsertId;
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
//			$foo['type'] = $med -> type;
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
    }

    public function addPrescription(stdClass $params)
    {
        $data = get_object_vars($params);
        $this->db->setSQL($this->db->sqlBind($data, 'patient_prescriptions', 'I'));
        $this->db->execLog();
        $params->id = $this->db->lastInsertId;
        return $params;
    }

    public function clonePrescription($params)
    {
        if(is_array($params)){
            foreach ($params as $row) {
                $data = get_object_vars($row);
                unset($data['type'], $data['id'], $data['dose']);
                $data['STRENGTH'] = $row->dose;
                $data['DIRECTIONS'] = $row->take_pills . $row->type . ' ' . $row->route . ' ' . $row->prescription_often . ' ' . $params->prescription_when;
                $data['pid'] = $_SESSION['patient']['pid'];
                $this->db->setSQL($this->db->sqlBind($data, 'patient_medications', 'I'));
                $this->db->execLog();

            }
        }else{
            $data = get_object_vars($params);
            unset($data['type'], $data['id'], $data['dose']);
            $data['STRENGTH'] = $params->dose;
            $data['DIRECTIONS'] = $params->take_pills . $params->type . ' ' . $params->route . ' ' . $params->prescription_often . ' ' . $params->prescription_when;
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
    public function getPrescriptions(stdClass $params)
    {
        $this->db->setSQL("SELECT *
						     FROM patient_prescriptions
							WHERE pid = '$params->pid'
						 ORDER BY id DESC");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);

    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function updatePrescription(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id']);
        $sql = $this->db->sqlBind($data, 'patient_prescriptions', 'U', array('id' => $params->id));
        $this->db->setSQL($sql);
        $this->db->execLog();
        return array(
            'totals' => 1,
            'rows' => $params
        );

    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function getPrescriptionMedications(stdClass $params)
    {
        $prescription_id = $params->prescription_id;
        $this->db->setSQL("SELECT *
					         FROM patient_medications
							WHERE prescription_id = '$prescription_id'
						 ORDER BY id DESC");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);

    }

    /**
     * @param $params
     * @return mixed
     */
    public function addPrescriptionMedication($params)
    {

        $data = get_object_vars($params);
        unset($data['type'], $data['id'], $data['dose']);
        $data['STRENGTH'] = $params->dose;
        $data['DIRECTIONS'] = $params->take_pills . $params->type . ' ' . $params->route . ' ' . $params->prescription_often . ' ' . $params->prescription_when;
        $data['create_date'] = $this->parseDate($data['create_date']);
        $data['pid'] = $_SESSION['patient']['pid'];
        $this->db->setSQL($this->db->sqlBind($data, 'patient_medications', 'I'));
        $this->db->execLog();
        $params->id = $this->db->lastInsertId;
        return $params;

    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function updatePrescriptionMedication(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['type'], $data['id'], $data['prescription_when'], $data['prescription_often'], $data['route'], $data['type'], $data['take_pills'], $data['dose']);
        $sql = $this->db->sqlBind($data, 'patient_medications', 'U', array('id' => $params->id));
        $this->db->setSQL($sql);
        $this->db->execLog();
        return array(
            'totals' => 1,
            'rows' => $params
        );
    }
}
