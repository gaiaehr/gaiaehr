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

class SIU extends Message {

	function __construct($hl7) {
		parent::__construct($hl7);
	}

	function __destruct() {
		parent::__destruct();
	}

	protected function Events($event) {

		$events['S12'] =
		$events['S13'] =
		$events['S14'] =
		$events['S15'] =
		$events['S16'] =
		$events['S17'] =
		$events['S18'] =
		$events['S19'] =
		$events['S20'] =
		$events['S21'] =
		$events['S22'] =
		$events['S23'] =
		$events['S24'] =
		$events['S26'] = array(
			'S12' => array(
				'MSH' => array('required' => true),
				'SCH' => array('required' => true),
				'TQ1' => array('repeatable' => true),
				'NTE' => array('repeatable' => true),
				'PATIENT' => array(
					'repeatable' => true,
					'items' => array(
						'PID' => array('required' => true),
						'PD1' => array(),
						'PV1' => array(),
						'PV2' => array(),
						'OBX' => array('repeatable' => true),
						'DG1' => array('repeatable' => true),
					)
				),
				'RESOURCES' => array(
					'RGS' => array('required' => true),
					'SERVICE' => array(
						'repeatable' => true,
						'items' => array(
							'AIS' => array('required' => true),
							'NTE' => array()
						)
					),
					'GENERAL_RESOURCE' => array(
						'repeatable' => true,
						'items' => array(
							'AIG' => array('required' => true),
							'NTE' => array()
						)
					),
					'LOCATION_RESOURCE' => array(
						'repeatable' => true,
						'items' => array(
							'AIL' => array('required' => true),
							'NTE' => array()
						)
					),
					'PERSONNEL_RESOURCE' => array(
						'repeatable' => true,
						'items' => array(
							'AIP' => array('required' => true),
							'NTE' => array()
						)
					)
				)
			)
		);

		return $events[$event];
	}
}