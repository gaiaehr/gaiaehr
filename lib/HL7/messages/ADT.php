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
include_once(dirname(__FILE__) . '/Message.php');

class ADT extends Message {

	function __construct($hl7) {
		parent::__construct($hl7);
	}

	function __destruct() {
		parent::__destruct();
	}

	protected function Events($event) {
		$events = array(
			/** ADT/ACK - Admit/Visit Notification (Event A01) */
			'A01' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'ROL' => array('repeatable' => true),
				'NK1' => array('repeatable' => true),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true),
				'AL1' => array('repeatable' => true),
				'DG1' => array(
					'repeatable' => true,
					'DRG' => array(),
					'PROCEDURE' => array(
						'repeatable' => true,
						'items' => array(
							'PR1' => array('required' => true),
							'ROL' => array('repeatable' => true),
						)
					),
					'GT1' => array('repeatable' => true),
					'INSURANCE' => array(
						'repeatable' => true,
						'items' => array(
							'IN1' => array('required' => true),
							'IN2' => array(),
							'IN3' => array('repeatable' => true),
							'ROL' => array('repeatable' => true),
						)
					),
					'ACC' => array(),
					'UB1' => array(),
					'UB2' => array(),
					'PDA' => array()
				),
			),
			/** ADT/ACK - Register a Patient (Event A04) */
			'A04' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'ROL' => array('repeatable' => true),
				'NK1' => array('repeatable' => true),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true),
				'AL1' => array('repeatable' => true),
				'DG1' => array('repeatable' => true),
				'DRG' => array(),
				'PROCEDURE' => array(
					'repeatable' => true,
					'items' => array(
						'PR1' => array('required' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'GT1' => array('repeatable' => true),
				'INSURANCE' => array(
					'repeatable' => true,
					'items' => array(
						'IN1' => array('required' => true),
						'IN2' => array(),
						'IN3' => array('repeatable' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'ACC' => array(),
				'UB1' => array(),
				'UB2' => array(),
				'PDA' => array()
			),
			/** ADT/ACK - Update Patient Information (Event A08) */
			'A08' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'ROL' => array('repeatable' => true),
				'NK1' => array('repeatable' => true),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true),
				'AL1' => array('repeatable' => true),
				'DG1' => array('repeatable' => true),
				'DRG' => array(),
				'PROCEDURE' => array(
					'repeatable' => true,
					'items' => array(
						'PR1' => array('required' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'GT1' => array('repeatable' => true),
				'INSURANCE' => array(
					'repeatable' => true,
					'items' => array(
						'IN1' => array('required' => true),
						'IN2' => array(),
						'IN3' => array('repeatable' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'ACC' => array(),
				'UB1' => array(),
				'UB2' => array(),
				'PDA' => array()
			),
			/** ADT/ACK - Patient Departing - Tracking (Event A09) */
			'A09' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true),
				'DG1' => array('repeatable' => true)
			),
			/** ADT/ACK - Patient Arriving - Tracking (Event A10) */
			'A10' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true),
				'DG1' => array('repeatable' => true)
			),
			/** ADT/ACK - Merge Patient Information (Event A18) */
			'A18' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PDI' => array(),
				'MRG' => array('required' => true),
				'PV1' => array('required' => true),
			),
			/** ADT/ACK - Add Person or Patient Information (Event A28) */
			'A28' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'ROL' => array('repeatable' => true),
				'NK1' => array('repeatable' => true),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true),
				'AL1' => array('repeatable' => true),
				'DG1' => array('repeatable' => true),
				'DRG' => array(),
				'PROCEDURE' => array(
					'repeatable' => true,
					'items' => array(
						'PR1' => array('required' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'GT1' => array('repeatable' => true),
				'INSURANCE' => array(
					'repeatable' => true,
					'items' => array(
						'IN1' => array('required' => true),
						'IN2' => array(),
						'IN3' => array('repeatable' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'ACC' => array(),
				'UB1' => array(),
				'UB2' => array()
			),
			/** ADT/ACK - Delete Person Information (Event A29) */
			'A29' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true)
			),
			/** ADT/ACK - Update Person Information (Event A31) */
			'A31' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'ROL' => array('repeatable' => true),
				'NK1' => array('repeatable' => true),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true),
				'AL1' => array('repeatable' => true),
				'DG1' => array('repeatable' => true),
				'DRG' => array(),
				'PROCEDURE' => array(
					'repeatable' => true,
					'items' => array(
						'PR1' => array('required' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'GT1' => array('repeatable' => true),
				'INSURANCE' => array(
					'repeatable' => true,
					'items' => array(
						'IN1' => array('required' => true),
						'IN2' => array(),
						'IN3' => array('repeatable' => true),
						'ROL' => array('repeatable' => true),
					)
				),
				'ACC' => array(),
				'UB1' => array(),
				'UB2' => array()
			),
			/** ADT/ACK - Cancel Patient Arriving - Tracking (Event A32) */
			'A32' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true)
			),
			/** ADT/ACK - Cancel Patient Departing - Tracking (Event A33) */
			'A33' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PID' => array('required' => true),
				'PD1' => array(),
				'PV1' => array('required' => true),
				'PV2' => array(),
				'DB1' => array('repeatable' => true),
				'OBX' => array('repeatable' => true)
			),
			/** ADT/ACK - Merge Person - Patient ID (Event A39) */
			'A39' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PATIENT' => array(
					'PID' => array('required' => true),
					'PD1' => array(),
					'MRG' => array('required' => true),
					'PV1' => array()
				)
			),
			/** ADT/ACK - Merge Patient - Patient Identifier List (Event A40) */
			'A40' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PATIENT' => array(
					'PID' => array('required' => true),
					'PD1' => array(),
					'MRG' => array('required' => true),
					'PV1' => array()
				)
			),
			/** ADT/ACK - Merge Account - Patient Account Number (Event A41) */
			'A41' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'EVN' => array('required' => true),
				'PATIENT' => array(
					'PID' => array('required' => true),
					'PD1' => array(),
					'MRG' => array('required' => true),
					'PV1' => array()
				)
			)
		);

		return $events[$event];
	}
}