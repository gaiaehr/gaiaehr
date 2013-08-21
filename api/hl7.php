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

$msg = <<<EOF
MSH|^~\&|NIST^2.16.840.1.113883.3.72.0^ISO|NIST^2.16.840.1.113883.3.72.5.21^ISO|NIST^2.16.840.1.113883.3.72.5.22^ISO|NIST^2.16.840.1.113883.3.72.5.23^ISO|20120821140551-0500||ORU^R01^ORU_R01|NIST-ELR-004.01|T|2.5.1|||NE|NE|||||PHLabReport-NoAck^HL7^2.16.840.1.113883.9.11^ISO
SFT|NIST Lab, Inc.^L^^^^NIST&2.16.840.1.113883.3.987.1&ISO^XX^^^123544|3.6.23|A-1 Lab System|6742873-12||20100617
PID|1||PATID1234^^^&2.16.840.1.113883.3.72.5.24&ISO^MR^Seminole Cnty Hlth C&2.16.840.1.113883.3.0&ISO||Jones^William^A^^^^L||19610615|M||2106-3^White^CDCREC|1955 Seminole Lane^^Oveido^FL^32765^USA^H^^12059||^PRN^PH^^1^407^2351234|||||||||N^Not Hispanic or Latino^HL70189^NL^not latino^L
ORC|RE|000-222-4^^2.16.840.1.113883.3.72.5.24^ISO|R-783274-4^LIS^2.16.840.1.113883.3.72.5.25^ISO|||||||||57422^RADON^NICHOLAS^^^Dr.^^^NPI&2.16.840.1.113883.4.6&ISO^L^^^NPI||^PRN^PH^^^407^2341212|||||||Seminole County Health Clinic|555 Orange Ave^^Oviedo^FL^32765^^B|^WPN^PH^^^813^8847284|555 Orange Ave^^Oviedo^FL^32765^^B
OBR|1|000-723222-4^^2.16.840.1.113883.3.72.5.24^ISO|R-783274-4^LIS^2.16.840.1.113883.3.72.5.25^ISO|625-4^Bacteria identified in Stool by Culture^LN^3456543^CULTURE STOOL^99USI^2.40|||20110528|||||||||57422^RADON^NICHOLAS^^^Dr.^^^NPI&2.16.840.1.113883.4.6&ISO^L^^^NPI|^PRN^PH^^^407^2341212|||||201106010900-0500|||F
OBX|1|000|625-4^Bacteria identified in Stool by Culture^LN^Bacteria identified^Bacteria identified^99USI||85729005^Shigella flexneri^SCT^^^^^^Shigella flexneri||||||F|||20110528|||||20110531130655-0500||||Seminole County Health Department Laboratory^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|6756 Florida Avenue^^Oveido^FL^32765^^B|10092^Pafford^Hamlin^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI
SPM|1|000-4&&2.16.840.1.113883.3.72.5.24&ISO||119339001^Stool specimen^SCT^^^^07/31/2012|||||||||||||20110528|20110529
ORC|RE|111-723222-4^^2.16.840.1.113883.3.72.5.24^ISO|R-783274-4^LIS^2.16.840.1.113883.3.72.5.25^ISO|||||||||57422^RADON^NICHOLAS^^^Dr.^^^NPI&2.16.840.1.113883.4.6&ISO^L^^^NPI||^PRN^PH^^^407^2341212|||||||Seminole County Health Clinic|555 Orange Ave^^Oviedo^FL^32765^^B|^WPN^PH^^^813^8847284|555 Orange Ave^^Oviedo^FL^32765^^B
OBR|2|111|1-783274-5^LIS^2.16.840.1.113883.3.72.5.25^ISO|50545-3^Bacterial susceptibility panel in Isolate by Minimum inhibitory concentration (MIC)^LN^Bact suscept^Bacteria susceptibility^99USI^2.40|||20110528|||||||||57422^RADON^NICHOLAS^^^Dr.^^^NPI&2.16.840.1.113883.4.6&ISO^L^^^NPI|^PRN^PH^^^407^2341212|||||201106010900-0500|||F|625-4&Bacteria identified in Stool by Culture&LN&Bacteria identified&Bacteria identified&99USI^^Shigella flexneri|||^R-783274-4&LIS&2.16.840.1.113883.3.72.5.25&ISO
OBX|1|111|20-8^Amoxicillin+Clavulanate [Susceptibility] by Minimum inhibitory concentration (MIC)^LN^AmoxClav^Amoxicillin-clavulanic acid^99USI^2.40||=^16|ug/mL^microgram per milliliter^UCUM^^^^1.8.2||I^Intermediate^HL70078^^^^2.5.1|||F|||20110528|||||201106010900-0500||||Seminole County Health Department Laboratory^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|6756 Florida Avenue^^Oveido^FL^32765^^B|10092^Pafford^Hamlin^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI
OBX|2|111|516-5^Trimethoprim+Sulfamethoxazole [Susceptibility] by Minimum inhibitory concentration (MIC)^LN^TMP-SMX^Trimethoprim-sulfamethoxazole^99USI^2.40||=^8^/^152|ug/mL^microgram per milliliter^UCUM^^^^1.8.2||R^Resistant^HL70078^^^^2.5.1|||F|||20110528|||||201106010900-0500||||Seminole County Health Department Laboratory^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|6756 Florida Avenue^^Oveido^FL^32765^^B|10092^Pafford^Hamlin^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI
OBX|3|111|185-9^Ciprofloxacin [Susceptibility] by Minimum inhibitory concentration (MIC)^LN^CIPROFLOXACIN^CIPROFLOXACIN^99USI^2.40||<=^0.06|ug/mL^microgram per milliliter^UCUM^^^^1.8.2||S^Susceptible^HL70078^^^^2.5.1|||F|||20110528|||||201106010900-0500||||Seminole County Health Department Laboratory^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^XX^^^987|6756 Florida Avenue^^Oveido^FL^32765^^B|10092^Pafford^Hamlin^^^^^^&2.16.840.1.113883.3.72.5.30.1&ISO^L^^^NPI
SPM|1|111^ORD723222-4.1&&2.16.840.1.113883.3.72.5.24&ISO||119303007^Microbial isolate specimen^SCT^^^^07/31/2012|||||||||||||20110528|20110529
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

//print_r($hl7->getSegment('PID')->data);
// save message to database

if($error === false){
//	switch($hl7->getMsgType()){
//		case 'ORU':
//
//			$pid = $hl7->getSegment('PID');
//			$orc = $hl7->getSegment('ORC');
//			$obrs = $orc->getChildren('OBR');
//			foreach($obrs AS $obr){
//
//				$obxs = $obr->getChildren('OBX', true);
//
//				// for each observation
//				foreach($obxs AS $obx){
//					// for each notes
//					foreach($obx->getChildren('NTE') AS $nte){
////						print_r($obx->data);
////						print_r($nte->data);
//					}
//				}
//
//				print_r(count($obr->data));
////				$obx = $hl7->getSegments(array('ORC'=>'OBX'));
////				print_r($orc);
//
//			}
//
//
//
//
//
//			break;
//		default:
//
//
//			break;
//	}

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






