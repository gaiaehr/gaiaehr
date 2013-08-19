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

class ORU {

	public $_01 = array(
		'MSH' => '',
		'SFT' => '',
		'PATIENT_RESULT' => array(
			'PATIENT' => array(
				'PID' => '',
				'PDI' => '',
				'NTE' => '',
				'NK1' => '',
				'VISIT' => array(
					'PV1' => '',
					'PV2' => '',
				)
			),
			'ORDER_OBSERVATION' => array(
				'ORC' => '',
				'OBR' => '',
				'NTE' => '',
				'TIMING_QTY' => array(
					'TQ1' => '',
					'TQ2' => ''
				),
				'CTD' => '',
				'OBSERVATION' => array(
					'OBX' => '',
					'NTE' => ''
				),
				'FT1' => '',
				'CTI' => '',
				'SPECIMEN' => array(
					'SPM' => '',
					'OBX' => ''
				)
			)
		)
	);

	public $_21 = array(
		'MSH' => '',
		'SFT' => '',
		'NTE' => '',
		'PATIENT' => array(
			'PID' => '',
			'PDI' => '',
			'NTE' => '',
			'VISIT' => array(
				'PV1' => '',
				'PV2' => '',
			)
		),
		'ORDER_OBSERVATION' => array(
			'CONTAINER' => array(
				'SAC' => '',
				'SID' => ''
			),
			'ORC' => '',
			'OBR' => '',
			'NTE' => '',
			'TIMING_QTY' => array(
				'TQ1' => '',
				'TQ2' => ''
			),
			'CTD' => '',
			'OBSERVATION' => array(
				'OBX' => '',
				'TCD' => '',
				'SID' => '',
				'NTE' => ''
			),
			'CTI' => '',
			'SPECIMEN' => array(
				'SPM' => '',
				'OBX' => ''
			)
		),
		'DSC' => ''
	);
}