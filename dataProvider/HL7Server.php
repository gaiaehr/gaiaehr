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
class HL7Server {

	/**
	 * @var HL7
	 */
	protected $hl7;
	/**
	 * @var HL7
	 */
	protected $ack;
	/**
	 * @var MatchaCUP
	 */
	protected $m;
	/**
	 * @var MatchaCUP
	 */
	protected $r;
	/**
	 * @var MatchaCUP
	 */
	protected $p;
	/**
	 * @var MatchaCUP
	 */
	protected $s;
	/**
	 * @var bool
	 */
	protected $ackStatus;
	/**
	 * @var string
	 */
	protected $ackMessage;
	/**
	 * @var string
	 */
	protected $site;
	/**
	 * @var int
	 */
	protected $port;
	/**
	 * @var array|bool
	 */
	protected $recipient;

	/**
	 * @var array
	 */
	protected $server;

	/**
	 * @var string
	 */
	protected $msg;

	/**
	 * @var MatchaCUP
	 */
	protected $pOrder;
	/**
	 * @var MatchaCUP
	 */
	protected $pResult;
	/**
	 * @var MatchaCUP
	 */
	protected $pObservation;

	/**
	 * @var string
	 */
	protected $updateKey = 'pid';

	function __construct($port = '9000', $site = 'default') {
		$this->site = $site;
		if(!defined('_GaiaEXEC'))
			define('_GaiaEXEC', 1);
		require_once(str_replace('\\', '/', dirname(dirname(__FILE__))) . '/registry.php');
		include_once(ROOT . "/sites/{$this->site}/conf.php");
		include_once(ROOT . '/classes/MatchaHelper.php');
		include_once(ROOT . '/lib/HL7/HL7.php');
		include_once(ROOT . '/dataProvider/HL7ServerHandler.php');
		include_once(ROOT . '/dataProvider/PoolArea.php');
		include_once(ROOT . '/dataProvider/Merge.php');
		new MatchaHelper();

		/** HL7 Models */
		$this->s = MatchaModel::setSenchaModel('App.model.administration.HL7Server');
		$this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Message');
		$this->r = MatchaModel::setSenchaModel('App.model.administration.HL7Client');

		/** Patient Model */
		$this->p = MatchaModel::setSenchaModel('App.model.patient.Patient');

		/** Order Models */
		$this->pOrder = MatchaModel::setSenchaModel('App.model.patient.PatientsOrders');
		$this->pResult = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderResult');
		$this->pObservation = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderObservation');
		$this->server = $this->getServerByPort($port);
	}

	public function Process($msg = '', $addSocketCharacters = true) {
		//		try{
		$this->msg = $msg;

		$this->ackStatus = 'AA';
		$this->ackMessage = '';

		/**
		 * Parse the HL7 Message
		 */
		$hl7 = new HL7();
		$msg = $hl7->readMessage($this->msg);

		$application = $hl7->getSendingApplication();
		$facility = $hl7->getSendingFacility();
		$version = $hl7->getMsgVersionId();

		/**
		 * check HL7 version
		 */
		if($version != '2.5.1'){
			$this->ackStatus = 'AR';
			$this->ackMessage = 'HL7 version unsupported';
		}
		/**
		 * Check for IP address access
		 */
		$this->recipient = $this->r->load(array('application_name' => $application))->one();
		if($this->recipient === false){
			$this->ackStatus = 'AR';
			$this->ackMessage = "This application '$application' Not Authorized";
		}
		/**
		 *
		 */
		if($msg === false){
			$this->ackStatus = 'AE';
			$this->ackMessage = 'Unable to parse HL7 message, please contact Support Desk';
		}
		/**
		 *
		 */
		$msgRecord = new stdClass();
		$msgRecord->msg_type = $hl7->getMsgType();
		$msgRecord->message = $this->msg;
		$msgRecord->foreign_facility = $hl7->getSendingFacility();
		$msgRecord->foreign_application = $hl7->getSendingApplication();
		$msgRecord->foreign_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$msgRecord->isOutbound = '0';
		$msgRecord->status = '2';
		$msgRecord->date_processed = date('Y-m-d H:i:s');
		$msgRecord = $this->m->save($msgRecord);
		$msgRecord = (array)$msgRecord['data'];

		if($this->ackStatus == 'AA'){
			/**
			 *
			 */
			switch($hl7->getMsgType()) {
				case 'ORU':
					$this->ProcessORU($hl7, $msg, $msgRecord);
					break;
				case 'ADT':
					$this->ProcessADT($hl7, $msg, $msgRecord);
					break;
				default:

					break;
			}
		}

		/**
		 *
		 */
		$ack = new HL7();
		$msh = $ack->addSegment('MSH');
		$msh->setValue('3.1', 'GaiaEHR'); // Sending Application
		$msh->setValue('4.1', 'Gaia'); // Sending Facility
		$msh->setValue('9.1', 'ACK');
		$msh->setValue('11.1', 'P'); // P = Production
		$msh->setValue('12.1', '2.5.1'); // HL7 version
		$msa = $ack->addSegment('MSA');
		$msa->setValue('1', $this->ackStatus); // AA = Positive acknowledgment, AE = Application error, AR = Application reject
		$msa->setValue('2', $hl7->getMsgControlId()); // Message Control ID from MSH
		$msa->setValue('3', $this->ackMessage); // Error Message
		$ackMsg = $ack->getMessage();

		$msgRecord['response'] = $ackMsg;
		$this->m->save((object)$msgRecord);

		// unset all the variables to release memory
		unset($ack, $hl7, $msg, $msgRecord, $oData, $result);

		return $addSocketCharacters ? "\v" . $ackMsg . chr(0x1c) . chr(0x0d) : $ackMsg;

	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function getServers($params) {

		$servers = $this->s->load($params)->all();
		foreach($servers['data'] as $i => $server){
			$handler = new HL7ServerHandler();
			$status = $handler->status($server);
			$servers['data'][$i]['online'] = $status['online'];
			unset($handler);
		}

		return $servers;
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function getServer($params) {
		$server = $this->s->load($params)->one();
		if($server === false || (isset($server['data']) && $server['data'] === false))
			return $server;

		$handler = new HL7ServerHandler();
		$status = $handler->status($server['port']);
		if(isset($server['data'])){
			$server['data']['online'] = $status['online'];
		} else {
			$server['online'] = $status['online'];
		}
		return $server;
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	public function addServer($params) {
		return $this->s->save($params);
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	public function updateServer($params) {
		return $this->s->save($params);
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function deleteServer($params) {
		return $this->s->destroy($params);
	}

	/**
	 * @param $port
	 *
	 * @return mixed
	 */
	protected function getServerByPort($port) {
		$this->s->addFilter('port', $port);
		return $this->s->load()->one();
	}

	/**
	 * @param $hl7 HL7
	 * @param $msg
	 * @param $msgRecord
	 */
	protected function ProcessORU($hl7, $msg, $msgRecord) {

		foreach($msg->data['PATIENT_RESULT'] AS $patient_result){
			$patient = isset($patient_result['PATIENT']) ? $patient_result['PATIENT'] : null;

			foreach($patient_result['ORDER_OBSERVATION'] AS $order){
				$orc = $order['ORC'];
				$obr = $order['OBR'];
				/**
				 * Check for order number in GaiaEHR
				 */

				$orderId = $orc[2][1];
				$patientId = $patient['PID'][3][0][1];
				$orderRecord = $this->pOrder->load(array('id' => $orderId, 'pid' => $patientId))->one();
				/**
				 * id not found set the error and break twice to get out of all the loops
				 */
				if($orderRecord === false){
					$this->ackStatus = 'AR';
					$this->ackMessage = "Unable to find order number '$orderId' for patient ID '$patientId'";
					break 2;
				}
				$foo = new stdClass();

				$foo->pid = $patientId;
				$foo->ordered_uid = $orderRecord['uid'];
				$foo->create_date = date('Y-m-d H:i:s');

				$foo->code = $obr[4][1] != '' ? $obr[4][1] : $orderRecord['code'];
				$foo->code_text = $obr[4][2] != '' ? $obr[4][2] : $orderRecord['code_text'];
				$foo->code_type = $obr[4][3] != '' ? $obr[4][3] : $orderRecord['code_type'];

				$foo->order_id = $obr[2][1];
				$foo->lab_order_id = $obr[3][1];
				$foo->lab_name = $this->recipient['facility'];
				$foo->lab_address = $this->recipient['physical_address'];
				$foo->observation_time = $hl7->time($obr[7][1]);
				$foo->result_status = $obr[25];

				if(is_array($obr[31])){
					$fo = array();
					foreach($obr[31] AS $dx){
						$fo[] = $dx[3] . ':' . $dx[1];
					}
					$foo->reason_code = implode(',', $fo);
				} else {
					$foo->reason_code = $obr[31][3] . ':' . $obr[31][1];
				}
				// specimen segment
				if(isset($order['SPECIMEN']) && $order['SPECIMEN'] !== false){
					$spm = $order['SPECIMEN']['SPM'];
					$foo->specimen_code = $spm[4][6] == 'HL70487' ? $spm[4][4] : $spm[4][1];
					$foo->specimen_text = $spm[4][6] == 'HL70487' ? $spm[4][5] : $spm[4][2];
					$foo->specimen_code_type = $spm[4][6] == 'HL70487' ? $spm[4][6] : $spm[4][3];
					$foo->specimen_notes = $spm[4][6] == 'HL70487' ? $spm[4][6] : $spm[4][3];
					// handle multiple SPECIMEN OBX's
					//					if(isset($order['SPECIMEN']['OBX']) && $order['SPECIMEN']['OBX'] !== false){
					//						foreach($order['SPECIMEN']['OBX'] AS $obx){
					//					    	print_r($obx);
					//						}
					//					}
				}

				$foo->documentId = 'hl7|' . $msgRecord['id'];
				$rResult = (array)$this->pResult->save($foo);
				unset($foo);
				/**
				 * Handle all the observations
				 */
				foreach($order['OBSERVATION'] AS $observation){

					/**
					 * observations and notes
					 */
					$obx = $observation['OBX'];
					$note = $observation['NTE'];
					$foo = new stdClass();
					$foo->result_id = $rResult['id'];
					$foo->code = $obx[3][1];
					$foo->code_text = $obx[3][2];
					$foo->code_type = $obx[3][3];
					/**
					 * handle the dynamics of the value field
					 * based on the OBX-2 value
					 */

					if($obx[2] == 'CWE'){
						$foo->value = $obx[5][2];
					} else {
						$foo->value = $obx[5];
					}


					$foo->units = $obx[6][1];
					$foo->reference_rage = $obx[7];
					$foo->probability = $obx[9];
					$foo->abnormal_flag = $obx[8][0];
					$foo->nature_of_abnormal = $obx[10][0];
					$foo->observation_result_status = $obx[11];
					$foo->date_rage_values = $hl7->time($obx[12][1]);
					$foo->date_observation = $hl7->time($obx[14][1]);
					$foo->observer = trim($obx[16][0][2][1] . ' ' . $obx[16][0][3]);
					$foo->performing_org_name = $obx[23][1];
					$foo->performing_org_address = $obx[24][1][1] . ' ' . $obx[24][3] . ', ' . $obx[24][4] . ' ' . $obx[24][5];
					$foo->date_analysis = $hl7->time($obx[19][1]);
					$foo->notes = $note['3'];

					$this->pObservation->save($foo);
					unset($foo);
				}
				/**
				 * Change the order status to received
				 */
				$foo = new stdClass();
				$foo->id = $orderId;
				$foo->status = 'Received';
				$this->pOrder->save($foo);
				unset($foo);
			}
		}

		unset($patient, $rResult);
	}

	/**
	 * @param HL7 $hl7
	 * @param ADT $msg
	 * @param stdClass $msgRecord
	 */
	protected function ProcessADT($hl7, $msg, $msgRecord) {

		$evt = $hl7->getMsgEventType();

		if($evt == 'A01'){
			/**
			 * Admit Visit
			 */
		} elseif($evt == 'A04') {
			/**
			 * Register a Patient
			 */
			$patientData = $this->PidToPatient($msg->data['PID'], $hl7);
			$patient = $this->p->load($patientData[$this->updateKey])->one();

			if($patient === false){
				$this->ackStatus = 'AR';
				$this->ackMessage = 'Unable to find patient ' . $patientData[$this->updateKey];
			}
			$patient = array_merge($patient, $patientData);

			$patient = $this->p->save((object)$patient);
			$this->InsuranceGroupHandler($msg->data['INSURANCE'], $hl7, $patient);

			return;
		} elseif($evt == 'A08') {
			/**
			 * Update Patient Information
			 */
			$patientData = $this->PidToPatient($msg->data['PID'], $hl7);
			$patient = $this->p->load($patientData[$this->updateKey])->one();

			if($patient === false){
				$this->ackStatus = 'AR';
				$this->ackMessage = 'Unable to find patient ' . $patientData[$this->updateKey];
			}
			$patient = array_merge($patient, $patientData);

			$patient = $this->p->save((object)$patient);
			$this->InsuranceGroupHandler($msg->data['INSURANCE'], $hl7, $patient);

			return;
		} elseif($evt == 'A09') {
			/**
			 * Patient Departing - Tracking
			 * PV1-3 - Assigned Patient Location
			 * PV1-6 - Prior Patient Location
			 * PV1-11 - Temporary Location
			 * PV1-42 - Pending Location
			 * PV1-43 - Prior Temporary Location
			 */
			$PID = $msg->data['PID'];
			$PV1 = $msg->data['PV1'];

			$filter = array();
			if($PID[3][4][1] == $this->getAssigningAuthority()){
				$filter['pid'] = $PID[3][1];
			} else {
				$filter['pubpid'] = $PID[3][1];
			}

			$patient = $this->p->load($filter)->one();
			if($patient === false){
				$this->ackStatus = 'AR';
				$this->ackMessage = 'Unable to find patient ' . $PID[3][1];
			}

			$newAreaId = $PV1[3][1];
			//$oldAreaId = $PV1[6][1];

			$PoolArea = new PoolArea();
			$areas = $PoolArea->getAreasArray();
			if(!array_key_exists($newAreaId, $areas)){
				$this->ackStatus = 'AR';
				$this->ackMessage = 'Unable to find Area ID ' . $newAreaId;
				return;
			}

			$params = new stdClass();
			$params->pid = $patient['pid'];
			$params->sendTo = $newAreaId;
			$PoolArea->sendPatientToPoolArea($params);
			unset($params);

			return;
		} elseif($evt == 'A10') {
			/**
			 * Patient Arriving - Tracking
			 * PV1-3  - As signed Patient Location
			 * PV1-6  - Prior Patient Location
			 * PV1-11 - Temporary Location
			 * PV1-43 - Prior Temporary Location
			 */
			$PID = $msg->data['PID'];
			$PV1 = $msg->data['PV1'];

			$filter = array();
			if($PID[3][4][1] == $this->getAssigningAuthority()){
				$filter['pid'] = $PID[3][1];
			} else {
				$filter['pubpid'] = $PID[3][1];
			}

			$patient = $this->p->load($filter)->one();
			if($patient === false){
				$this->ackStatus = 'AR';
				$this->ackMessage = 'Unable to find patient ' . $PID[3][1];
			}

			$newAreaId = $PV1[3][1];
			//$oldAreaId = $PV1[6][1];

			$PoolArea = new PoolArea();
			$areas = $PoolArea->getAreasArray();
			if(!array_key_exists($newAreaId, $areas)){
				$this->ackStatus = 'AR';
				$this->ackMessage = 'Unable to find Area ID ' . $newAreaId;
				return;
			}

			$params = new stdClass();
			$params->pid = $patient['pid'];
			$params->sendTo = $newAreaId;
			$PoolArea->sendPatientToPoolArea($params);
			unset($params);

			return;
		} elseif($evt == 'A18') {
			/**
			 * Merge Patient Information
			 * PID-2.1 <= MRG-4.1
			 */
			$pid = $msg->data['PATIENT']['PID'][2][1];
			$mrg = $msg->data['PATIENT']['MRG'][4][1];
			$aPatient = $this->p->load(array('pubpid' => $pid))->one();
			$bPatient = $this->p->load(array('pubpid' => $mrg))->one();
			$this->MergeHandler($aPatient, $bPatient, $pid, $mrg);

			return;
		} elseif($evt == 'A28') {
			/**
			 * Add Person or Patient Information
			 * PID-2.1 <= MRG-4.1
			 */
			$patientData = $this->PidToPatient($msg->data['PID'], $hl7);
			$patientData['pubpid'] = $patientData['pid'];
			$patientData['pid'] = 0;
			$patient = $this->p->save((object)$patientData);
			$this->InsuranceGroupHandler($msg->data['INSURANCE'], $hl7, $patient);

			return;
		} elseif($evt == 'A29') {
			/**
			 * Delete Person Information
			 */
		} elseif($evt == 'A31') {
			/**
			 * Update Person Information
			 */
			$patientData = $this->PidToPatient($msg->data['PID'], $hl7);
			$patient = $this->p->load($patientData[$this->updateKey])->one();

			if($patient === false){
				$this->ackStatus = 'AR';
				$this->ackMessage = 'Unable to find patient ' . $patientData[$this->updateKey];
			}
			$patient = array_merge($patient, $patientData);

			$patient = $this->p->save((object)$patient);
			$this->InsuranceGroupHandler($msg->data['INSURANCE'], $hl7, $patient);

			return;
		} elseif($evt == 'A32') {
			/** Cancel Patient Arriving - Tracking **/

			return;
		} elseif($evt == 'A33') {
			/** Cancel Patient Departing - Tracking **/

			return;
		} elseif($evt == 'A39') {
			/**
			 * Merge Person - Patient ID (Using External ID)
			 * PID-2.1 <= MRG-4.1
			 */
			$pid = $msg->data['PATIENT']['PID'][2][1];
			$mrg = $msg->data['PATIENT']['MRG'][4][1];
			$aPatient = $this->p->load(array('pubpid' => $pid))->one();
			$bPatient = $this->p->load(array('pubpid' => $mrg))->one();
			$this->MergeHandler($aPatient, $bPatient, $pid, $mrg);

			return;
		} elseif($evt == 'A40') {
			/**
			 * Merge Patient - Patient Identifier List
			 * PID-3.1 <= MRG-1.1
			 */
			$pid = $msg->data['PATIENT']['PID'][3][1];
			$mrg = $msg->data['PATIENT']['MRG'][1][1];
			$aPatient = $this->p->load(array('pid' => $pid))->one();
			$bPatient = $this->p->load(array('pid' => $mrg))->one();
			$this->MergeHandler($aPatient, $bPatient, $pid, $mrg);

			return;
		} elseif($evt == 'A41') {
			/**
			 * Merge Account - Patient Account Number
			 * PID-18.1 <= MRG-3.1
			 */
			$pid = $msg->data['PATIENT']['PID'][18][1];
			$mrg = $msg->data['PATIENT']['MRG'][3][1];
			$aPatient = $this->p->load(array('pubaccount' => $pid))->one();
			$bPatient = $this->p->load(array('pubaccount' => $mrg))->one();
			$this->MergeHandler($aPatient, $bPatient, $pid, $mrg);

			return;
		}

		/**
		 * Un handle event error
		 */
		$this->ackStatus = 'AR';
		$this->ackMessage = 'Unable to handle ADT_' . $evt;
	}

	/**
	 * @param array $insGroups
	 * @param HL7 $hl7
	 * @param null $patient
	 */
	protected function InsuranceGroupHandler($insGroups, $hl7, $patient = null) {
		/** if patient is not set don't do anything */
		if(!isset($patient))
			return;

		foreach($insGroups as $insuranceGroup){
			foreach($insuranceGroup as $key => $insurance){
				if($insurance == false)
					continue;
				if($key == 'IN1'){
					$in1 = $this->IN1ToInsuranceObj($insurance, $hl7);

				} elseif($key == 'IN2') {
					$in2 = $this->IN2ToInsuranceObj($insurance, $hl7);

				} elseif($key == 'IN3') {
					foreach($insurance as $IN3){
						$in3 = $this->IN3ToInsuranceObj($IN3, $hl7);

					}
				}
			}
		}
	}

	/**
	 * @param $aPatient
	 * @param $bPatient
	 * @param $pid
	 * @param $mrg
	 */
	protected function MergeHandler($aPatient, $bPatient, $pid, $mrg) {
		if($aPatient === false){
			$this->ackStatus = 'AR';
			$this->ackMessage = 'Unable to find primary patient - ' . $pid;
			return;
		} elseif($bPatient === false) {
			$this->ackStatus = 'AR';
			$this->ackMessage = 'Unable to find merge patient - ' . $mrg;
			return;
		}

		$merge = new Merge();
		$success = $merge->merge($aPatient['pid'], $bPatient['pid']);
		unset($merge);

		if($success === false){
			$this->ackStatus = 'AR';
			$this->ackMessage = 'Unable to merge patient ' . $aPatient['pid'] . ' <= ' . $bPatient['pid'];
			unset($aPatient, $bPatient);
			return;
		}
		unset($aPatient, $bPatient);
	}

	/**
	 * @param array $PID
	 * @param HL7 $hl7
	 *
	 * @return array
	 */
	protected function PidToPatient($PID, $hl7) {
		$p = array();

		if($this->notEmpty($PID[2][1]))
			$p['pubpid'] = $PID[2][1]; // Patient ID (External ID)

		if($this->notEmpty($PID[3][1]))
			$p['pid'] = $PID[3][1]; // Patient ID (Internal ID)

		if($this->notEmpty($PID[5][2]))
			$p['fname'] = $PID[5][2]; // Patient Name...

		if($this->notEmpty($PID[5][3]))
			$p['mname'] = $PID[5][3]; //

		if($this->notEmpty($PID[5][1][1]))
			$p['lname'] = $PID[5][1][1]; //

		if($this->notEmpty($PID[6][3]))
			$p['mothers_name'] = "{$PID[6][2]} {$PID[6][3]} {$PID[6][1][1]}"; // Mother’s Maiden Name

		if($this->notEmpty($PID[7][1]))
			$p['DOB'] = $hl7->time($PID[7][1]); // Date/Time of Birth

		if($this->notEmpty($PID[8]))
			$p['sex'] = $PID[8]; // Sex

		if($this->notEmpty($PID[9][3]))
			$p['alias'] = "{$PID[9][2]} {$PID[9][3]} {$PID[9][1][1]}"; // Patient Alias

		if($this->notEmpty($PID[10][1]))
			$p['race'] = $PID[10][1]; // Race

		if($this->notEmpty($PID[11][1][1]))
			$p['address'] = $PID[11][1][1]; // Patient Address

		if($this->notEmpty($PID[11][3]))
			$p['city'] = $PID[11][3]; //

		if($this->notEmpty($PID[11][4]))
			$p['state'] = $PID[11][4]; //

		if($this->notEmpty($PID[11][5]))
			$p['zipcode'] = $PID[11][5]; //

		if($this->notEmpty($PID[11][6]))
			$p['country'] = $PID[11][6]; // Country Code

		if($this->notEmpty($PID[13][7]))
			$p['home_phone'] = "{$PID[13][7]} . '-' . {$PID[13][1]}"; // Phone Number – Home

		if($this->notEmpty($PID[14][7]))
			$p['work_phone'] = "{$PID[14][7]} . '-' . {$PID[14][1]}"; // Phone Number – Business

		if($this->notEmpty($PID[15][1]))
			$p['language'] = $PID[15][1]; // Primary Language

		if($this->notEmpty($PID[16][1]))
			$p['marital_status'] = $PID[16][1]; // Marital Status

		//if($this->notEmpty($PID[17]))
		//  $p['00'] = $PID[17]; // Religion

		if($this->notEmpty($PID[18][1]))
			$p['pubaccount'] = $PID[18][1]; // Patient Account Number

		if($this->notEmpty($PID[19]))
			$p['SS'] = $PID[19]; // SSN Number – Patient

		if($this->notEmpty($PID[20][1]))
			$p['drivers_license'] = $PID[20][1]; // Driver’s License Number - Patient

		if($this->notEmpty($PID[20][2]))
			$p['drivers_license_state'] = $PID[20][2]; // Driver’s License State - Patient

		if($this->notEmpty($PID[20][3]))
			$p['drivers_license_exp'] = $PID[20][3]; // Driver’s License Exp Date - Patient

		//if($this->notEmpty($PID[21]))
		//  $p['00'] = $PID[21]; // Mother’s Identifier

		if($this->notEmpty($PID[22][1]))
			$p['ethnicity'] = $PID[22][1]; // Ethnic Group

		if($this->notEmpty($PID[23]))
			$p['birth_place'] = $PID[23]; // Birth Place

		if($this->notEmpty($PID[24]))
			$p['birth_multiple'] = $PID[24]; // Multiple Birth Indicator

		if($this->notEmpty($PID[25]))
			$p['birth_order'] = $PID[25]; // Birth Order

		if($this->notEmpty($PID[26][1]))
			$p['citizenship'] = $PID[26][1]; // Citizenship

		if($this->notEmpty($PID[27][1]))
			$p['is_veteran'] = $PID[27][1]; // Veterans Military Status

		if($this->notEmpty($PID[27][1]))
			$p['death_date'] = $PID[29][1]; // Patient Death Date and Time

		if($this->notEmpty($PID[30]))
			$p['deceased'] = $PID[30]; // Patient Death Indicator

		if($this->notEmpty($PID[33][1]))
			$p['update_date'] = $hl7->time($PID[33][1]); // Last update time stamp

		return $p;
	}

	/**
	 * @param array $IN1
	 * @param HL7 $hl7
	 *
	 * @return stdClass
	 */
	protected function IN1ToInsuranceObj($IN1, $hl7) {
		$i = array();
		if($this->notEmpty($IN1[0]))
			$i['pid'] = $IN1[0];
		if($this->notEmpty($IN1[0]))
			$i['pid'] = $IN1[0];
		if($this->notEmpty($IN1[0]))
			$i['pid'] = $IN1[0];
		if($this->notEmpty($IN1[0]))
			$i['pid'] = $IN1[0];
		if($this->notEmpty($IN1[0]))
			$i['pid'] = $IN1[0];

		return $i;
	}

	/**
	 * @param array $IN1
	 * @param HL7 $hl7
	 *
	 * @return stdClass
	 */
	protected function IN2ToInsuranceObj($IN1, $hl7) {
		$obj = new stdClass();
		if($this->notEmpty($IN1[0])){
			$p['pid'] = $IN1[0];
		}
		return $obj;
	}

	/**
	 * @param array $IN1
	 * @param HL7 $hl7
	 *
	 * @return stdClass
	 */
	protected function IN3ToInsuranceObj($IN1, $hl7) {
		$obj = new stdClass();
		if($this->notEmpty($IN1[0]))
			$p['id'] = $IN1[0];

		return $obj;
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 */
	private function notEmpty($data) {
		return isset($data) && ($data != '' && $data != '""' && $data != '\'\'');
	}

	/**
	 * @return string
	 */
	private function getAssigningAuthority() {
		return 'GAIA-' . Matcha::getInstallationNumber();
	}
}

//$msg = <<<EOF
//MSH|^~\&|^Test Application^ISO|^2.16.840.1.113883.3.72.5.21^ISO||^2.16.840.1.113883.3.72.5.23^ISO|20110531140551-0500||ORU^R01^ORU_R01|NIST-LRI-GU-002.00|T|2.5.1|||AL|NE|||||LRI_Common_Component^^2.16.840.1.113883.9.16^ISO~LRI_GU_Component^^2.16.840.1.113883.9.12^ISO~LRI_RU_Component^^2.16.840.1.113883.9.14^ISO
//PID|1||1^^^&2.16.840.1.113883.3.72.5.30.2&ISO^MR||Jones^William^A||19610615|M||2106-3^White^HL70005
//ORC|RE|1^^2.16.840.1.113883.3.72.5.24^ISO|R-991133^^2.16.840.1.113883.3.72.5.25^ISO|GORD874233^^2.16.840.1.113883.3.72.5.24^ISO||||||||57422^Radon^Nicholas^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI
//OBR|1|1^^2.16.840.1.113883.3.72.5.24^ISO|R-991133^^2.16.840.1.113883.3.72.5.25^ISO|57021-8^CBC W Auto Differential panel in Blood^LN^4456544^CBC^99USI^^^CBC W Auto Differential panel in Blood|||20110103143428-0800|||||||||57422^Radon^Nicholas^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI||||||20110104170028-0800|||F|||10093^Deluca^Naddy^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI|||||||||||||||||||||CC^Carbon Copy^HL70507
//OBX|1|NM|26453-1^Erythrocytes [#/volume] in Blood^LN^^^^^^Erythrocytes [#/volume] in Blood||4.41|10*6/uL^million per microliter^UCUM|4.3 to 6.2|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|2|NM|718-7^Hemoglobin [Mass/volume] in Blood^LN^^^^^^Hemoglobin [Mass/volume] in Blood||12.5|g/mL^grams per milliliter^UCUM|13 to 18|L|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|3|NM|20570-8^Hematocrit [Volume Fraction] of Blood^LN^^^^^^Hematocrit [Volume Fraction] of Blood||41|%^percent^UCUM|40 to 52|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|4|NM|26464-8^Leukocytes [#/volume] in Blood^LN^^^^^^Leukocytes [#/volume] in Blood||105600|{cells}/uL^cells per microliter^UCUM|4300 to 10800|HH|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|5|NM|26515-7^Platelets [#/volume] in Blood^LN^^^^^^Platelets [#/volume] in Blood||210000|{cells}/uL^cells per microliter^UCUM|150000 to 350000|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|6|NM|30428-7^Erythrocyte mean corpuscular volume [Entitic volume]^LN^^^^^^Erythrocyte mean corpuscular volume [Entitic volume]||91|fL^femtoliter^UCUM|80 to 95|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|7|NM|28539-5^Erythrocyte mean corpuscular hemoglobin [Entitic mass]^LN^^^^^^Erythrocyte mean corpuscular hemoglobin [Entitic mass]||29|pg/{cell}^picograms per cell^UCUM|27 to 31|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|8|NM|28540-3^Erythrocyte mean corpuscular hemoglobin concentration [Mass/volume]^LN^^^^^^Erythrocyte mean corpuscular hemoglobin concentration [Mass/volume]||32.4|g/dL^grams per deciliter^UCUM|32 to 36|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|9|NM|30385-9^Erythrocyte distribution width [Ratio]^LN^^^^^^Erythrocyte distribution width [Ratio]||10.5|%^percent^UCUM|10.2 to 14.5|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|10|NM|26444-0^Basophils [#/volume] in Blood^LN^^^^^^Basophils [#/volume] in Blood||0.1|10*3/uL^thousand per microliter^UCUM|0 to 0.3|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|11|NM|30180-4^Basophils/100 leukocytes in Blood^LN^^^^^^Basophils/100 leukocytes in Blood||0.1|%^percent^UCUM|0 to 2|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|12|NM|26484-6^Monocytes [#/volume] in Blood^LN^^^^^^Monocytes [#/volume] in Blood||3|10*3/uL^thousand per microliter^UCUM|0.0 to 13.0|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|13|NM|26485-3^Monocytes/100 leukocytes in Blood^LN^^^^^^Monocytes/100 leukocytes in Blood||3|%^percent^UCUM|0 to 10|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|14|NM|26449-9^Eosinophils [#/volume] in Blood^LN^^^^^^Eosinophils [#/volume] in Blood||2.1|10*3/uL^thousand per microliter^UCUM|0.0 to 0.45|HH|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|15|NM|26450-7^Eosinophils/100 leukocytes in Blood^LN^^^^^^Eosinophils/100 leukocytes in Blood||2|%^percent^UCUM|0 to 6|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|16|NM|26474-7^Lymphocytes [#/volume] in Blood^LN^^^^^^Lymphocytes [#/volume] in Blood||41.2|10*3/uL^thousand per microliter^UCUM|1.0 to 4.8|HH|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|17|NM|26478-8^Lymphocytes/100 leukocytes in Blood^LN^^^^^^Lymphocytes/100 leukocytes in Blood||39|%^percent^UCUM|15.0 to 45.0|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|18|NM|26499-4^Neutrophils [#/volume] in Blood^LN^^^^^^Neutrophils [#/volume] in Blood||58|10*3/uL^thousand per microliter^UCUM|1.5 to 7.0|HH|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|19|NM|26511-6^Neutrophils/100 leukocytes in Blood^LN^^^^^^Neutrophils/100 leukocytes in Blood||55|%^percent^UCUM|50 to 73|N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|20|CWE|38892-6^Anisocytosis [Presence] in Blood^LN^^^^^^Anisocytosis [Presence] in Blood||260348001^Present ++ out of ++++^SCT^^^^^^Moderate Anisocytosis|||A|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|21|CWE|30400-6^Hypochromia [Presence] in Blood^LN^^^^^^Hypochromia [Presence] in Blood||260415000^not detected^SCT^^^^^^None seen|||N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|22|CWE|30424-6^Macrocytes [Presence] in Blood^LN^^^^^^Macrocytes [Presence] in Blood||260415000^not detected^SCT^^^^^^None seen|||N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|23|CWE|30434-5^Microcytes [Presence] in Blood^LN^^^^^^Microcytes [Presence] in Blood||260415000^not detected^SCT^^^^^^None seen|||N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|24|CWE|779-9^Poikilocytosis [Presence] in Blood by Light microscopy^LN^^^^^^Poikilocytosis [Presence] in Blood by Light microscopy||260415000^not detected^SCT^^^^^^None seen|||N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|25|CWE|10378-8^Polychromasia [Presence] in Blood by Light microscopy^LN^^^^^^Polychromasia [Presence] in Blood by Light microscopy||260415000^not detected^SCT^^^^^^None seen|||N|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|26|TX|6742-1^Erythrocyte morphology finding [Identifier] in Blood^LN^^^^^^Erythrocyte morphology finding [Identifier] in Blood||Many spherocytes present.|||A|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|27|TX|11156-7^Leukocyte morphology finding [Identifier] in Blood^LN^^^^^^Leukocyte morphology finding [Identifier] in Blood||Reactive morphology in lymphoid cells.|||A|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|28|TX|11125-2^Platelet morphology finding [Identifier] in Blood^LN^^^^^^Platelet morphology finding [Identifier] in Blood||Platelets show defective granulation.|||A|||F|||20110103143428-0800|||||20110103163428-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//SPM|1|||119297000^BLD^SCT^^^^^^Blood|||||||||||||20110103calen143428-0800
//EOF;

//$msg = <<<EOF
//MSH|^~\&|^2.16.840.1.113883.3.72.5.20^ISO|^2.16.840.1.113883.3.72.5.21^ISO||^2.16.840.1.113883.3.72.5.23^ISO|20110531140551-0500||ORU^R01^ORU_R01|NIST-LRI-GU-003.00|T|2.5.1|||AL|NE|||||LRI_Common_Component^^2.16.840.1.113883.9.16^ISO~LRI_GU_Component^^2.16.840.1.113883.9.12^ISO~LRI_RU_Component^^2.16.840.1.113883.9.14^ISO
//PID|1||8^^^&2.16.840.1.113883.3.72.5.30.2&ISO^MR||Jones^William^A||19610615|M||2106-3^White^HL70005
//ORC|RE|8^^2.16.840.1.113883.3.72.5.24^ISO|R-220713^^2.16.840.1.113883.3.72.5.25^ISO|GORD874244^^2.16.840.1.113883.3.72.5.24^ISO||||||||57422^Radon^Nicholas^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI
//OBR|1|8^^2.16.840.1.113883.3.72.5.24^ISO|R-220713^^2.16.840.1.113883.3.72.5.25^ISO|24331-1^Lipid 1996 panel in Serum or Plasma^LN^345789^Lipid Panel^99USI^^^Lipid 1996 panel in Serum or Plasma|||20110531123551-0800||||||56388000^hyperlipidemia^99USI^3744001^hyperlipoproteinemia^SCT^^^hyperlipoproteinemia|||57422^Radon^Nicholas^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI||||||20110611140428-0800|||F|||10092^Hamlin^Pafford^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI|||||||||||||||||||||BCC^Blind Copy^HL70507
//OBX|1|NM|2093-3^Cholesterol [Mass/volume] in Serum or Plasma^LN^^^^^^Cholesterol [Mass/volume] in Serum or Plasma||196|mg/dL^milligrams per deciliter^UCUM|Recommended: <200; Moderate Risk: 200-239 ; High Risk: >240|N|||F|||20110531123551-0800|||||20110601130551-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|2|NM|2571-8^Triglyceride [Mass/volume] in Serum or Plasma^LN^^^^^^Triglyceride [Mass/volume] in Serum or Plasma||100|mg/dL^milligrams per deciliter^UCUM|40 to 160|N|||F|||20110531123551-0800|||||20110601130551-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|3|NM|2085-9^Cholesterol in HDL [Mass/volume] in Serum or Plasma^LN^^^^^^Cholesterol in HDL [Mass/volume] in Serum or Plasma||60|mg/dL^milligrams per deciliter^UCUM|29 to 72|N|||F|||20110531123551-0800|||||20110601130551-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//OBX|4|NM|2089-1^Cholesterol in LDL [Mass/volume] in Serum or Plasma^LN^^^^^^Cholesterol in LDL [Mass/volume] in Serum or Plasma||116|mg/dL^milligrams per deciliter^UCUM|Recommended: <130; Moderate Risk: 130-159; High Risk: >160|N|||F|||20110531123551-0800|||||20110601130551-0800||||Century Hospital^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|2070 Test Park^^Los Angeles^CA^90067^^B|2343242^Knowsalot^Phil^^^Dr.^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^DN
//SPM|1|||119297000^BLD^SCT^^^^^^Blood|||||||||||||20110531123551-0800
//EOF;

//$msg = <<<EOF
//MSH|^~\&|EPIC|EPICADT|SMS|SMSADT|199912271408|CHARRIS|ADT^A04|1817457|D|2.5|
//PID||0493575^^^2^ID 1|454721||DOE^JOHN^^^^|DOE^JOHN^^^^|19480203|M||B|254 MYSTREET AVE^^MYTOWN^OH^44123^USA||(216)123-4567|||M|NON|400003403~1129086|
//NK1||ROE^MARIE^^^^|SPO||(216)123-4567||EC|||||||||||||||||||||||||||
//PV1||O|168 ~219~C~PMA^^^^^^^^^||||277^ALLEN MYLASTNAME^BONNIE^^^^|||||||||| ||2688684|||||||||||||||||||||||||199912271408||||||002376853
//EOF;

//$msg = <<<EOF
//MSH|^~\&||OTHER REG MED CTR^1234567890^NPI|||201102171531||ADT^A04^ADT_A01|201102171531956|P|2.5.1
//EVN||201102171531
//PID|1||FL01059711^^^^PI||~^^^^^^U|||F||2106-3^White^CDCREC|^^^12^33821|||||||||||2186-5^Not Hispanic^CDCREC
//PV1||E||E||||||||||7|||||V20220217-00274^^^^VN|||||||||||||||||||||||||201102171522
//PV2|||78907^ABDOMINAL PAIN, GENERALIZED^I9CDX
//OBX|1|HD|SS001^TREATING FACILITY IDENTIFIER^PHINQUESTION||OTHER REG MED CTR^1234567890^NPI||||||F|||201102171531
//OBX|2|CWE|8661-1^CHIEF COMPLAINT:FIND:PT:PATIENT:NOM:REPORTED^LN||^^^^^^^^STOMACH ACHE||||||F|||201102171531
//OBX|3|NM|21612-7^AGE TIME PATIENT REPORTED^LN||43|a^YEAR^UCUM|||||F|||201102171531
//DG1|1||78900^ABDMNAL PAIN UNSPCF SITE^I9CDX|||A
//EOF;

//$msg = <<<EOF
//MSH|^~\&|REGADT|GOOD HEALTH HOSPITAL|RSP1P8|GOOD HEALTH HOSPI- TAL|200701051530|SEC|ADT^A09^ADT_A09|00000003|P|2.5.1
//EVN|A09|200701051530
//PID|||6^^^GAIA-1||EVERYWOMAN^EVE|
//PV1|1||2|||1|1|||||||||Y
//EOF;

//$msg = <<<EOF
//MSH|^~\&|TRA|||00|20140918123529||ADT^A28||P|2.5.1
//EVN|A01|20140918123529
//PID|||R28112^^||SUAREZ CASTRO^TERESA||19630306|F||2106-3^White|PO-BOX-362319^^SAN JUAN^PR^00936||7877069054|7872505555|spa^Spanish|S|||000002305|||H^Hispanic
//PV1|OP|I|||||||||||||||000^RADIOLOGIA^ADMIN|00|140900008||P|||||||||||||||||||||||20140918123529|20140918123529
//EOF;


// RGS-1 = This field contains a number that uniquely identifies the information represented by this segment in this transaction for the purposes of addition, change or deletion.
// RGS-2  A = Add/Insert, D = Delete, U = Update

//$msg = <<<EOF
//MSH|^~\&|GPMS|CTX||MED2000|200803060953||SIU^S14|20080306953450|P|2.5.1||||||||
//SCH|00331839401|||||58||HLCK^HEALTHCHECK ANY AGE|20|MIN|^^^200803061000 |||||JOHN||||VALERIE|||||ARRIVED|
//PID|1||489671|0|FULANO^DE TAL^||20080205|F|||CALLE DEL SOL^SAN JUAN^PR^00987||7958259|||S|||999999999||||||||||||
//RGS|1|A||
//EOF;


//$msg = <<<EOF
//MSH|^~\&|Test Application|1|TRARIS||201411040737||ORM^O01|20141104073774636|P|2.5.1
//PID|||32061||DEL PUEBLO^MANUEL||19010101|M|||BO XXXX^CARR 000 K 5 H 5 INT 9983^LUQUILLO^PR^00773||7878888888|||||74636
//PV1||E|EGY^0^0||||1992960355^SOLIVAN SOBRINO^ENRIQUE~1992960355^RODRIGUEZ GUZAMN^ERNESTO|||||||||||ER||||||||||||||||||||||||||201411040650
//ORC|NW|36353|||||||201411040736|||1992960355^SOLIVAN SOBRINO^ENRIQUE
//OBR|1|36353||74177-57969^COMP TOMOGRAPHY ABDOMEN/PELVIS W CONTRAST|R||201411040736|||||||||1992960355^SOLIVAN SOBRINO^ENRIQUE||||||||CT|||^^^201411040736^^R||||^ABDOMINAL PAIN;ABDOMINAL DISTENTION;R/O INTESTINAL OBSTRUCTION
//EOF;
////
////
//include_once(dirname(dirname(__FILE__)).'/lib/HL7/HL7.php');
//print '<pre>';
//$hl7 = new HL7Server();
//print $hl7->Process($msg);
