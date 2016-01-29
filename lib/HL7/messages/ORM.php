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

class ORM extends Message {

	function __construct($hl7) {
		parent::__construct($hl7);
	}

	function __destruct() {
		parent::__destruct();
	}

	protected function Events($event) {
		$events = array(
			'O01' => array(
				'MSH' => array('required' => true),
				'NTE' => array('repeatable' => true),
				'PATIENT' => array(
					'items' => array(
						'PID' => array('required' => true),
						'PD1' => array(),
						'NTE' => array('repeatable' => true),
						'PATIENT_VISIT' => array(
							'items' => array(
								'PV1' => array('required' => true),
								'PV2' => array()
							),
						),
						'INSURANCE' => array(
							'repeatable' => true,
							'items' => array(
								'IN1' => array('required' => true),
								'IN2' => array(),
								'IN3' => array()
							)
						),
						'GT1' => array(),
						'AL1' => array('repeatable' => true),
					)
				),
				'ORDER' => array(
					'repeatable' => true,
					'items' => array(
						'ORC' => array('required' => true),
						'ORDER_DETAIL' => array(
							'items' => array(
								'OBR' => array('required' => true), // OBR|RQD|QR1|RXO|ODS|ODT
								'NTE' => array('repeatable' => true),
								'CTD' => array(),
								'DG1' => array('repeatable' => true),
								'ORDER_DETAIL' => array(
									'items' => array(
										'OBR' => array('required' => true),
										'NTE' => array('repeatable' => true),
										'CTD' => array(),
										'DG1' => array('repeatable' => true),
									    'OBSERVATION' => array(
										    'items' => array(
											    'OBX' => array('required' => true),
											    'NTE' => array('repeatable' => true),
										    )
									    )
									)
								)
							),
						),
						'FT1' => array('repeatable' => true),
						'CTI' => array('repeatable' => true),
						'BLG' => array(),
					)
				)
			)
		);

		return $events[$event];
	}
}