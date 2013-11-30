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
include_once('../registry.php');

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
	 * @var bool
	 */
	private $error;
	/**
	 * @var string
	 */
	private $site;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $msg;


	function __construct($site = 'default'){
		$this->site = $site;
		include_once($_SESSION['root']."/sites/$this->site/conf.php");
		include_once($_SESSION['root'].'/classes/MatchaHelper.php');
		include_once($_SESSION['root'].'/lib/HL7/HL7.php');
		new MatchaHelper();

		$this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Messages');
		$this->r = MatchaModel::setSenchaModel('App.model.administration.HL7Recipients');
		$this->error = false;

	}

	public function Process($msg = ''){
		$this->msg = $msg;

//		$recipient = $this->r->load(array('recipient' => $_SERVER['REMOTE_ADDR']))->one();
//		if($recipient === false){
//			$this->error = 'IP Address Not Authorized';
//		}

		$hl7 = new HL7();
		$msg = $hl7->readMessage(trim($this->msg));

		if($msg === false){
			$this->error = 'Unable to parse HL7 message, please contact Support Desk';
		}

//	print $msg;
		$message = new stdClass();
		$message->msg_type = $hl7->getMsgType();
		$message->message = $this->msg;
		$message->foreign_facility = $hl7->getSendingFacility();
		$message->foreign_application = $hl7->getSendingApplication();
		$message->foreign_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$message->isOutbound = '0';
		$message->status = '2';
		$message->date_processed = date('Y-m-d H:i:s');
		$message = $this->m->save($message);
		$message = $message['data'];

		//print '<br>';
		//print_r($hl7->getSegment('PID')->data);
		//save message to database

		if($this->error === false){
			switch($hl7->getMsgType()){
				case 'ORU':

					$po = MatchaModel::setSenchaModel('App.model.patient.PatientsOrders');
					$poRep = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderReports');
					$poRes = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderResults');

					foreach($msg->data['PATIENT_RESULT'] AS $patient_result){
					// patient info
//					$patient = $patient_result['PATIENT'];
					$patient = isset($patient_result['PATIENT']) ? $patient_result['PATIENT'] : null;


						foreach($patient_result['ORDER_OBSERVATION'] AS $order){
							// order info
							$orc = $order['ORC'];
							$obr = $order['OBR'];

							$oData = new stdClass();
							$oData->order_id = $obr[2][1];
							$oData->lab_order_id = $obr[3][1];
							$oData->code = $obr[4][1];
							$oData->code_text = $obr[4][2];
							$oData->code_type = $obr[4][3];
							$oData->lab_order_id = $obr[3][1];
							$oData->lab_collected_date = $hl7->time($obr[7][1]);
							$oData->result_status = $obr[25];
							//					$oData->reason_code = $obr[31][3].':'.$obr[31][1];

							if(is_array($obr[31][1])){
								$foo = array();
								foreach($obr[31] AS $dx){
									$foo[] = $dx[3].':'.$dx[1];
								}
								$oData->reason_code = implode(',',$foo);
							}else{
								$oData->reason_code = $obr[31][3].':'.$obr[31][1];
							}




							if(isset($order['SPECIMEN']) && $order['SPECIMEN'] !== false){
								// specimen segment
								$spm = $order['SPECIMEN']['SPM'];
								//						print_r($spm);
								$oData->specimen_code = $spm[4][6] == 'HL70487' ? $spm[4][4] : $spm[4][1];
								$oData->specimen_text = $spm[4][6] == 'HL70487' ? $spm[4][5] : $spm[4][2];
								$oData->specimen_code_type = $spm[4][6] == 'HL70487' ? $spm[4][6] : $spm[4][3];
								$oData->specimen_notes = $spm[4][6] == 'HL70487' ? $spm[4][6] : $spm[4][3];
								// handle multiple SPECIMEN OBX's
		//						if(isset($order['SPECIMEN']['OBX']) && $order['SPECIMEN']['OBX'] !== false){
		//							foreach($order['SPECIMEN']['OBX'] AS $obx){
		//								print_r($obx);
		//							}
		//						}
							}
							//					print 'Order Data: ';
							//					print_r($oData);


							foreach($order['OBSERVATION'] AS $observation){
								// observations and notes
								$obx = $observation['OBX'];
								$note = $observation['NTE'];

								$result = new stdClass();
								$result->code = $obx[3][1];
								$result->code_text = $obx[3][2];
								$result->code_type = $obx[3][3];
								$result->value = $obx[5];
								$result->units = $obx[6][1];
								$result->reference_rage = $obx[7];
								$result->probability = $obx[9];
								$result->abnormal_flag = $obx[8];
								$result->nature_of_abnormal = $obx[10];
								$result->observation_result_status = $obx[11];
								$result->date_rage_values = $hl7->time($obx[12][1]);
								$result->date_observation = $hl7->time($obx[14][1]);
								$result->observer = $obx[16][2][1] . ' ' . $obx[16][3];
								$result->date_analysis = $hl7->time($obx[19][1]);
								$result->notes = $note['3'];
								$result->resultsDoc = $message['id'];

								$rData[] = $result;

							}

							//					print('Result Data: ');
							//					print_r($rData);
						}
					}
					break;
				default:


					break;
			}

		}

		//We got useful data from socket_read(), so let's echo it.
		// "$socket" will be output as "Resource id #n", where n is
		// the internal ID of the socket, e.g. "Resource id #3"
		//Note also that $data can be an empty string, so we check
		// for that in our "elseif ($data)" line
		$ack = new HL7();
		$msh = $ack->addSegment('MSH');
		$msh->setValue('3.1','GaiaEHR');    // Sending Application
		$msh->setValue('4.1', '');          // Sending Facility
		$msh->setValue('9.1','ACK');
		$msh->setValue('11.1','P');         // P = Production
		$msh->setValue('12.1','2.5.1');     // HL7 version

		$msa =  $ack->addSegment('MSA');
		$msa->setValue('1','AA');                   // AA = Positive acknowledgment, AE = Application error, AR = Application reject
		$msa->setValue('2', $hl7->getMsgControlId());  // Message Control ID from MSH
		$msa->setValue('3', '');            // Error Message
		$foo = $ack->getMessage();

		$message['response'] = $foo;
		$this->m->save((object)$message);

		unset($ack, $hl7, $msg, $message, $oData, $result);


//		$foo = <<<EOF
//MSH|^~\&|GaiaEHR||||||ACK|GAIA-304706561226701|P|2.5.1
//MSA|AA|NIST-110316102013209
//EOF;

//		$foo = trim($foo);

		return "\v".$foo.chr(0x1c).chr(0x0d);
	}
}

//print '<pre>';
//$hl7 = new HL7Messages();
//print_r($hl7->sendVXU());