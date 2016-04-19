<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once(ROOT . '/classes/MatchaHelper.php');
include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/lib/HL7/HL7.php');
include_once(ROOT . '/lib/HL7/HL7Client.php');

class HL7Messages {

	/**
	 * @var PDO
	 */
	public $conn;
	/**
	 * @var HL7
	 */
	public $hl7;
	/**
	 * @var MatchaCUP HL7Messages
	 */
	private $m;
	/**
	 * @var MatchaCUP Facility
	 */
	private $f;
	/**
	 * @var MatchaCUP HL7Client
	 */
	private $c;

    /**
     * @var bool|MatchaCUP Patient Contacts
     */
    private $PatientContacts;

    /**
     * @var MatchaCUP Encounter Services
     */
    private $EncounterServices;

	/**
	 * @var MatchaCUP PatientImmunization
	 */
	private $i;
	/**
	 * @var MatchaCUP Patient
	 */
	private $p;
	/**
	 * @var MatchaCUP Encounter
	 */
	private $e;
	/**
	 * @var MatchaCUP User
	 */
	private $u;
	/**
	 * @var MatchaCUP Referring Provider/Physician
	 */
	private $ReferringProvider;
	/**
	 * @var stdClass
	 */
	private $msg;
	/**
	 * @var int|array
	 */
	private $to;
	/**
	 * @var int|array
	 */
	private $from;
	/**
	 * @var int|array
	 */
	private $patient;
	/**
	 * @var bool|int|stdClass
	 */
	private $encounter;
	/**
	 * @var string
	 */
	private $type;

	function __construct() {
		$this->hl7 = new HL7();
		$this->conn = Matcha::getConn();
        if(!isset($this->p))
            $this->p = MatchaModel::setSenchaModel('App.model.patient.Patient');
        if(!isset($this->PatientContacts))
            $this->PatientContacts = MatchaModel::setSenchaModel('App.model.patient.PatientContacts');
        if(!isset($this->EncounterServices))
            $this->EncounterServices = MatchaModel::setSenchaModel('App.model.patient.EncounterService');
        if(!isset($this->e))
            $this->e = MatchaModel::setSenchaModel('App.model.patient.Encounter');
        if(!isset($this->u))
            $this->u = MatchaModel::setSenchaModel('App.model.administration.User');
        if(!isset($this->ReferringProvider))
            $this->ReferringProvider = MatchaModel::setSenchaModel('App.model.administration.ReferringProvider');
        if(!isset($this->m))
            $this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Message');
        if(!isset($this->c))
            $this->c = MatchaModel::setSenchaModel('App.model.administration.HL7Client');
        if(!isset($this->f))
            $this->f = MatchaModel::setSenchaModel('App.model.administration.Facility');
	}

	function broadcastADT($params){
		$this->c->addFilter('active', 1);
		$clients = $this->c->load()->all();

		foreach($clients as $client){
			$foo = new stdClass();
			$foo->to = $client['id'];
			$foo->from = $params->fid;
			$foo->pid = $params->pid;
			$foo->eid = isset($params->eid) ? $params->eid : 0;
			$this->sendADT($foo, $params->event);
			unset($foo);
		}

		return [ 'success' => true ];
	}

	/**
	 * @param $params
	 * @param $event
	 * @throws Exception
	 */
	function sendADT($params, $event){

		$this->to = $params->to;
		$this->from = $params->from;
		$this->patient = $params->pid;
		$this->encounter = isset($params->eid) ? $params->eid : 0;
		$this->type = 'ADT';

		// MSH
		$msh = $this->setMSH(true);
		$msh->setValue('9.1', 'ADT');
		$msh->setValue('9.2', $event);
		$msh->setValue('9.3', 'ADT_A01');

		$msh->setValue('21.1', 'PH_SS-NoAck');
		$msh->setValue('21.2', 'SS Sender');
		$msh->setValue('21.3', '2.16.840.1.114222.4.10.3');
		$msh->setValue('21.4', 'ISO');

		$this->setEVN();

		// PID
		$this->setPID();
		$this->setPV1();

		// Continue with message
		if($event == 'A04'){

			// Specialty
			$obx = $this->hl7->addSegment('OBX');
			$obx->setValue('1', 1);
			$obx->setValue('2', 'CWE');
			$obx->setValue('3.1', 'SS003');
			$obx->setValue('3.3', 'PHINQUESTION');

			$sth = $this->conn->prepare('SELECT * FROM `specialties` WHERE id = ?');
			$sth->execute([$this->encounter->specialty_id]);
			$specialty = $sth->fetch(PDO::FETCH_ASSOC);
			if($specialty !== false){
				$obx->setValue('5.1', $specialty['taxonomy']);
				$obx->setValue('5.2', $specialty['title']);
				$obx->setValue('5.3', 'NUCC');
				$obx->setValue('11', 'F');
			}
			unset($obx);

			// Age - Reportedx

			$obx = $this->hl7->addSegment('OBX');
			$obx->setValue('1', 2);
			$obx->setValue('2', 'NM');
			$obx->setValue('3.1', '21612-7');
			$obx->setValue('3.3', 'LN');
			$obx->setValue('5', (string) $this->patient->age['DMY']['years']);
			$obx->setValue('6.1', 'a');
			$obx->setValue('6.3', 'UCUM');
			$obx->setValue('11', 'F');
			unset($obx);

			$obx = $this->hl7->addSegment('OBX');
			$obx->setValue('1', 3);
			$obx->setValue('2', 'CWE');
			$obx->setValue('3.1', '8661-1');
			$obx->setValue('3.3', 'LN');
			$obx->setValue('5.9', $this->encounter->brief_description);
			$obx->setValue('11', 'F');

			$dg1 = $this->hl7->addSegment('DG1');
			$dg1->setValue('1', 1);
			$dg1->setValue('3.1', '4871');
			$dg1->setValue('3.2', 'Influenza with other respiratory manifestations');
			$dg1->setValue('3.3', 'I9CDX');
			$dg1->setValue('6', 'W');
		}

		$msgRecord = $this->saveMsg();

		if($this->to['route'] == 'file'){
			$response = $this->Save();
		} else {
			$response = $this->Send();
		}

		if($response['success']){
			$msgRecord->status = 3;
			$this->m->save($msgRecord);
		}else{
			$msgRecord->status = preg_match('/^socket/', $response['message']) ? 2 : 4; // if socket error put back in queue
			$msgRecord->error = $response['message'];
			$this->m->save($msgRecord);
		}

	}

	/**
	 * @param $to
	 * @param $from
	 * @param stdClass $service
	 * @param $orderControl
	 * @throws Exception
	 */
	function sendServiceORM($to, $from, $service, $orderControl){
        try
        {
            $service = (object) $service;
            $this->to = $to;
            $this->from = $from;
            $this->patient = $service->pid;
            $this->encounter = $service->eid;
            $this->type = 'ORM';

            // MSH
            $msh = $this->setMSH();
            $msh->setValue('9.1', 'ORM');
            $msh->setValue('9.2', 'O01');
    //		$msh->setValue('9.3', 'ORM_O01');
            // PID
            $this->setPID();
            // PV1
            $this->setPV1();
            // ORC
            $this->setORC($service, $orderControl);
            // OBR
            $this->setOBR($service, 1);


            if(is_array($service->dx_pointers)){
                $dxIndex = 1;
                foreach($service->dx_pointers as $dx){
                    $this->setDG1($dx, $dxIndex);
                    $dxIndex++;
                }
            }

            $msgRecord = $this->saveMsg();

            if($this->to['route'] == 'file'){
                $response = $this->Save();
            } else {
                $response = $this->Send();
            }

            $msgRecord->response = $response['message'];

            if($response['success']){
                $msgRecord->status = 3;
                $this->m->save($msgRecord);
            }else{
                $msgRecord->status = preg_match('/^socket/', $response['message']) ? 2 : 4; // if socket error put back in queue
                $msgRecord->error = $response['message'];
                $this->m->save($msgRecord);
            }
            return ['success' => true];
        }
        catch(Exception $Error)
        {
            return ['success' => false];
        }

	}

	function sendVXU($params) {
        try
        {
            // set these globally to be used by MSH and PID
            $this->to = $params->to;
            $this->from = $params->from;
            $this->patient = $params->pid;
            $this->encounter = isset($params->eid) ? $params->eid : 0;
            $this->type = 'VXU';

            // MSH
            $msh = $this->setMSH();
            $msh->setValue('9.1', 'VXU');
            $msh->setValue('9.2', 'V04');
            $msh->setValue('9.3', 'VXU_V04');
            // PID
            $this->setPID();
            // PV1
            $this->setPV1();

            // Variable Objects to pass filter to MatchaCup
            $filters = new stdClass();
            $filters->filter[0] = new stdClass();
            $filters->filter[1] = new stdClass();

            // Load the List option model, to do lookups in the Value Code Sets
            $ListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');

            // PD1 - 3.4.10 PD1 - Patient Additional Demographic Segment
            // If the Publicity is set, on the patient contacts compile this HL7 Message line
            $filters = new stdClass();
            $filters->filter[0] = new stdClass();
            $filters->filter[1] = new stdClass();
            $filters->filter[0]->property = 'pid';
            $filters->filter[0]->value = $this->patient->pid;
            $filters->filter[1]->property = 'relationship';
            $filters->filter[1]->value = 'SEL';
            $ContactRecord = $this->PatientContacts->load($filters)->one();
            if($this->notEmpty($ContactRecord)) {
                $PD1 = $this->hl7->addSegment('PD1');
                $filters->filter[0]->property = 'list_id';
                $filters->filter[0]->value = 132;
                $filters->filter[1]->property = 'code';
                $filters->filter[1]->value = $ContactRecord->publicity;
                $Record = $ListOptions->load($filters)->one();
                $PD1->setValue('11.1', $Record['option_value']);
                $PD1->setValue('11.2', $Record['option_name']);
                $PD1->setValue('11.3', $Record['code_type']);
                $PD1->setValue('16', 'A');
                $PD1->setValue('17', $this->date($this->patient->create_date, false));
                $PD1->setValue('18', $this->date($this->patient->create_date, false));
            }

            // NK1 - 3.4.5 NK1 - Next of Kin / Associated Parties Segment
            $filters->filter[0]->property = 'pid';
            $filters->filter[0]->value = $params->pid;
            $filters->filter[1] = new stdClass();
            $Records = $this->PatientContacts->load($filters)->all();
            $transactionID = 0;
            foreach($Records as $Record)
            {
                $transactionID++;
                $PD1 = $this->hl7->addSegment('NK1');
                $PD1->setValue('1', $transactionID);
                $PD1->setValue('2.1', $Record['middle_name'] .' '.$Record['last_name']);
                $PD1->setValue('2.2', $Record['first_name']);
                $PD1->setValue('2.7', 'L');
                $PD1->setValue('3.1', $Record['relationship']);
                $filters->filter[0]->property = 'list_id';
                $filters->filter[0]->value = 134;
                $filters->filter[1]->property = 'option_value';
                $filters->filter[1]->value = $Record['relationship'];
                $List = $ListOptions->load($filters)->one();
                $PD1->setValue('3.2', $List['option_name']);
                $PD1->setValue('3.3', $List['code_type']);
                $PD1->setValue('4.1', $Record['street_mailing_address']);
                $PD1->setValue('4.3', $Record['city']);
                $PD1->setValue('4.4', $Record['state']);
                $PD1->setValue('4.5', $Record['zip']);
                $PD1->setValue('4.6', $Record['country']);
                $PD1->setValue('4.7', 'L');
                $PD1->setValue('5.2', 'PRN');
                $PD1->setValue('5.3', 'PH');
                $PD1->setValue('5.6', $Record['phone_area_code']);
                $PD1->setValue('5.7', $Record['phone_local_number']);
            }

            $this->i = MatchaModel::setSenchaModel('App.model.patient.PatientImmunization');
            include_once(ROOT . '/dataProvider/Immunizations.php');
            include_once(ROOT . '/dataProvider/Services.php');
            $immunization = new Immunizations();
            $EncounterServices = new Services();

            // Immunizations loop
            foreach($params->immunizations AS $i){

                $immu = $this->i->load($i)->one();

                // ORC - 4.5.1 ORC - Common Order Segment
                $ORC = $this->hl7->addSegment('ORC');
                $ORC->setValue('1', 'RE'); //HL70119
                $ORC->setValue('3.1', 'GAIA10001');
                $ORC->setValue('3.2', $immu['id']);

                // RXA - 4.14.7 RXA - Pharmacy/Treatment Administration Segment
                $RXA = $this->hl7->addSegment('RXA');
                $RXA->setValue('3.1', $this->date($immu['administered_date'])); //Date/Time Start of Administration
                $RXA->setValue('4.1', $this->date($immu['administered_date'])); //Date/Time End of Administration
                //Administered Code
                $RXA->setValue('5.1', $immu['code']); //Identifier
                $RXA->setValue('5.2', $immu['vaccine_name']); //Text
                $RXA->setValue('5.3', $immu['code_type']); //Name of Coding System
                if($this->isPresent($immu['administer_amount'])){
                    $RXA->setValue('6', $immu['administer_amount']); //Administered Amount
                    $RXA->setValue('7.1', $immu['administer_units']); //Identifier
                    $RXA->setValue('7.2', $immu['administer_units']); // Text
                    $RXA->setValue('7.3', 'UCUM'); //Name of Coding System HL70396
                } else {
                    $RXA->setValue('6', '999'); //Administered Amount
                }
                $RXA->setValue('15', $immu['lot_number']); //Substance LotNumbers
                // get immunization manufacturer info
                $mvx = $immunization->getMvxByCode($immu['manufacturer']);
                $mText = isset($mvx['manufacturer']) ? $mvx['manufacturer'] : '';
                //Substance ManufacturerName
                $RXA->setValue('17.1', $immu['manufacturer']); //Identifier
                $RXA->setValue('17.2', $mText); //Text
                $RXA->setValue('17.3', 'MVX'); //Name of Coding System HL70396
                $RXA->setValue('21', 'A'); //Action Code

                // RXR - 4.14.2 RXR - Pharmacy/Treatment Route Segment
                $RXR = $this->hl7->addSegment('RXR');
                // Route
                $filters->filter[0]->property = 'list_id';
                $filters->filter[0]->value = 6;
                $filters->filter[1]->property = 'option_value';
                $filters->filter[1]->value = $immu['route'];
                $Record = $ListOptions->load($filters)->one();
                $RXR->setValue('1.1', $Record['option_value']);
                $RXR->setValue('1.2', $Record['option_name']);
                $RXR->setValue('1.3', $Record['code_type']);
                // Administration Site
                $filters->filter[0]->property = 'list_id';
                $filters->filter[0]->value = 119;
                $filters->filter[1]->property = 'code';
                $filters->filter[1]->value = $immu['administration_site'];
                $Record = $ListOptions->load($filters)->one();
                $RXR->setValue('2.1', $Record['option_value']);
                $RXR->setValue('2.2', $Record['option_name']);
                $RXR->setValue('2.3', $Record['code_type']);

                // OBX - 7.4.2 OBX - Observation/Result Segment
                $filters->filter[0]->property = 'eid';
                $filters->filter[0]->value = $immu['eid'];
                $filters->filter[1]->property = 'pid';
                $filters->filter[1]->value = $immu['pid'];
                $Records = $EncounterServices->getEncounterServicesByEIDandPID($filters);
                $obxCount = 1;
                foreach($Records as $Record) {
                    $OBX = $this->hl7->addSegment('OBX');
                    $OBX->setValue('1', $obxCount);
                    $OBX->setValue('2', 'CE');
                    $OBX->setValue('3.1', '64994-7');
                    $OBX->setValue('3.2', 'Vaccine funding program eligibility category');
                    $OBX->setValue('3.3', 'LN');
                    $OBX->setValue('4', $Record['eid']);
                    $OBX->setValue('5.1', $Record['financial_class']);
                    $OBX->setValue('5.2', $Record['financial_name']);
                    $OBX->setValue('5.3', $Record['code_type']);
                    $OBX->setValue('11', 'F');
                    $OBX->setValue('17.1', 'VXC40');
                    $OBX->setValue('17.2', 'Eligibility captured at the immunization level');
                    $OBX->setValue('17.3', 'CDCPHINVS');
                    $obxCount++;
                }
                $OBX = $this->hl7->addSegment('OBX');
                $OBX->setValue('1', $obxCount);
                $OBX->setValue('2', 'CE');
                $OBX->setValue('3.1', '30956-7');
                $OBX->setValue('3.2', 'vaccine type');
                $OBX->setValue('3.3', 'LN');
                $OBX->setValue('4', $immu['id']);
                $OBX->setValue('5.1', $immu['code']);
                $OBX->setValue('5.2', $immu['vaccine_name']);
                $OBX->setValue('5.3', $immu['code_type']);
                $OBX->setValue('11', 'F');
                $obxCount++;
                $OBX = $this->hl7->addSegment('OBX');
                $OBX->setValue('1', $obxCount);
                $OBX->setValue('2', 'TS');
                $OBX->setValue('3.1', '29768-9');
                $OBX->setValue('3.2', 'Date vaccine information statement published');
                $OBX->setValue('3.3', 'LN');
                $OBX->setValue('4', $immu['id']);
                $OBX->setValue('5', $this->date($immu['education_doc_published'], false));
                $OBX->setValue('11', 'F');
                $obxCount++;
                $OBX = $this->hl7->addSegment('OBX');
                $OBX->setValue('1', $obxCount);
                $OBX->setValue('2', 'TS');
                $OBX->setValue('3.1', '29769-7');
                $OBX->setValue('3.2', 'Date vaccine information statement presented');
                $OBX->setValue('3.3', 'LN');
                $OBX->setValue('4', $immu['id']);
                $OBX->setValue('5', $this->date($immu['education_date'], false));
                $OBX->setValue('11', 'F');
            }

            $msgRecord = $this->saveMsg();

            // If the delivery is set and for download, quit the rest of the process
            if(isset($params->delivery) && $params->delivery = 'download') return;

            if($this->to['route'] == 'file'){
                $response = $this->Save();
            } else {
                $response = $this->Send();
            }

            $msgRecord->response = $response['message'];

            if($response['success']){
                $msgRecord->status = 3;
                $this->m->save($msgRecord);
            }else{
                $msgRecord->status = preg_match('/^socket/', $response['message']) ? 2 : 4; // if socket error put back in queue
                $msgRecord->error = $response['message'];
                $this->m->save($msgRecord);
            }
            return ['success' => true];
        }
        catch(Exception $Error)
        {
            return ['success' => false];
        }
	}

	private function setMSH($includeNPI = false) {
		$this->setEncounter();

		// set these globally
		$this->to = $this->c->load($this->to)->one();
		$this->from = $this->f->load($this->from)->one();
		//
		$msh = $this->hl7->addSegment('MSH');
		$msh->setValue('3.1', 'GaiaEHR'); // Sending Application
		$msh->setValue('4.1', addslashes(substr($this->from['name'], 0, 20))); // Sending Facility
		if($includeNPI){
			$msh->setValue('4.2', $this->from['npi']);
			$msh->setValue('4.3', 'NPI');
		}

		$msh->setValue('5.1', $this->to['application_name']); // Receiving Application
		$msh->setValue('6.1', $this->to['facility']); // Receiving Facility
		$msh->setValue('7.1', date('YmdHis')); // Message Date Time
		$msh->setValue('11.1', 'P'); // D = Debugging P = Production T = Training
		$msh->setValue('12.1', '2.5.1'); // HL7 version
		return $msh;
	}

	private function setEVN(){
		$evn = $this->hl7->addSegment('EVN');
		$evn->setValue('2.1', date('YmdHis'));
		$evn->setValue('7.1', str_replace(' ', '', substr($this->from['name'], 0, 20)));
		$evn->setValue('7.2', $this->from['npi']);
		$evn->setValue('7.3', 'NPI');
	}

	/**
	 * @return Segments
	 * @throws Exception
	 */
	private function setPID() {

		$this->patient = $this->p->load($this->patient)->one();

		if($this->patient == false){
			throw new \Exception('Error: Patient not found during setPID, Record # ' . $this->patient);
		}

		$this->patient = (object) $this->patient;

		$this->patient->age = Patient::getPatientAgeByDOB($this->patient->DOB);

		$pid = $this->hl7->addSegment('PID');

		$pid->setValue('1', 1);

//		if($this->notEmpty($this->patient->pubpid)){
//			$pid->setValue('2.3', $this->patient->pubpid);
//		}else if($this->notEmpty($this->patient->pid)){
//			$pid->setValue('2.3', $this->patient->pid);
//		}
//		if($this->notEmpty($this->patient->pubpid)){
//			$pid->setValue('3.1', $this->patient->pubpid);
		if($this->notEmpty($this->patient->pid)){
			$pid->setValue('3.1', $this->patient->pid);
		}
		$pid->setValue('3.4', 'GaiaEHR');
		$pid->setValue('3.5', 'MR'); // IDNumber Type (HL70203) MR = Medical Record

		if($this->patient->age['DMY']['years'] == 0){
//			$pid->setValue('5.1.1', '', 0);
			$pid->setValue('5.7', 'S', 1);
		}else{
			if($this->notEmpty($this->patient->lname)){
				$pid->setValue('5.1.1', $this->patient->lname);
			}
			if($this->notEmpty($this->patient->fname)){
				$pid->setValue('5.2', $this->patient->fname);
			}
			if($this->notEmpty($this->patient->mname)){
				$pid->setValue('5.3', $this->patient->mname);
			}
			$pid->setValue('5.7', 'L');
		}

        // This has to be taken on Patient Contacts
        $filters = new stdClass();
        $filters->filter[0] = new stdClass();
        $filters->filter[1] = new stdClass();
        $filters->filter[0]->property = 'pid';
        $filters->filter[0]->value = $this->patient->pid;
        $filters->filter[1]->property = 'relationship';
        $filters->filter[1]->value = 'MTH';
        $ContactRecord = $this->PatientContacts->load($filters)->one();
		if($this->notEmpty($ContactRecord)){
            $pid->setValue(
                '6.1',
                $ContactRecord['first_name'].' '.
                $ContactRecord['middle_name'].' '.
                $ContactRecord['last_name']
            );
			$pid->setValue(
                '6.2',
                $ContactRecord['first_name'].' '.
                $ContactRecord['middle_name'].' '.
                $ContactRecord['last_name']
            );
		}

		if($this->notEmpty($this->patient->DOB)){
			$pid->setValue('7.1', $this->date($this->patient->DOB));
		}
		if($this->notEmpty($this->patient->sex)){
			$pid->setValue('8', $this->patient->sex);
		}
		if($this->notEmpty($this->patient->alias)){
			$pid->setValue('9.2', $this->patient->alias);
		}
		if($this->notEmpty($this->patient->race)){
			$pid->setValue('10.1', $this->patient->race);
			$pid->setValue('10.2', $this->hl7->race($this->patient->race)); //Race Text
			$pid->setValue('10.3', 'CDCREC'); // Race Name of Coding System
		}

        // Patient Address taken Patient Contact (SELF)
        $filters = new stdClass();
        $filters->filter[0] = new stdClass();
        $filters->filter[1] = new stdClass();
        $filters->filter[0]->property = 'pid';
        $filters->filter[0]->value = $this->patient->pid;
        $filters->filter[1]->property = 'relationship';
        $filters->filter[1]->value = 'SEL';
        $ContactRecord = $this->PatientContacts->load($filters)->one();
        if($this->notEmpty($ContactRecord['street_mailing_address'])) {
            if ($this->notEmpty($ContactRecord['street_mailing_address']))
                $pid->setValue('11.1.1', $ContactRecord['street_mailing_address']);

            if ($this->notEmpty($ContactRecord['city']))
                $pid->setValue('11.3', $ContactRecord['city']);

            if ($this->notEmpty($ContactRecord['state'])) {
                $pid->setValue('11.4', $ContactRecord['state']);
            }
            if ($this->notEmpty($ContactRecord['zip'])) {
                $pid->setValue('11.5', $ContactRecord['zip']);
            }
            if ($this->notEmpty($ContactRecord['country'])) {
                $pid->setValue('11.6', $ContactRecord['country']);
            }
            if ($this->notEmpty($ContactRecord['street_mailing_address'])) {
                $pid->setValue('11.7', 'L'); // Address Type L = Legal Address
            }
            $pid->setValue('11.9', '25025');
        }
        // Patient Phone Number taken from Patient Contact (SELF)
		if($this->notEmpty($ContactRecord['phone_use_code']) &&
            $this->notEmpty($ContactRecord['phone_area_code']) &&
            $this->notEmpty($ContactRecord['phone_local_number'])){

			$phone = $this->phone(
                $ContactRecord['phone_use_code'].
                $ContactRecord['phone_area_code'].
                $ContactRecord['phone_local_number']);

			$pid->setValue('13.2', 'PRN'); // PhoneNumber‐Home
			$pid->setValue('13.6', $ContactRecord['zip']); // Area/City Code
			$pid->setValue('13.7', $phone); // LocalNumber
		}
		if($this->notEmpty($this->patient->language)){
			$pid->setValue('15.1', $this->patient->language);
		}
		if($this->notEmpty($this->patient->marital_status)){
			$pid->setValue('16.1', $this->patient->marital_status); // EthnicGroup Identifier
			$pid->setValue('16.2', $this->hl7->marital($this->patient->marital_status)); // EthnicGroup Text
			$pid->setValue('16.3', 'CDCREC'); // Name of Coding System
		}
		if($this->notEmpty($this->patient->pubaccount)){
			$pid->setValue('18.1', $this->patient->pubaccount);
		}
		if($this->notEmpty($this->patient->SS)){
			$pid->setValue('19', $this->patient->SS);
		}

        // Patient Drivers License Information
		if($this->notEmpty($this->patient->drivers_license)){
			$pid->setValue('20.1', $this->patient->drivers_license);
            if($this->notEmpty($this->patient->drivers_license_state)){
                $pid->setValue('20.2', $this->patient->drivers_license_state);
            }
            if($this->notEmpty($this->patient->drivers_license_exp)){
                $pid->setValue('20.3', $this->date($this->patient->drivers_license_exp));
            }
		}

		if($this->notEmpty($this->patient->ethnicity)){
			if($this->patient->ethnicity == 'H'){
				$pid->setValue('22.1', '2135-2');
				$pid->setValue('22.3', 'CDCREC');
			}elseif($this->patient->ethnicity == 'N'){
				$pid->setValue('22.1', '2186-5');
				$pid->setValue('22.3', 'CDCREC');
			}else{
				$pid->setValue('22.1', '$this->patient->ethnicity');
			}
		}
		if($this->notEmpty($this->patient->birth_place)){
			$pid->setValue('23', $this->patient->birth_place);
		}
		if($this->notEmpty($this->patient->birth_multiple)){
			$pid->setValue('24', $this->patient->birth_multiple);
		}
		if($this->notEmpty($this->patient->birth_order)){
			$pid->setValue('25', $this->patient->birth_order);
		}
		if($this->notEmpty($this->patient->citizenship)){
			$pid->setValue('26.1', $this->patient->citizenship);
		}
		if($this->notEmpty($this->patient->is_veteran)){
			$pid->setValue('27.1', $this->patient->is_veteran);
		}
        if($this->notEmpty($this->patient->death_date) && $this->notEmpty($this->patient->deceased))
        {
            $pid->setValue('29.1', $this->date($this->patient->death_date));
            $pid->setValue('30', 'Y');
        }
		if($this->notEmpty($this->patient->update_date)){
			$pid->setValue('33.1', $this->date($this->patient->update_date));
		}

		return $pid;
	}

	private function setPV1(){

		if($this->encounter === false) return;

		$pv1 = $this->hl7->addSegment('PV1');
		$pv1->setValue('1', 1);
		/**
		 * 0004 B Obstetrics
		 * 0004 C Commercial Account
		 * 0004 E Emergency
		 * 0004 I Inpatient
		 * 0004 N Not Applicable
		 * 0004 O Outpatient
		 * 0004 P Preadmit
		 * 0004 R Recurring patient
		 * 0004 U Unknown
		 */
		if($this->notEmpty($this->encounter->patient_class)){
			$pv1->setValue('2', $this->encounter->patient_class);
		}else{
			$pv1->setValue('2', 'U');
		}
		/**
		 * 0007 A Accident
		 * 0007 C Elective
		 * 0007 E Emergency
		 * 0007 L Labor and Delivery
		 * 0007 N Newborn (Birth in healthcare facility)
		 * 0007 R Routine
		 */
		$repIndex = 0;
		if($this->notEmpty($this->encounter->provider_uid)){
			$provider = $this->u->load($this->encounter->provider_uid)->one();
			if($provider !== false){
				$provider = (object) $provider;
				$pv1->setValue('7.1', $provider->npi, $repIndex); // NPI
				$pv1->setValue('7.2.1', $provider->lname, $repIndex); // Last Name
				$pv1->setValue('7.3', $provider->fname, $repIndex); // First Name
				$pv1->setValue('7.4', $provider->mname, $repIndex); // Middle Name
				//$pv1->setValue('7.5', $provider->suffix, $repIndex); // Suffix Sr. Jr
				$pv1->setValue('7.6', $provider->title, $repIndex); // Prefix Title
				$repIndex++;
			}
		}

		if($this->notEmpty($this->encounter->supervisor_uid)){
			$supervisor = $this->u->load($this->encounter->supervisor_uid)->one();
			if($supervisor !== false){
				$provider = (object) $supervisor;
				$pv1->setValue('7.1', $provider->npi, $repIndex); // NPI
				$pv1->setValue('7.2.1', $provider->lname, $repIndex); // Last Name
				$pv1->setValue('7.3', $provider->fname, $repIndex); // First Name
				$pv1->setValue('7.4', $provider->mname, $repIndex); // Middle Name
				$pv1->setValue('7.6', $provider->title, $repIndex); // Prefix Title
				$repIndex++;
			}
		}

		if($this->notEmpty($this->encounter->referring_physician)){
			$referring = $this->ReferringProvider->load($this->encounter->referring_physician)->one();
			if($referring !== false){
				$referring = (object) $referring;
				$pv1->setValue('8.1', $referring->npi); // NPI
				$pv1->setValue('8.2.1', $referring->lname); // Last Name
				$pv1->setValue('8.3', $referring->fname); // First Name
				$pv1->setValue('8.4', $referring->mname); // Middle Name
				$pv1->setValue('8.6', $referring->title); // Prefix Title
			}
		}

		if($this->notEmpty($this->encounter->eid)){
			$pv1->setValue('19.1', $this->encounter->eid);
			$pv1->setValue('19.5', 'VN');
		}

		if($this->notEmpty($this->encounter->service_date)){
			$pv1->setValue('44.1', $this->date($this->encounter->service_date)); // Prefix Title
		}
	}

	private function setORC($order, $orderControl){
		if($order === false) return;

		$orc = $this->hl7->addSegment('ORC');
		/**
		 * $orderControl shall be one for these values
		 * ----------------------------------------------
		 * 0119 AF Order/service refill request approval
		 * 0119 CA Cancel order/service request
		 * 0119 CH Child order/service
		 * 0119 CN Combined result
		 * 0119 CR Canceled as requested
		 * 0119 DC Discontinue order/service request
		 * 0119 DE Data errors
		 * 0119 DF Order/service refill request denied
		 * 0119 DR Discontinued as requested
		 * 0119 FU Order/service refilled, unsolicited
		 * 0119 HD Hold order request
		 * 0119 HR On hold as requested
		 * 0119 LI Link order/service to patient care problem or goal
		 * 0119 NA Number assigned
		 * 0119 NW New order/service
		 * 0119 OC Order/service canceled
		 * 0119 OD Order/service discontinued
		 * 0119 OE Order/service released
		 * 0119 OF Order/service refilled as requested
		 * 0119 OH Order/service held
		 * 0119 OK Order/service accepted & OK
		 * 0119 OP Notification of order for outside dispense
		 * 0119 OR Released as requested
		 * 0119 PA Parent order/service
		 * 0119 PR Previous Results with new order/service
		 * 0119 PY Notification of replacement order for outside dispense
		 * 0119 RE Observations/Performed Service to follow
		 * 0119 RF Refill order/service request
		 * 0119 RL Release previous hold
		 * 0119 RO Replacement order
		 * 0119 RP Order/service replace request
		 * 0119 RQ Replaced as requested
		 * 0119 RR Request received
		 * 0119 RU Replaced unsolicited
		 * 0119 SC Status changed
		 * 0119 SN Send order/service number
		 * 0119 SR Response to send order/service status request
		 * 0119 SS Send order/service status request
		 * 0119 UA Unable to accept order/service
		 * 0119 UC Unable to cancel
		 * 0119 UD Unable to discontinue
		 * 0119 UF Unable to refill
		 * 0119 UH Unable to put on hold
		 * 0119 UM Unable to replace
		 * 0119 UN Unlink order/service from patient care problem or goal
		 * 0119 UR Unable to release
		 * 0119 UX Unable to change
		 * 0119 XO Change order/service request
		 * 0119 XR Changed as requested
		 * 0119 XX Order/service changed, unsol.
		 */

		$orc->setValue('1', $orderControl);
		$orc->setValue('2.1', $order->id);
		$orc->setValue('9.1', $this->date($this->encounter->service_date));

		$repIndex = 0;
		if($this->notEmpty($this->encounter->provider_uid)){
			$provider = $this->u->load($this->encounter->provider_uid)->one();
			if($provider !== false){
				$provider = (object) $provider;
				$orc->setValue('12.1', $provider->npi, $repIndex); // NPI
				$orc->setValue('12.2.1', $provider->lname, $repIndex); // Last Name
				$orc->setValue('12.3', $provider->fname, $repIndex); // First Name
				$orc->setValue('12.4', $provider->mname, $repIndex); // Middle Name
				//$orc->setValue('7.5', $provider->suffix, $repIndex); // Suffix Sr. Jr
				$orc->setValue('12.6', $provider->title, $repIndex); // Prefix Title
				$repIndex++;
			}
		}

		if($this->notEmpty($this->encounter->supervisor_uid)){
			$supervisor = $this->u->load($this->encounter->supervisor_uid)->one();
			if($supervisor !== false){
				$provider = (object) $supervisor;
				$orc->setValue('12.1', $provider->npi, $repIndex); // NPI
				$orc->setValue('12.2.1', $provider->lname, $repIndex); // Last Name
				$orc->setValue('12.3', $provider->fname, $repIndex); // First Name
				$orc->setValue('12.4', $provider->mname, $repIndex); // Middle Name
				//$orc->setValue('7.5', $provider->suffix, $repIndex); // Suffix Sr. Jr
				$orc->setValue('12.6', $provider->title, $repIndex); // Prefix Title
				$repIndex++;
			}
		}

	}

	/**
	 * @param stdClass $observation
	 * @param int $sequence
	 * @throws Exception
	 */
	private function setOBR($observation, $sequence = 1){

		$obr = $this->hl7->addSegment('OBR');
		$obr->setValue(1, $sequence);

		if($this->notEmpty($observation->id)){
			$obr->setValue('2', $observation->id);
		}
		if($this->notEmpty($observation->code)){
			$obr->setValue('4.1', $observation->code);
			$obr->setValue('4.2', $observation->code_text);
			$obr->setValue('4.3', $observation->code_type);
		}

		if($this->notEmpty($this->encounter->service_date)){
			$obr->setValue('7.1', $this->date($this->encounter->service_date));
		}

		$repIndex = 0;
		if($this->notEmpty($this->encounter->provider_uid)){
			$provider = $this->u->load($this->encounter->provider_uid)->one();
			if($provider !== false){
				$provider = (object) $provider;
				$obr->setValue('16.1', $provider->npi, $repIndex); // NPI
				$obr->setValue('16.2.1', $provider->lname, $repIndex); // Last Name
				$obr->setValue('16.3', $provider->fname, $repIndex); // First Name
				$obr->setValue('16.4', $provider->mname, $repIndex); // Middle Name
				//$orc->setValue('16.5', $provider->suffix, $repIndex); // Suffix Sr. Jr
				$obr->setValue('16.6', $provider->title, $repIndex); // Prefix Title
				$repIndex++;
			}
		}

		if($this->notEmpty($this->encounter->supervisor_uid)){
			$supervisor = $this->u->load($this->encounter->supervisor_uid)->one();
			if($supervisor !== false){
				$provider = (object) $supervisor;
				$obr->setValue('16.1', $provider->npi, $repIndex); // NPI
				$obr->setValue('16.2.1', $provider->lname, $repIndex); // Last Name
				$obr->setValue('16.3', $provider->fname, $repIndex); // First Name
				$obr->setValue('16.4', $provider->mname, $repIndex); // Middle Name
				//$orc->setValue('16.5', $provider->suffix, $repIndex); // Suffix Sr. Jr
				$obr->setValue('16.6', $provider->title, $repIndex); // Prefix Title
			}
		}
		if($this->notEmpty($observation->units)){
			$obr->setValue('27.1', $observation->code);
		}

		if($this->notEmpty($observation->code)){
			$obr->setValue('44.1', $observation->code);
			$obr->setValue('44.2', $observation->code_text);
			$obr->setValue('44.3', $observation->code_type);
		}

		if($this->notEmpty($observation->modifiers) && is_array($observation->modifiers)){
			$repIndex = 0;
			foreach($observation->modifiers as $modifier){
				$obr->setValue('45.1', $modifier, $repIndex);
				$repIndex++;
			}
		}
	}


	private function setDG1($diagnosis, $sequence = '1'){
		$diagnosis = explode(":", $diagnosis);
		$type = $this->encounter->close_date == '0000-00-00 00:00:00' ? 'W' :'F';

		$dg1 = $this->hl7->addSegment('DG1');

		$dg1->setValue('1', $sequence);
		$dg1->setValue('2', $diagnosis[0]);
		$dg1->setValue('3.1', $diagnosis[1]);
		$dg1->setValue('6', $type);
	}

	private function setEncounter(){
		$this->encounter = $this->e->load($this->encounter)->one();
		if($this->encounter === false) return;
		$this->encounter = (object) $this->encounter;
	}

	public function saveMsg() {
		$foo = new stdClass();
		$foo->msg_type = $this->type;
		$foo->message = $this->hl7->getMessage();
		$foo->date_processed = date('Y-m-d H:i:s');
		$foo->isOutbound = true;
		$foo->status = 1; // 0 = hold, 1 = processing, 2 = queue, 3 = processed, 4 = error
		$foo->foreign_address = $this->to['address'] . (isset($this->to['port']) ? $this->to['port'] : '');
		$foo->foreign_facility = $this->to['facility'];
		$foo->foreign_application = $this->to['application_name'];
		$foo = $this->m->save($foo);
		$this->msg = (object) $foo['data'];
		return $this->msg;
	}

	private function Save() {
		$client = new HL7Client($this->to['address']);
		return $client->Save($this->msg->message);
	}

	public function Send() {
		$client = new HL7Client($this->to['address'], $this->to['port']);
		return $client->Send($this->msg->message);
	}

	public function getMessages($params) {
		return $this->m->load($params)->all();
	}

	public function getMessage($params) {
		return $this->m->load($params)->one();
	}

	public function getMessageById($id) {
		return $this->m->load($id)->one();
	}

	public function getRecipients($params) {
		return $this->c->load($params)->all();
	}

	private function date($date, $returnTime = true) {
		//$date = str_replace([' ',':','-'], '', $date);
        $dateObject = new DateTime($date);
        if($returnTime) {
            return $dateObject->format('YmdHis');
        } else {
            return $dateObject->format('Ymd');
        }
	}

	private function phone($phone) {
		$phone = str_replace([' ','(',')','-'], '', $phone);
		return ['zip' => substr($phone, 0, 3), 'number' => substr($phone, 3, 9)];
	}

	private function notEmpty($data) {
		return isset($data) && ($data != '');
	}

	private function isPresent($var) {
		return isset($var) && $var != '';
	}
}
//print '<pre>';
//$hl7 = new HL7Messages();
//print_r($hl7->sendVXU());
