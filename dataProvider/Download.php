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

// TODO: Make a better Download procedure for the HL7 Message
//include_once(ROOT . '/lib/HL7/HL7Message.php');
//
//$HL7 = new HL7Messages();
//$Parameters = new stdClass();
//$Parameters->pid = $_REQUEST['pid'];
//$Parameters->from = $_REQUEST['from'];
//$Parameters->to = $_REQUEST['to'];
//$HL7->sendVXU($Parameters);

header('Content-Description: HL7 File Download');
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename=' . $_REQUEST['pid'].'-'.date("Ymd").'.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
echo "Hi Download!";
//echo $HL7->saveMsg();