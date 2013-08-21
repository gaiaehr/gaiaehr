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

include_once('../lib/HL7/HL7.php');
include_once('../classes/MatchaHelper.php');
new MatchaHelper();
$hl7 = new HL7();
$m = MatchaModel::setSenchaModel('App.model.administration.HL7Messages');
$r = MatchaModel::setSenchaModel('App.model.administration.HL7Recipients');
$o = MatchaModel::setSenchaModel('App.model.patient.PatientsObservations');
$error = false;

print '<pre>';

$recipient = $r->load(array('recipient' => $_SERVER['REMOTE_ADDR']))->one();
if($recipient === false){
	$error = 'IP Address Not Authorized';
}

$rawMsg = <<<EOF
MSH|^~\&|EHR Application^2.16.840.1.113883.3.72.7.1^HL7|EHR Facility^2.16.840.1.113883.3.72.7.2^HL7|PH Application^2.16.840.1.113883.3.72.7.3^HL7|PH Facility^2.16.840.1.113883.3.72.7.4^HL7|20110316102334||ORU^R01^ORU_R01|NIST-110316102333943|P|2.5.1|||||||||PHLabReport-Ack^^2.16.840.1.114222.4.10.3^ISO
SFT|NIST Lab, Inc.|3.6.23|A-1 Lab System|6742873-12||20080303
PID|||686774009^^^MPI&2.16.840.1.113883.19.3.2.1&ISO^MR||Takamura^Michael||19820815|M||2028-9^Asian^HL70005|3567 Maple Street^^Oakland^CA^94605^USA^M||^PRN^^^^510^6658876|||||||||N^Not Hispanic or Latino^HL70189
OBR|1||7564832^Lab^2.16.840.1.113883.19.3.1.6^ISO|10676-5^Hepatitis C Virus RNA^LN^1198112^Hepatitis C Test^99USI|||201007281400||||||Nausea, vomiting, abdominal pain|||1234^Admit^Alan^^^^^^ABC Medical Center&2.16.840.1.113883.19.4.6&ISO||||||201007301500|||F||||||787.01^Nausea and vomiting^I9CDX~789.0^Abdominal pain^I9CDX
OBX|1|NM|10676-5^Hepatitis C Virus RNA^LN|1|850000|iU/mL^international units per mililiter^UCUM|High Viral Load > or = 850000iU/mL|H|||F|||201007281400|||||200807301500||||Lab^L^^^^CLIA&2.16.840.1.113883.19.4.6&ISO^XX^^^1236|3434 Industrial Lane^^Ann Arbor^MI^48103^^B
SPM||||122555007^Venous blood specimen^SCT^BLDV^Blood venous^HL70487^20080131^2.5.1
EOF;

$msg = $hl7->readMessage($rawMsg);

$message = new stdClass();
$message->msg_type = $hl7->getMsgType();
$message->message = $rawMsg;
$message->foreign_facility = $hl7->getSendingFacility();
$message->foreign_application = $hl7->getSendingApplication();
$message->foreign_address = $_SERVER['REMOTE_ADDR'];
$message->isOutbound = '0';
$message->status = '2';
$message->date_processed = date('Y-m-d H:i:s');
$message = $m->save($message);

//print '<br>';

//print_r($hl7->getSegment('PID')->data);
// save message to database

if($error === false){
	switch($hl7->getMsgType()){
		case 'ORU':

			print_r($msg);


			break;
		default:


			break;
	}

}
