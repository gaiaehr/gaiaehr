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
$error = false;

print '<pre>';

$recipient = $r->load(array('recipient' => $_SERVER['REMOTE_ADDR']))->one();
if($recipient === false){
	$error = 'IP Address Not Authorized';
}

$msg = <<<EOF
MSH|^~\&|EHR Application^2.16.840.1.113883.3.72.7.1^HL7|EHR Facility^2.16.840.1.113883.3.72.7.2^HL7|PH Application^2.16.840.1.113883.3.72.7.3^HL7|PH Facility^2.16.840.1.113883.3.72.7.4^HL7|20110316102013||ORU^R01^ORU_R01|NIST-110316102013209|P|2.5.1|||||||||PHLabReport-Ack^^2.16.840.1.114222.4.10.3^ISO
SFT|NIST Lab, Inc.|3.6.23|A-1 Lab System|6742873-12||20080303
PID|||9817566735^^^MPI&2.16.840.1.113883.19.3.2.1&ISO^MR||Johnson^Philip||20070526|M||2106-3^White^HL70005|3345 Elm Street^^Aurora^Colorado^80011^USA^M||^PRN^^^^303^5548889|||||||||N^Not Hispanic or Latino^HL70189
ORC|RE|||||||||||1234^Admit^Alan^^^^^^ABC Medical Center&2.16.840.1.113883.19.4.6&ISO|||||||||Level Seven Healthcare^L^^^^ABC Medical Center&2.16.840.1.113883.19.4.6&ISO^XX^^^1234|1005 Healthcare Drive^^Ann Arbor^MI^48103^^B|^^^^^734^5553001|4444 Healthcare Drive^^Ann Arbor^MI^48103^^B
OBR|1||9700123^Lab^2.16.840.1.113883.19.3.1.6^ISO|10368-9^Lead BldC-mCnc^LN^3456543^Blood lead test^99USI|||200808151030-0700||||||Diarrhea|||1234^Admit^Alan^^^^^^ABC Medical Center&2.16.840.1.113883.19.4.6&ISO||||||200808181800-0700|||F||||||787.91^DIARRHEA^I9CDX
OBX|1|NM|10368-9^Lead BldC-mCnc^LN|1|50|ug/dL^micro-gram per deci-liter^UCUM|<9 mcg/dL:  Acceptable background lead exposure|H|||F|||200808151030-0700|||||200808181800-0700||||Lab^L^^^^CLIA&2.16.840.1.113883.19.4.6&ISO^XX^^^1236|3434 Industrial Lane^^Ann Arbor^MI^48103^^B
OBX|2|NM|10368-9^Lead BldC-mCnc^LN|1|50|ug/dL^micro-gram per deci-liter^UCUM|<9 mcg/dL:  Acceptable background lead exposure|H|||F|||200808151030-0700|||||200808181800-0700||||Lab^L^^^^CLIA&2.16.840.1.113883.19.4.6&ISO^XX^^^1236|3434 Industrial Lane^^Ann Arbor^MI^48103^^B
OBX|3|NM|10368-9^Lead BldC-mCnc^LN|1|50|ug/dL^micro-gram per deci-liter^UCUM|<9 mcg/dL:  Acceptable background lead exposure|H|||F|||200808151030-0700|||||200808181800-0700||||Lab^L^^^^CLIA&2.16.840.1.113883.19.4.6&ISO^XX^^^1236|3434 Industrial Lane^^Ann Arbor^MI^48103^^B
SFT|NIST Lab, Inc.|3.6.23|A-1 Lab System|6742873-12||20080303
EOF;
$hl7->readMessage($msg);

$message = new stdClass();
$message->msg_type = $hl7->getMsgType();
$message->message = $msg;
$message->foreign_facility = $hl7->getSendingFacility();
$message->foreign_application = $hl7->getSendingApplication();
$message->foreign_address = $_SERVER['REMOTE_ADDR'];
$message->isOutbound = '0';
$message->status = '2';
$message->date_processed = date('Y-m-d H:i:s');
$message = $m->save($message);

//print '<br>';

//print_r($hl7->segments);
// save message to database

if($error === false){
	switch($hl7->getMsgType()){

		case 'ORU':

			$pid = $hl7->getSegment('PID');
			$sft = $hl7->getSegment('SFT');
			$orc = $hl7->getSegment('ORC');
//			print_r($hl7->getSegment('OBR'));
			print_r($hl7->getSegment('OBR')->getChildren('OBX'));

//			$obx = $hl7->getSegments(array('ORC'=>'OBX'));


//			print_r($orc);





			break;
		default:


			break;
	}

}



//print_r($hl7->getMsgSecurity());
//print '<br>';
//print_r($hl7->getMsgControlId());
//print '<br>';
//print_r($hl7->getMsgProcessingId());
//print '<br>';
//print_r($hl7->getMsgVersionId());

//$pid = $hl7->getSegment('PID');
//print_r($pid[3][4][1]);
//print_r($pid);

//print_r($hl7->segments);






