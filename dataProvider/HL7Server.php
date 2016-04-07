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
        include_once(ROOT . '/dataProvider/Facility.php');

		new MatchaHelper();

		/** HL7 Models */
        if(!isset($this->s))
            $this->s = MatchaModel::setSenchaModel('App.model.administration.HL7Server');
        if(!isset($this->m))
            $this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Message');
        if(!isset($this->r))
            $this->r = MatchaModel::setSenchaModel('App.model.administration.HL7Client');

		/** Patient Model */
        if(!isset($this->p))
            $this->p = MatchaModel::setSenchaModel('App.model.patient.Patient');

        /**
         * User facilities
         */
        $this->Facility = MatchaModel::setSenchaModel('App.model.administration.Facility');

		/** Order Models */
        if(!isset($this->pOrder))
            $this->pOrder = MatchaModel::setSenchaModel('App.model.patient.PatientsOrders');
        if(!isset($this->pResult))
            $this->pResult = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderResult');
        if(!isset($this->pObservation))
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
		 * The first segment of the HL7 Message
		 */
		$ack = new HL7();
		$msh = $ack->addSegment('MSH');
		$msh->setValue('3.1', 'GaiaEHR'); // Sending Application
		$msh->setValue('4.1', $this->Facility->getgetCurrentFacility(true)); // Sending Facility
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
