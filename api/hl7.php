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

//OBX|2|NM|JTRIG^Triglyceride (CAD)|1|72|CD:289^mg/dL|35-150^35^150|""||""|F|||20080511103500|||^^^""|
//OBX|3|NM|JVLDL^VLDL-C (calc - CAD)|1|14|CD:289^mg/dL||""||""|F|||20080511103500|||^^^""|
//OBX|4|NM|JLDL^LDL-C (calc - CAD)|1|134|CD:289^mg/dL|0-100^0^100|H||""|F|||20080511103500|||^^^""|
//OBX|5|NM|JCHO^Cholesterol (CAD)|1|210|CD:289^mg/dL|90-200^90^200|H||""|F|||20080511103500|||^^^""|


//OBR|1||185L29839X64489JLPF~X64489^ACC_NUM|JLPF^Lipid Panel - C||||||||||||1694^DOCLAST^DOCFIRST^^MD||||||20080511103529|||
//OBX|1|NM|JHDL^HDL Cholesterol (CAD)|1|62|CD:289^mg/dL|>40^>40|""||""|F|||20080511103500|||^^^""|

$msg = <<<EOF
MSH|^~\&|XXXX|C|PRIORITYHEALTH|PRIORITYHEALTH|20080511103530||ORU^R01|Q335939501T337311002|P|2.3|||
PID|1||94000000000^^^Priority Health 1&Priority Health 2||LASTNAME^FIRSTNAME^INIT||19460101|M|||||
EOF;
print '<pre>';

print_r($msg.PHP_EOL);
$hl7->readMessage($msg);
print_r($hl7->segments);



