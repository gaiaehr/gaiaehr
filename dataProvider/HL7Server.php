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
if (!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
define('_GaiaEXEC', 1);
include_once(dirname(dirname(__FILE__)) . '/registry.php');

class HL7Server {

	/**
	 * @var HL7
	 */
	private $hl7;
	/**
	 * @var HL7
	 */
	private $ack;
	/**
	 * @var MatchaCUP
	 */
	private $m;
	/**
	 * @var MatchaCUP
	 */
	private $r;
	/**
	 * @var MatchaCUP
	 */
	private $s;
	/**
	 * @var bool
	 */
	private $ackStatus;
	/**
	 * @var string
	 */
	private $ackMessage;
	/**
	 * @var string
	 */
	private $site;
	/**
	 * @var int
	 */
	private $port;
	/**
	 * @var array|bool
	 */
	private $recipient;

	/**
	 * @var string
	 */
	private $msg;


	function __construct($site = 'default'){
		$this->site = $site;

		include_once(dirname(dirname(__FILE__))."/sites/{$this->site}/conf.php");
		include_once(dirname(dirname(__FILE__)).'/classes/MatchaHelper.php');
		include_once(dirname(dirname(__FILE__)).'/lib/HL7/HL7.php');
		include_once(dirname(__FILE__).'/HL7ServerHandler.php');
		new MatchaHelper();


		$this->s = MatchaModel::setSenchaModel('App.model.administration.HL7Server');
		$this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Messages');
		$this->r = MatchaModel::setSenchaModel('App.model.administration.HL7Recipients');
	}

	public function getServers($params){
		$servers = $this->s->load($params)->all();
		foreach($servers['data'] as $i => $server){
			$handler = new HL7ServerHandler();
			$status = $handler->status($server['port']);
			$servers['data'][$i]['online'] = $status['online'];
		}

		return $servers;
	}

	public function getServer($params){
		return $this->s->load($params)->one();
	}

	public function addServer($params){
		return $this->s->save($params);
	}

	public function updateServer($params){
		return $this->s->save($params);
	}

	public function deleteServer($params){
		return $this->s->destroy($params);
	}


	public function Process($msg = ''){
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
		$version =  $hl7->getMsgVersionId();

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
		$this->recipient = $this->r->load(array('recipient_application' => $application))->one();
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
		$record = new stdClass();
		$record->msg_type = $hl7->getMsgType();
		$record->message = $this->msg;
		$record->foreign_facility = $hl7->getSendingFacility();
		$record->foreign_application = $hl7->getSendingApplication();
		$record->foreign_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$record->isOutbound = '0';
		$record->status = '2';
		$record->date_processed = date('Y-m-d H:i:s');
		$record = $this->m->save($record);
		$record = (array) $record['data'];

		if($this->ackStatus == 'AA'){
			/**
			 *
			 */
			switch($hl7->getMsgType()){
				case 'ORU':

					$this->ProcessORU($hl7, $msg, $record);
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
		$msh->setValue('3.1','GaiaEHR');                // Sending Application
		$msh->setValue('4.1', 'Gaia');                  // Sending Facility
		$msh->setValue('9.1','ACK');
		$msh->setValue('11.1','P');                     // P = Production
		$msh->setValue('12.1','2.5.1');                 // HL7 version
		$msa =  $ack->addSegment('MSA');
		$msa->setValue('1',$this->ackStatus);           // AA = Positive acknowledgment, AE = Application error, AR = Application reject
		$msa->setValue('2', $hl7->getMsgControlId());   // Message Control ID from MSH
		$msa->setValue('3', $this->ackMessage);         // Error Message
		$foo = $ack->getMessage();

		$record['response'] = $foo;
		$this->m->save((object)$record);

		// unset all the variables to release memory
		unset($ack, $hl7, $msg, $record, $oData, $result);


		return "\v".$foo.chr(0x1c).chr(0x0d);
	}


	private function ProcessORU($hl7, $msg, $record){
		
		$mOrder = MatchaModel::setSenchaModel('App.model.patient.PatientsOrders');
		$mResult = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderResult');
		$mObservation = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderObservation');


		foreach($msg->data['PATIENT_RESULT'] AS $patient_result){

			$patient = isset($patient_result['PATIENT']) ? $patient_result['PATIENT'] : null;

			foreach($patient_result['ORDER_OBSERVATION'] AS $order){

//				print_r($order);

				$orc = $order['ORC'];
				$obr = $order['OBR'];

				/**
				 * Check for order number in GaiaEHR
				 */
				$orderId = $orc[2][1];
				$orderRecord = $mOrder->load(array('id' => $orderId))->one();
				/**
				 * id not found set the error and break twice to get out of all the loops
				 */
				if($orderRecord === false){
					$this->ackStatus = 'AR';
					$this->ackMessage = "Unable to find order number '$orderId' within the system";
					break 2;
				}

//				print_r($obr);

				$foo = new stdClass();
				$foo->order_id = $obr[2][1];
				$foo->lab_order_id = $obr[3][1];
				$foo->lab_name = $this->recipient['recipient_facility'];
				$foo->lab_address = $this->recipient['recipient_address'];
				$foo->observation_time = $hl7->time($obr[7][1]);
				$foo->result_status = $obr[25];

				if(is_array($obr[31][1])){
					$foo = array();
					foreach($obr[31] AS $dx){
						$foo[] = $dx[3].':'.$dx[1];
					}
					$foo->reason_code = implode(',',$foo);
				}else{
					$foo->reason_code = $obr[31][3].':'.$obr[31][1];
				}

				// specimen segment
				if(isset($order['SPECIMEN']) && $order['SPECIMEN'] !== false){
					$spm = $order['SPECIMEN']['SPM'];
//			        print_r($spm);
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

				$foo->documentId = 'hl7|' . $record['id'];

				$rResult = (array) $mResult->save($foo);
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
					}else{
						$foo->value = $obx[5];
					}
					$foo->units = $obx[6][1];
					$foo->reference_rage = $obx[7];
					$foo->probability = $obx[9];
					$foo->abnormal_flag = $obx[8];
					$foo->nature_of_abnormal = $obx[10];
					$foo->observation_result_status = $obx[11];
					$foo->date_rage_values = $hl7->time($obx[12][1]);
					$foo->date_observation = $hl7->time($obx[14][1]);
					$foo->observer = trim($obx[16][2][1] . ' ' . $obx[16][3]);
					$foo->performing_org_name = $obx[23][1] ;
					$foo->performing_org_address = $obx[24][1][1] . ' ' . $obx[24][3] . ', ' . $obx[24][4] . ' ' . $obx[24][5];
					$foo->date_analysis = $hl7->time($obx[19][1]);
					$foo->notes = $note['3'];
					$mObservation->save($foo);
					unset($foo);

				}

				/**
				 * Change the order status to received
				 */
				$foo = new stdClass();
				$foo->id = $orderId;
				$foo->status = 'Received';
				$mOrder->save($foo);
				unset($foo);
			}
		}
	}
}
//$msg = <<<EOF
//MSH|^~\&|^2.16.840.1.113883.3.72.5.20^ISO|^2.16.840.1.113883.3.72.5.21^ISO||^2.16.840.1.113883.3.72.5.23^ISO|20110531140551-0500||ORU^R01^ORU_R01|NIST-LRI-GU-002.00|T|2.5.1|||AL|NE|||||LRI_Common_Component^^2.16.840.1.113883.9.16^ISO~LRI_GU_Component^^2.16.840.1.113883.9.12^ISO~LRI_RU_Component^^2.16.840.1.113883.9.14^ISO
//PID|1||PATID1234^^^&2.16.840.1.113883.3.72.5.30.2&ISO^MR||Jones^William^A||19610615|M||2106-3^White^HL70005
//ORC|RE|6^^2.16.840.1.113883.3.72.5.24^ISO|R-991133^^2.16.840.1.113883.3.72.5.25^ISO|GORD874233^^2.16.840.1.113883.3.72.5.24^ISO||||||||57422^Radon^Nicholas^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI
//OBR|1|6^^2.16.840.1.113883.3.72.5.24^ISO|R-991133^^2.16.840.1.113883.3.72.5.25^ISO|57021-8^CBC W Auto Differential panel in Blood^LN^4456544^CBC^99USI^^^CBC W Auto Differential panel in Blood|||20110103143428-0800|||||||||57422^Radon^Nicholas^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI||||||20110104170028-0800|||F|||10093^Deluca^Naddy^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI|||||||||||||||||||||CC^Carbon Copy^HL70507
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
//SPM|1|||119297000^BLD^SCT^^^^^^Blood|||||||||||||20110103143428-0800
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
//MSH|^~\&||LAB1||SITE1|20090601103638||ADT^A08|JPANUCCI-0091|T|2.2|5109
//EVN|A08|20090601103638||||
//PID|1|SH0091|SH0091||Panucci^John^||19490314|M||G|33 10th Ave.^^Costa Mesa^CA^92330||(714) 555-0091^^||ENG|M|CATH|SHACT0091|123-45-6789||
//NK1|1|Panucci^Joan|HU|7056 Culver^^IRVINE^CA^92612|(949)211-4615|
//NK1|2|^||^^^^||
//PV1||O|Z27|2|||16^VAN HOUTEN^KIRK^|11^FLANDERS^MAUDE^|14^VAN HOUTEN^MILLHOUSE^|O/P||||1|||005213^KURZWEIL^PETER^R|O|1|MA|||||||||||||||||||SIMPSON CLINIC|||||200906011027
//PV2|||^MENISCUS TEAR RIGHT KNEE|||||||||EKG/LAB||VET
//GT1|||Washington^GEORGE^M||7056 Main St^^SEATTLE^WA^98101|(234)211-4615|||M||01^PATIENT IS INSURED|677-47-2055||||DISABLED
//IN1|1|MB|0011|MEDICARE OP ONLY MCR M|||||
//IN1|2|MSCP|0I38|MEM SENIOR COMPPLN IND I|||||
//EOF;

//$msg = <<<EOF
//MSH|^~\&||OTHER REG MED CTR^1234567890^NPI|||201102171531||ADT^A04^ADT_A01|201102171531956|P|2.3.1
//EVN||201102171531
//PID|1||FL01059711^^^^PI||~^^^^^^U|||F||2106-3^White^CDCREC|^^^12^33821|||||||||||2186-5^Not Hispanic^CDCREC
//PV1||E||E||||||||||7|||||V20220217-00274^^^^VN|||||||||||||||||||||||||201102171522
//PV2|||78907^ABDOMINAL PAIN, GENERALIZED^I9CDX
//OBX|1|HD|SS001^TREATING FACILITY IDENTIFIER^PHINQUESTION||OTHER REG MED CTR^1234567890^NPI||||||F|||201102171531
//OBX|2|CWE|8661-1^CHIEF COMPLAINT:FIND:PT:PATIENT:NOM:REPORTED^LN||^^^^^^^^STOMACH ACHE||||||F|||201102171531
//OBX|3|NM|21612-7^AGE TIME PATIENT REPORTED^LN||43|a^YEAR^UCUM|||||F|||201102171531
//DG1|1||78900^ABDMNAL PAIN UNSPCF SITE^I9CDX|||A
//EOF;
//


//include_once(dirname(dirname(__FILE__)).'/lib/HL7/HL7.php');

//print '<pre>';
//
//$hl7 = new HL7();
//$msg = $hl7->readMessage($msg);
//print_r($msg);

//$hl7 = new HL7Server();
//$hl7->Process($msg);
//print $hl7->Process($msg);