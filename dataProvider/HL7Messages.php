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
	private $r;
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
		$this->p = MatchaModel::setSenchaModel('App.model.patient.Patient');
		$this->e = MatchaModel::setSenchaModel('App.model.patient.Encounter');
		$this->u = MatchaModel::setSenchaModel('App.model.administration.User');
		$this->r = MatchaModel::setSenchaModel('App.model.administration.ReferringProvider');
		$this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Message');
		$this->c = MatchaModel::setSenchaModel('App.model.administration.HL7Client');
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
		$msh = $this->setMSH();
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

		// continue with message
		if($event == 'A04'){

			// specialty
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

	}

	function sendVXU($params) {
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
//		$msh->setValue('9.3', 'VXU_V04');
		// PID
		$this->setPID();
		// PV1
		$this->setPV1();

		$this->i = MatchaModel::setSenchaModel('App.model.patient.PatientImmunization');
		include_once(ROOT . '/dataProvider/Immunizations.php');
		$immunization = new Immunizations();

		// immunizations loop
		foreach($params->immunizations AS $i){

			$immu = $this->i->load($i)->one();

			// ROC
			$roc = $this->hl7->addSegment('ORC');
			$roc->setValue('1', 'RE'); //HL70119
			// RXA
			$rxa = $this->hl7->addSegment('RXA');
			$rxa->setValue('3.1', $this->date($immu['administered_date'])); //Date/Time Start of Administration
			$rxa->setValue('4.1', $this->date($immu['administered_date'])); //Date/Time End of Administration
			//Administered Code
			$rxa->setValue('5.1', $immu['code']); //Identifier
			$rxa->setValue('5.2', $immu['vaccine_name']); //Text
			$rxa->setValue('5.3', $immu['code_type']); //Name of Coding System

			if($this->isPresent($immu['administer_amount'])){
				$rxa->setValue('6', $immu['administer_amount']); //Administered Amount
				$rxa->setValue('7.1', $immu['administer_amount']); //Identifier
				$rxa->setValue('7.2', 'millimeters'); //Text
				$rxa->setValue('7.3', 'ISO+'); //Name of Coding System HL70396
			} else {
				$rxa->setValue('6', '999'); //Administered Amount
			}

			$rxa->setValue('15', $immu['lot_number']); //Substance LotNumbers

			// get immunization manufacturer info
			$mvx = $immunization->getMvxByCode($immu['manufacturer']);
			$mText = isset($mvx['manufacturer']) ? $mvx['manufacturer'] : '';
			//Substance ManufacturerName
			$rxa->setValue('17.1', $immu['manufacturer']); //Identifier
			$rxa->setValue('17.2', $mText); //Text
			$rxa->setValue('17.3', 'MVX'); //Name of Coding System HL70396

			$rxa->setValue('21', 'A'); //Action Code
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
	}



	private function setMSH() {
		$this->setEncounter();

		// set these globally
		$this->to = $this->c->load($this->to)->one();
		$this->from = $this->f->load($this->from)->one();
		//
		$msh = $this->hl7->addSegment('MSH');
		$msh->setValue('3.1', 'GaiaEHR'); // Sending Application
		$msh->setValue('4.1', addslashes(substr($this->from['name'], 0, 20))); // Sending Facility
		$msh->setValue('4.2', $this->from['npi']);
		$msh->setValue('4.3', 'NPI');
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

		if($this->notEmpty($this->patient->pubpid)){
			$pid->setValue('2.3', $this->patient->pubpid);
		}
		if($this->notEmpty($this->patient->pid)){
			$pid->setValue('3.1', $this->patient->pid);
		}
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

		if($this->notEmpty($this->patient->mothers_name)){
			$pid->setValue('6.2', $this->patient->mothers_name);
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
		if($this->notEmpty($this->patient->address))
			$pid->setValue('11.1.1', $this->patient->address);

		if($this->notEmpty($this->patient->city))
			$pid->setValue('11.3', $this->patient->city);

		if($this->notEmpty($this->patient->state)){
			$pid->setValue('11.4', $this->patient->state);
		}
		if($this->notEmpty($this->patient->zipcode)){
			$pid->setValue('11.5', $this->patient->zipcode);
		}
		if($this->notEmpty($this->patient->country)){
			$pid->setValue('11.6', $this->patient->country);
		}
		if($this->notEmpty($this->patient->address)){
			$pid->setValue('11.7', 'P'); // Address Type P = Permanent
		}

		$pid->setValue('11.9', '25025');

		if($this->notEmpty($this->patient->home_phone)){
			$phone = $this->phone($this->patient->home_phone);

			$pid->setValue('13.2', 'PRN'); // PhoneNumber‐Home
			$pid->setValue('13.6', $phone['zip']); // Area/City Code
			$pid->setValue('13.7',  $phone['number']); // LocalNumber
		}
//		if($this->notEmpty($this->patient->work_phone)){
//		    $phone = $this->phone($this->patient->work_phone);
//			$PID->setValue('13.2', 'PRN'); // PhoneNumber‐Home
//			$PID->setValue('13.6', $phone['zip']); // Area/City Code
//			$PID->setValue('13.7', $phone['number']); // LocalNumber
//		}
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
		if($this->notEmpty($this->patient->drivers_license)){
			$pid->setValue('20.1', $this->patient->drivers_license);
		}
		if($this->notEmpty($this->patient->drivers_license_state)){
			$pid->setValue('20.2', $this->patient->drivers_license_state);
		}
		if($this->notEmpty($this->patient->drivers_license_exp)){
			$pid->setValue('20.3', $this->date($this->patient->drivers_license_exp));
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
		if($this->notEmpty($this->patient->death_date)){
			$pid->setValue('29.1', $this->date($this->patient->death_date));
		}
//		if($this->notEmpty($this->patient->deceased)){
//			$pid->setValue('30', $this->patient->deceased);
//		}
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
//		if($this->notEmpty($this->encounter->admission_type)){
//			$pv1->setValue('1', $this->encounter->admission_type);
//		}

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
				//$pv1->setValue('7.5', $provider->suffix, $repIndex); // Suffix Sr. Jr
				$pv1->setValue('7.6', $provider->title, $repIndex); // Prefix Title
				$repIndex++;
			}
		}

		if($this->notEmpty($this->encounter->referring_physician)){
			$referring = $this->r->load($this->encounter->referring_physician)->one();
			if($referring !== false){
				$referring = (object) $referring;
				$pv1->setValue('8.1', $referring->npi); // NPI
				$pv1->setValue('8.2.1', $referring->lname); // Last Name
				$pv1->setValue('8.3', $referring->fname); // First Name
				$pv1->setValue('8.4', $referring->mname); // Middle Name
//				$pv1->setValue('8.5', $provider->suffix); // Suffix Sr. Jr
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

	private function date($date) {
		$date = str_replace([' ',':','-'], '', $date);
		return $date;
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