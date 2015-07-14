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

class ORU extends Message {

	function __construct($hl7) {
		parent::__construct($hl7);
	}

	function __destruct() {
		parent::__destruct();
	}

	protected function Events($event) {
		$events = array(
			'R01' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'PATIENT_RESULT' => array(
					'required' => true,
					'repeatable' => true,
					'items' => array(
						'PATIENT' => array(
							'items' => array(
								'PID' => array('required' => true),
								'PD1' => array('repeatable' => true),
								'NTE' => array('repeatable' => true),
								'NK1' => array('repeatable' => true),
								'VISIT' => array(
									'items' => array(
										'PV1' => array('required' => true),
										'PV2' => array(),
									)
								)
							),
						),
						'ORDER_OBSERVATION' => array(
							'required' => true,
							'repeatable' => true,
							'items' => array(
								'ORC' => array(),
								'OBR' => array('required' => true),
								'NTE' => array('repeatable' => true),
								'TIMING_QTY' => array(
									'repeatable' => true,
									'items' => array(
										'TQ1' => array('required' => true),
										'TQ2' => array('repeatable' => true)
									)
								),
								'CTD' => array(),
								'OBSERVATION' => array(
									'repeatable' => true,
									'items' => array(
										'OBX' => array('required' => true),
										'NTE' => array('repeatable' => true)
									)

								),
								'FT1' => array('repeatable' => true),
								'CTI' => array('repeatable' => true),
								'SPECIMEN' => array(
									'required' => true,
									'items' => array(
										'SPM' => array('required' => true),
										'OBX' => array('repeatable' => true)
									)

								)
							)

						)
					)
				)
			),
			'R21' => array(
				'MSH' => array('required' => true),
				'SFT' => array('repeatable' => true),
				'NTE' => array(),
				'PATIENT' => array(
					'items' => array(
						'PID' => array('required' => true),
						'PDI' => array(),
						'NTE' => array('repeatable' => true),
						'VISIT' => array(

							'PV1' => array('required' => true),
							'PV2' => array(),
						)
					)
				),
				'ORDER_OBSERVATION' => array(
					'required' => true,
					'repeatable' => true,
					'items' => array(
						'CONTAINER' => array(
							'SAC' => array('required' => true),
							'SID' => array()
						),
						'ORC' => array(),
						'OBR' => array('required' => true),
						'NTE' => array('repeatable' => true),
						'TIMING_QTY' => array(
							'repeatable' => true,
							'items' => array(
								'TQ1' => '',
								'TQ2' => ''
							)

						),
						'CTD' => '',
						'OBSERVATION' => array(
							'required' => true,
							'repeatable' => true,
							'items' => array(
								'OBX' => array(),
								'TCD' => array(),
								'SID' => array('repeatable' => true),
								'NTE' => array('repeatable' => true)
							)

						),
						'CTI' => array('repeatable' => true),
					)
				),
				'DSC' => array()
			)

		);

		return $events[$event];
	}
}