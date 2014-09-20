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
include_once(ROOT . '/lib/HL7/HL7.php');
include_once(ROOT . '/lib/HL7/HL7Client.php');

class HL7Messages {

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
	 * @var array
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
	 * @var string
	 */
	private $type;

	function __construct() {
		$this->hl7 = new HL7();
		$this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Message');
		$this->c = MatchaModel::setSenchaModel('App.model.administration.HL7Client');
		$this->f = MatchaModel::setSenchaModel('App.model.administration.Facility');
	}

	private function setMSH() {
		// set these globally
		$this->to = $this->c->load($this->to)->one();
		$this->from = $this->f->load($this->from)->one();
		//
		$msh = $this->hl7->addSegment('MSH');
		$msh->setValue('3.1', 'GaiaEHR'); // Sending Application
		$msh->setValue('4.1', addslashes($this->from['name'])); // Sending Facility
		$msh->setValue('5.1', $this->to['application_name']); // Receiving Application
		$msh->setValue('6.1', $this->to['facility']); // Receiving Facility
		$msh->setValue('11.1', 'P'); // D = Debugging P = Production T = Training
		$msh->setValue('12.1', '2.5.1'); // HL7 version
		return $msh;
	}

	private function setPID() {

		// set patient globally
		$this->p = MatchaModel::setSenchaModel('App.model.patient.Patient');
		$this->patient = $this->p->load($this->patient)->one();

		// TODO
		$pid = $this->hl7->addSegment('PID');
		$pid->setValue('3.1', $this->patient['pid']); //IDNumber
		//		$pid->setValue('3.4.1', 'MPI');                             //Namespace ID
		//		$pid->setValue('3.4.2', '2.16.840.1.113883.19.3.2.1');      //Universal ID
		//		$pid->setValue('3.4.3', 'ISO');                             //Universal ID Type (HL70301)
		$pid->setValue('3.5', 'MR'); //IDNumber Type (HL70203) MR = Medical Record

		$pid->setValue('5.1.1', $this->patient['lname']); //Family Name (Surname)
		$pid->setValue('5.2', $this->patient['fname']); //GivenName
		$pid->setValue('5.3', $this->patient['mname']); //Second and Further Given Names or Initials Thereof

		$pid->setValue('7.1', $this->date($this->patient['DOB'])); //Date of Birth
		$pid->setValue('8', $this->patient['sex']); //Administrative Sex

		if($this->isPresent($this->patient['race'])){
			$pid->setValue('10.1', $this->patient['race']); //Race Identifier
			$pid->setValue('10.2', $this->hl7->race($this->patient['race'])); //Race Text
			$pid->setValue('10.3', 'HL70005'); //Race Name of Coding System
		}

		if($this->isPresent($this->patient['address'])){
			$pid->setValue('11.1.1', $this->patient['address']); //Street or Mailing Address
			$pid->setValue('11.3', $this->patient['city']); //City
			$pid->setValue('11.4', $this->patient['state']); //State
			$pid->setValue('11.5', $this->patient['zipcode']); //Zip Code
			$pid->setValue('11.6', $this->patient['country']); //Country
			$pid->setValue('11.7', 'P'); //Address Type P = Permanent
		}

		if($this->isPresent($this->patient['home_phone'])){
			$pid->setValue('13.2', 'PRN'); //PhoneNumber‐Home
			$pid->setValue('13.6', '000'); //Area/City Code
			$pid->setValue('13.7', $this->phone($this->patient['home_phone'])); //LocalNumber
		}

		if($this->isPresent($this->patient['marital_status'])){
			$pid->setValue('16.1', $this->patient['marital_status']); //EthnicGroup Identifier
			$pid->setValue('16.2', $this->hl7->marital($this->patient['marital_status'])); //EthnicGroup Text
			$pid->setValue('16.3', 'HL70002'); //Name of Coding System
		}

		if($this->isPresent($this->patient['marital_status'])){
			$pid->setValue('22.1', $this->patient['marital_status']); //Marital Status Identifier
			$pid->setValue('22.2', $this->hl7->ethnic($this->patient['ethnicity'])); //EthnicGroup Text
			$pid->setValue('22.3', 'HL70189'); //Name of Coding System
		}
	}

	function sendVXU($params) {
		// set these globally to be used by MSH and PID
		$this->to = $params->to;
		$this->from = $params->from;
		$this->patient = $params->pid;
		$this->type = 'VXU';

		// MSH
		$msh = $this->setMSH();
		$msh->setValue('9.1', 'VXU');
		$msh->setValue('9.2', 'V04');
		$msh->setValue('9.3', 'VXU_V04');
		// PID
		$this->setPID();

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

		if($response['success']){
			$msgRecord->status = 3;
			$this->m->save($msgRecord);
		}else{
			$msgRecord->status = preg_match('/^socket/', $response['message']) ? 2 : 4; // if socket error put back in queue
			$msgRecord->error = $response['message'];
			$this->m->save($msgRecord);
		}
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
		$this->msg = $foo['data'];
		return (object) $foo['data'];
	}

	private function Save() {
		$client = new HL7Client($this->to['address']);
		return $client->Save($this->msg['message']);
	}

	public function Send() {
		$client = new HL7Client($this->to['address'], $this->to['port']);
		return $client->Send($this->msg['message']);
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
		return str_replace(array(' ',':','-'), '', $date);
	}

	private function phone($phone) {
		return str_replace(array(' ','(',')','-'), '', $phone);
	}

	private function isPresent($var) {
		return isset($var) && $var != '';
	}
}

//print '<pre>';
//$hl7 = new HL7Messages();
//print_r($hl7->sendVXU());