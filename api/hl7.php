<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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
//if(!isset($_SESSION)){
session_name('GaiaEHR');
session_start();
session_cache_limiter('private');
//}
define('_GaiaEXEC', 1);
include_once('../../../registry.php');
include_once('../conf.php');
include_once('../../../classes/MatchaHelper.php');
include_once('../../../lib/HL7/HL7.php');
new MatchaHelper();

class MsgHandler {

	/**
	 * @var HL7
	 */
	private $hl7;
	/**
	 * @var MatchaCUP
	 */
	private $m;
	/**
	 * @var MatchaCUP
	 */
	private $r;
	private $error;

	function __construct(){
		$this->hl7 = new HL7();
		$this->m = MatchaModel::setSenchaModel('App.model.administration.HL7Messages');
		$this->r = MatchaModel::setSenchaModel('App.model.administration.HL7Recipients');
		$this->error = false;
	}

	function Process($incomingMsg){

		$recipient = $this->r->load(array('recipient' => $_SERVER['REMOTE_ADDR']))->one();
		if($recipient === false){
			$this->error = 'IP Address Not Authorized';
		}

		$incomingMsg = trim($incomingMsg);
		$msg = $this->hl7->readMessage($incomingMsg);

		if($msg === false){
			$this->error = 'Unable to parse HL7 message, please contact Support Desk';
		}

//	print $msg;
		$message = new stdClass();
		$message->msg_type = $this->hl7->getMsgType();
		$message->message = $incomingMsg;
		$message->foreign_facility = $this->hl7->getSendingFacility();
		$message->foreign_application = $this->hl7->getSendingApplication();
		$message->foreign_address = $_SERVER['REMOTE_ADDR'];
		$message->isOutbound = '0';
		$message->status = '2';
		$message->date_processed = date('Y-m-d H:i:s');
		$message = $this->m->save($message);

		//print '<br>';
		//print_r($hl7->getSegment('PID')->data);
		//save message to database

		if($this->error === false){
			switch($this->hl7->getMsgType()){
				case 'ORU':

					$po = MatchaModel::setSenchaModel('App.model.patient.PatientsOrders');
					$poRep = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderReports');
					$poRes = MatchaModel::setSenchaModel('App.model.patient.PatientsOrderResults');

					foreach($msg->PATIENT_RESULT AS $patient_result){
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
							$oData->lab_collected_date = $this->hl7->time($obr[7][1]);
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
								$result->date_rage_values = $this->hl7->time($obx[12][1]);
								$result->date_observation = $this->hl7->time($obx[14][1]);
								$result->observer = $obx[16][2][1] . ' ' . $obx[16][3];
								$result->date_analysis = $this->hl7->time($obx[19][1]);
								$result->notes = $note['3'];
								$result->resultsDoc = $message['data']['id'];

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

		$msa = $ack->addSegment('MSA');
		$msa->setValue('1','AA');                   // AA = Positive acknowledgment, AE = Application error, AR = Application reject
		$msa->setValue('2', $this->hl7->getMsgControlId());  // Message Control ID from MSH
		$msa->setValue('3', '');            // Error Message

		return "\v".$ack->getMessage().chr(0x1c).chr(0x0d);
	}
}



$address = '127.0.0.1';
$port = 8181;
//
set_time_limit(0);
// Ensure that every time we call "echo", the data is sent to the browser
// IMMEDIATELY, rather than when PHP feels like it
ob_implicit_flush();

// Normally when the user clicks the "Stop" button in their browser, the
// script is terminated. This line stops that happening, so that we can
// detect the Stop button ourselves and properly close our sockets (to
// prevent the listening socket remaining open and stealing the port)
ignore_user_abort(true);

// Define a function that we can call when any of our socket function calls
// fail. This allows us to consolidate our error message XHTML and avoid
// code repetition. If $die is set to true, the script will terminate
function socketError($errorFunction, $die=false) {
	$errMsg = socket_strerror(socket_last_error());

	// This odd construct (known as a heredoc) just echos all of the text
	// between "<<<EOHTML" and "EOHTML;". It's just a neater and easier to read
	// format than using standard quoted strings. If you want to use one
	// yourself, bear in mind that the structure is VERY strict: the opening
	// line must be just "<<<" followed by the ending identifier, and the last
	// line must contain NOTHING except the identifier ("EOHTML" in this case).
	// The semi-colon after the closing identifier is optional, but it is
	// important to realise that there cannot even be whitespace (tabs or
	// spaces) before the EOHTML; at the end!!
	echo <<<EOHTML
<div class="error">
<h1>$errorFunction() failed!</h1>
<p>
	<strong>Error Message:</strong>
	<span>$errMsg</span>
</p>
<p>Note that if you have recently pressed your browser's Stop or
 Refresh/Reload button on this server script, you may have to wait a few
 seconds for the old server to release its listening port. As such, wait
 and try again in a few seconds.
</p>
</div>
EOHTML;

	if ($die) {
		// Close the BODY and HTML tags as well as terminating script
		// execution because the die() call prevents us ever getting to the last
		// lines of this script
		die('</body></html>');
	}
}

// Attempt to create our socket. The "@" hides PHP's standard error reporting,
// so that we can output our own error message if it fails
if (!($server = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
	socketError('socket_create', true);
}

// Set the "Reuse Address" socket option to enabled
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);

// Attempt to bind our socket to the address and port that we're listening on.
// Again, we suppress PHP's error reporting in favour of our own
if (!@socket_bind($server, $address, $port)) {
	socketError('socket_bind', true);
}

// Start listening on the address and port that we bound our socket to above,
// using our own error reporting code as before
if (!@socket_listen($server)) {
	socketError('socket_listen', true);
}

// Create an array to store our sockets in. We use this so that we can
// determine which socket has new incoming data with the "socket_select()"
// function, and to properly close each socket when the script finishes
$allSockets = array($server);
$null = null;
// Start looping indefinitely. On each iteration we will make sure the browser's
// "Stop" button hasn't been pressed and, if not, see if we have any incoming
// client connection requests or any incoming data on existing clients
while (true) {
	//We have to echo something to the browser or PHP won't know if the Stop
	// button has been pressed
	if (connection_aborted()) {
		//The Stop button has been pressed, so close all our sockets and exit
		foreach ($allSockets as $socket) {
			socket_close($socket);
		}

		//Now break out of this while() loop!
		break;
	}

	// socket_select() is slightly strange. You have to make a copy of the array
	// of sockets you pass to it, because it changes that array when it returns
	// and the resulting array will only contain sockets with waiting data on
	// them. $write and $except are set to NULL because we aren't interested in
	// them. The last parameter indicates that socket_select will return after
	// that many seconds if no data is receiveed in that time; this prevents the
	// script hanging forever at this point (remember, we might want to accept a
	// new connection or even exit entirely) and also pauses the script briefly
	// to prevent this tight while() loop using a lot of processor time
	$changedSockets = $allSockets;

	socket_select($changedSockets, $null, $null, 5);

	// Now we loop over each of the sockets that socket_select() says have new
	// data on them
	foreach($changedSockets as $socket) {
		if ($socket == $server) {
			// socket_select() will include our server socket in the
			// $changedSockets array if there is an incoming connection attempt
			// on it. This will only accept one incoming connection per while()
			// loop iteration, but that shouldn't be a problem given the
			// frequency that we're iterating
			if (!($client = @socket_accept($server))) {
				//socket_accept() failed for some reason (again, we hid PHP's
				// standard error message), so let's say what happened...
				socketError('socket_accept', false);
			} else {
				//We've accepted the incoming connection, so add the new client
				// socket to our array of sockets
				$allSockets[] = $client;
			}
		} else {
			//Attempt to read data from this socket
			$data = socket_read($socket, 1024*10);
			if ($data === false || $data === '') {
				//socket_read() returned FALSE, meaning that the client has
				// closed the connection. Therefore we need to remove this
				// socket from our client sockets array and close the socket
				//A potential bug in PHP means that socket_read() will return
				// an empty string instead of FALSE when the connection has
				// been closed, contrary to what the documentation states. As
				// such, we look for FALSE or an empty string (an empty string
				// for the current, buggy, behaviour, and FALSE in case it ends
				// up getting fixed at some point)
				unset($allSockets[array_search($socket, $allSockets)]);
				socket_close($socket);
			} else {
				$foo = new MsgHandler();
				$ack = $foo->Process($data);
				socket_write($socket, $ack, strlen($ack));
			}
		}
	}
}



