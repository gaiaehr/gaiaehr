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
class CQMs {










	private function getCodeSet($set, $codeType) {



		$Sets = array(

			//Diagnosis, Active: Limited Life Expectancy
			'2168401113883352631259' => array(
				'SNOMEDCT' => array(
					162607003,
					162608008,
					170969009,
					27143004,
					300936002
				)
			),

			//Encounter, Performed: Annual Wellness Visit
			'2168401113883352631240' => array(
				'HCPCS' => array()
			),

			//Encounter, Performed: Face-to-Face Interaction
			'216840111388334641003101121048' => array(
				'SNOMEDCT' => array(
					12843005,
					18170008,
					185349003,
					185463005,
					185465003,
					19681004,
					207195004,
					270427003,
					270430005,
					308335008,
					390906007,
					406547006,
					439708006,
					87790002,
					90526000
				)
			),

			//Encounter, Performed: Health & Behavioral Assessment - Individual
			'2168401113883352631020' => array(
				'CPT' => array(
					96152
				)
			),

			//Encounter, Performed: Health and Behavioral Assessment - Initial
			'2168401113883352631245' => array(
				'CPT' => array(
					96150
				)
			),

			//Encounter, Performed: Occupational Therapy Evaluation
			'2168401113883352631011' => array(
				'CPT' => array(
					97003,
					97004
				)
			),

			//Encounter, Performed: Office Visit
			'216840111388334641003101121001' => array(
				'CPT' => array(
					99201,
					99202,
					99203,
					99204,
					99205,
					99212,
					99213,
					99214,
					99215
				)
			),

			//Encounter, Performed: Ophthalmological Services
			'2168401113883352631285' => array(
				'CPT' => array(
					92002,
					92004,
					92012,
					92014
				)
			),

			//Encounter, Performed: Preventive Care Services - Established Office Visit, 18 and Up
			'216840111388334641003101121025' => array(
				'CPT' => array(
					99395,
					99396,
					99397
				)
			),

			//Encounter, Performed: Preventive Care Services - Group Counseling
			'216840111388334641003101121027' => array(
				'CPT' => array(
					99411,
					99412
				)
			),

			//Encounter, Performed: Preventive Care Services - Other
			'216840111388334641003101121030' => array(
				'CPT' => array(
					99420,
					99429
				)
			),

			//Encounter, Performed: Preventive Care Services-Individual Counseling
			'216840111388334641003101121026' => array(
				'CPT' => array(
					99401,
					99402,
					99403,
					99404
				)
			),

			//Encounter, Performed: Preventive Care Services-Initial Office Visit, 18 and Up
			'216840111388334641003101121023' => array(
				'CPT' => array(
					99385,
					99386,
					99387
				)
			),

			//Encounter, Performed: Psych Visit - Diagnostic Evaluation
			'2168401113883352631492' => array(
				'CPT' => array(
					90791,
					90792
				)
			),

			//Encounter, Performed: Psych Visit - Psychotherapy
			'2168401113883352631496' => array(
				'CPT' => array(
					90832,
					90834,
					90837
				)
			),

			//Encounter, Performed: Psychoanalysis
			'2168401113883352631141' => array(
				'CPT' => array(
					90845
				)
			),

			//Intervention, Performed: Tobacco Use Cessation Counseling
			'216840111388335263509' => array(
				'SNOMEDCT' => array(
					171055003,
					185792005,
					185793000,
					185794006,
					185795007,
					185796008,
					225323000,
					225324006,
					310429001,
					315232003,
					384742004
				),
				'CPT' => array(
					99406,
					99407
				)
			),

			//Medication, Active: Tobacco Use Cessation Pharmacotherapy
			//Medication, Order: Tobacco Use Cessation Pharmacotherapy
			'2168401113883352631190' => array(
				'RXNORM' => array(
					1046847,
					1046858,
					151226,
					198029,
					198030,
					198031,
					198045,
					198046,
					198047,
					199888,
					199889,
					199890,
					205315,
					205316,
					250983,
					311972,
					311973,
					311975,
					312036,
					314119,
					317136,
					359817,
					359818,
					419168,
					636671,
					636676,
					749289,
					749788,
					892244,
					896100,
					993503,
					993518,
					993536,
					993541,
					993550,
					993557,
					993567,
					993681,
					998671,
					998675,
					998679
				)
			),

			//Patient Characteristic: Tobacco Non-User
			'2168401113883352631189' => array(
				'SNOMEDCT' => array(
					105539002,
					105540000,
					105541001,
					160618006,
					160620009,
					160621008,
					228501004,
					228502006,
					228503001,
					228512004,
					266919005,
					266921000,
					266922007,
					266923002,
					266924008,
					266925009,
					266928006,
					281018007,
					360890004,
					360900008,
					360918006,
					360929005,
					405746006,
					53896009,
					8392000,
					8517006,
					87739003
				)
			),

			//Patient Characteristic: Tobacco User
			'2168401113883352631170' => array(
				'SNOMEDCT' => array(
					160603005,
					160604004,
					160605003,
					160606002,
					160619003,
					228494002,
					228504007,
					228514003,
					228515002,
					228516001,
					228517005,
					228518000,
					230059006,
					230060001,
					230062009,
					230063004,
					230064005,
					230065006,
					266920004,
					428041000124106,
					428061000124105,
					428071000124103,
					449868002,
					59978006,
					65568007,
					77176002,
					81703003,
					82302008
				)
			)
		);

		/**
		 * return the set values is is found
		 */
		$set = str_replace('.', '', $set);

		if(isset($Sets[$set]) && isset($Sets[$set][$codeType])){
			return $Sets[$set][$codeType];
		}

		/**
		 * return false is not found
		 */
		return false;
	}

}

//print '<pre>';
//$c = new CQMs();

