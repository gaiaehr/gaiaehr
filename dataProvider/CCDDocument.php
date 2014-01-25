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

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

//if(!isset($_REQUEST['token']) || $_REQUEST['token'] == $_SESSION['user']['token']) die('Not Authorized!');
if(!isset($_REQUEST['pid']))
	die('PID not send');
if(!isset($_REQUEST['action']))
	die('Action not send');

include_once(dirname(dirname(__FILE__)) . '/classes/MatchaHelper.php');
include_once(dirname(dirname(__FILE__)) . '/classes/UUID.php');
include_once(dirname(dirname(__FILE__)) . '/classes/Array2XML.php');

include_once(dirname(__FILE__) . '/Patient.php');
include_once(dirname(__FILE__) . '/User.php');
include_once(dirname(__FILE__) . '/Rxnorm.php');
include_once(dirname(__FILE__) . '/Encounter.php');
include_once(dirname(__FILE__) . '/PoolArea.php');
include_once(dirname(__FILE__) . '/Medical.php');
include_once(dirname(__FILE__) . '/PreventiveCare.php');
include_once(dirname(__FILE__) . '/Services.php');
include_once(dirname(__FILE__) . '/DiagnosisCodes.php');
include_once(dirname(__FILE__) . '/Facilities.php');
include_once(dirname(__FILE__) . '/CombosData.php');

$pid = $_REQUEST['pid'];
$dateNow = date('Ymd');
$timeNow = date('YmdHisO');


$Encounter = new Encounter();
$Medical = new Medical();
$Facilities = new Facilities();
$CombosData = new CombosData();
$facility = $Facilities->getFacility(true);
$body = array();

/**
 * CPT-4        2.16.840.1.113883.6.12  CPT-4
 * ICD-9 CM     2.16.840.1.113883.6.1   ICD-9 CM
 * LOINC        2.16.840.1.113883.6.1   LOINC
 * NDC          2.16.840.1.113883.6.6   NDC
 * RxNorm       2.16.840.1.113883.6.88  RxNorm
 * SNOMED CT    2.16.840.1.113883.6.96  SNOMED CT
 */
$codes = array(
	'CPT-4' => '2.16.840.1.113883.6.12',
	'ICD9' => '2.16.840.1.113883.6.1',
	'LOINC' => '2.16.840.1.113883.6.1',
	'NDC' => '2.16.840.1.113883.6.6',
	'RXNORM' => '2.16.840.1.113883.6.88',
	'SNOMED-CT' => '2.16.840.1.113883.6.96'
);


$requiredAllergies = true;
$requiredVitals = true;
$requiredImmunization = true;
$requiredMedications = true;
$requiredProblems = true;
$requiredProcedures = true;
$requiredPlanOfCare = true;
$requiredResults = true;


//region Patient Data

$Patient = new Patient();
$patientData = $Patient->getPatientDemographicDataByPid($pid);
$insuranceData = $Patient->getPatientPrimaryInsuranceByPid($pid);
unset($Patient);
$patient = array(
	'typeId' => array(
		'@attributes' => array(
			'root' => '2.16.840.1.113883.1.3',
			'extension' => 'POCD_HD000040'
		)
	),
	'patientRole' => array(
		'id' => array(
			'@attributes' => array(
				'extension' => $patientData['pid'],
				'root' => '2.16.840.1.113883.19.5'
			)
		),
		'patient' => array(
			'name' => array(
//				'@attributes' => array(
//					'use' => 'L'
//				),
				'given' => array(
					$patientData['fname'],
					$patientData['mname']
				),
				'family' => $patientData['lname'],
				'suffix' => array(
					'@attributes' => array(
						'qualifier' => 'TITLE'
					),
					'@value' => $patientData['title']

				),
			),
			'administrativeGenderCode' => array(
				'@attributes' => array(
					'code' => $patientData['sex'],  // values are M, F, or UM more info... http://phinvads.cdc.gov/vads/ViewValueSet.action?id=8DE75E17-176B-DE11-9B52-0015173D1785
					'codeSystem' => '2.16.840.1.113883.5.1'
				)
			),
			'birthTime' => array(
				'@attributes' => array(
					'value' => preg_replace('/(\d{4})-(\d{2})-(\d{2}) \d{2}:\d{2}:\d{2}/', '$1$2$3', $patientData['DOB'])
				)
			),
			'ethnicGroupCode' => array(
				'@attributes' => array(
					'code' => $patientData['ethnicity'] == 'H' ? '2135-2' : '2186-5',
					'displayName' => $CombosData->getDisplayValueByListIdAndOptionValue(59, $patientData['ethnicity']),
					'codeSystem' => '2.16.840.1.113883.6.238'
				)
			),
			'raceCode' => array(
				'@attributes' => array(
					'code' => $patientData['race'],
					'displayName' => $CombosData->getDisplayValueByListIdAndOptionValue(14, $patientData['race']),
					'codeSystem' => '2.16.840.1.113883.6.238'
				)
			)
		),
		'addr' => array( // tirando error
             '@attributes' => array(
                 'use' => 'HP',
             ),
			'streetAddressLine' => array(
				'@value' => 'Strert'
			),
			'city' => array(
				'@value' => 'Carolina'
			),
			'state' => array(
				'@value' => 'PR'
			),
			'postalCode' => array(
				'@value' => '00987'
			),
			'country' => array(
				'@value' => 'USA'
			)
		),
		'telecom' => array(
			'@attributes' => array(
				'value' => 'tel:000-000-0000'
			)
		)
	)
);
//endregion Data

//region User Data
$User = new User();
$userData = $User->getCurrentUserData();
$author = array(
	'typeId' => array(
		'@attributes' => array(
			'root' => '2.16.840.1.113883.1.3',
		    'extension' => 'POCD_HD000040'
		)
	),
	'time' => array(
		'@attributes' => array(
			'value' => $timeNow
		)
	),
	'assignedAuthor' => array(
		'id' => array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.4.6',
				'extension' =>  $userData['npi']
			)
		),
		'assignedPerson' => array(
			'name' => array(
				'prefix' => $userData['title'],
				'given' => $userData['fname'],
				'family' => $userData['lname'],
			)
		),
		'representedOrganization' => array(
			'id' => array(
				'@attributes' => array(
					'root' => '2.16.840.1.113883.19.5',
					'extension' => UUID::v4()
				),
			),
			'name' => $facility['name']
		),
		'addr' => array( // tirando error
             '@attributes' => array(
                 'use' => 'HP',
             ),
             'streetAddressLine' => array(
                 '@value' => 'Strert'
             ),
             'city' => array(
                 '@value' => 'Carolina'
             ),
             'state' => array(
                 '@value' => 'PR'
             ),
             'postalCode' => array(
                 '@value' => '00987'
             ),
             'country' => array(
                 '@value' => 'USA'
             )
		),
		'telecom' => array(
			'@attributes' => array(
				'value' => 'tel:000-000-0000'
			)
		)
	)
);

$documentationOf = array(
	'serviceEvent' => array(
		'@attributes' => array(
			'classCode' => 'PCPR'
		),
	    'effectiveTime' => array(
	        '@attributes' => array(
				'xsi:type' => 'IVL_TS',
			),
		    'low' => array(
			    '@attributes' => array(
				    'value' => '19320924'
			    )
		    ),
		    'high' => array(
			    '@attributes' => array(
				    'value' => $dateNow
			    )
		    )
	    ),
	    'performer' => array(
		    '@attributes' => array(
			    'typeCode' => 'PRF'
		    ),
	        'functionCode' => array(
		        '@attributes' => array(
			        'code' => 'PCP',
			        'codeSystem' => '2.16.840.1.113883.5.88'
		        )
	        ),
	        'time' => array(
		        'low' => array(
			        '@attributes' => array(
				        'value' => '1990'
			        )
		        ),
		        'high' => array(
			        '@attributes' => array(
				        'value' => $dateNow
			        )
		        )
	        ),
	        'assignedEntity' => array(
		        'id' => array(
			        '@attributes' => array(
				        'root' => UUID::v4()
			        )
		        ),
	            'assignedPerson' => array(
		            'name' => array(
			            'prefix' => $userData['title'],
			            'given' => $userData['fname'],
			            'family' => $userData['lname'],
		            )
	            ),
	            'representedOrganization' => array(
		            'id' => array(
			            '@attributes' => array(
				            'root' => '2.16.840.1.113883.19.5',
				            'extension' => UUID::v4()
			            )
		            ),
		            'name' => $facility['name']
	            )
	        )
	    )
	)
);


unset($userData, $User);
//endregion


$informant = array(
	'assignedEntity' => array(
		'id' => array(
			'@attributes' => array(
				'nullFlavor' => 'NI'
			)
		),
		'representedOrganization' => array(
			'id' => array(
				'@attributes' => array(
					'root' => '2.16.840.1.113883.19.5',
					'extension' => UUID::v4()
				)
			),
			'name' => $facility['name']
		)
	)
);

$custodian = array(
	'assignedCustodian' => array(
		'representedCustodianOrganization' => array(
			'addr' => array(
				'@attributes' => array(
					'use' => 'HP',
				),
				'streetAddressLine' => array(
					'@value' => $facility['street']
				),
				'city' => array(
					'@value' => $facility['city']
				),
				'state' => array(
					'@value' => $facility['state']
				),
				'postalCode' => array(
					'@value' => $facility['postal_code']
				),
				'country' => array(
					'@value' => $facility['country_code']
				)
			),
			'id' => array(
				'@attributes' => array(
					'root' => '2.16.840.1.113883.4.6',
					'extension' => '1234567'  // TODO: Organization NPI
				)
			),
			'name' => $facility['name'],
			'telecom' => array(
				'@attributes' => array(
					'value' => 'tel:(000)000-0000',
				)
			)
		)
	)
);

//region Legal Authenticator

//$legalAuthenticator = array(
//	'time' => array(
//		'@attributes' => array(
//			'value' => $timeNow
//		)
//	),
//	'signatureCode' => array(
//		'@attributes' => array(
//			'code' => 'S'
//		)
//	),
//	'assignedEntity' => array(
//		'id' => array(
//			'@attributes' => array(
//				'nullFlavor' => 'NI'
//			)
//		),
//		'representedOrganization' => array(
//			'id' => array(
//				'@attributes' => array(
//					'root' => '2.16.840.1.113883.19.5',
//					'extension' => UUID::v4()
//				)
//			),
//			'name' => $facility['name']
//		),
//		'addr' => array(
////			'@attributes' => array(
////				'xsi:type' => 'USRealmAddress'
////			),
//		    'streetAddressLine' => array(
//			    '@value' => $facility['street']
//		    ),
//		    'city' => array(
//			    '@value' => $facility['city']
//		    ),
//		    'state' => array(
//			    '@value' => $facility['state']
//		    ),
//		    'postalCode' => array(
//			    '@value' => $facility['postal_code']
//		    ),
//		    'country' => array(
//			    '@value' => $facility['country']
//		    )
//		),
//		'telecom' => array(
//			'@attributes' => array(
//				'value' => 'tel:'. $facility['phone'],
//			    'use' => 'tel'
//			)
//		)
//	)
//);
//endregion

/**
 *
 */
$participant = array(
	array(
		'@attributes' => array(
			'typeCode' => 'IND'
		),
	    'associatedEntity' => array(
		    '@attributes' => array(
			    'classCode' => 'GUAR'
		    ),
	    )
	),
	array(
		'@attributes' => array(
			'typeCode' => 'IND'
		),
		'associatedEntity' => array(
			'@attributes' => array(
				'classCode' => 'NOK'
			),
		)
	)
);


//region Procedures Section
/**
 * Procedures section - (OPTIONAL)
 * ************************************************************************************************************
 * This section defines all interventional, surgical, diagnostic, or therapeutic procedures or treatments
 * pertinent to the patient historically at the time the document is generated. The section may contain all
 * procedures for the period of time being summarized, but should include notable procedures.
 * ************************************************************************************************************
 */
$procedures = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => $requiredProcedures ? '2.16.840.1.113883.10.20.22.2.7.1' : '2.16.840.1.113883.10.20.22.2.7'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '47519-4',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Procedures',
		'text' => ''
	)
);

$proceduresData = array();
if(!empty($proceduresData)){

	$procedures['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Procedure'
							),
							array(
								'@value' => 'Date'
							)
						)
					)
				)
			),
			'tbody' => array(
				'tr' => array()
			)
		)
	);
	$procedures['section']['entry'] = array();

	foreach($proceduresData as $item){
		$procedures['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => 'Procedure Title'
				),
				array(
					'@value' => 'Procedure Date'
				)
			)

		);

		//  Procedure Activity Procedure

		$procedures['section']['entry'][] = array(
			'procedure' => array(
				'@attributes' => array(
					'classCode' => 'PROC',
					'moodCode' => 'EVN'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.14'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => '152734007', // TODO...
						'codeSystem' => $codes['SNOMED-CT'],
						'displayName' => 'Total hip replacement',
					)
				),
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'completed'
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'value' => '19980123' // TODO...
					)
				)
			)
		);
	}
}


if($requiredProcedures || isset($procedures['section']['entry'])){
	$body[] = $procedures;
}
unset($proceduresData, $procedures);
//endregion


//region Vital Signs Section
/**
 * Vital Signs section - (SHALL)
 * ************************************************************************************************************
 * This section contains current and historically relevant vital signs, such as blood pressure, heart rate,
 * respiratory rate, height, weight, body mass index, head circumference, crown-to-rump length, and pulse
 * oximetry. The section may contain all vital signs for the period of time being summarized, but at a
 * minimum should include notable vital signs such as the most recent, maximum and/or minimum, or both,
 * baseline, or relevant trends.
 * ************************************************************************************************************
 */
$vitals = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => $requiredVitals ? '2.16.840.1.113883.10.20.22.2.4.1' : '2.16.840.1.113883.10.20.22.2.4'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '8716-3',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Vital Signs',
		'text' => ''
	)
);

$vitalsData = $Encounter->getVitalsByPid($pid);
if(!empty($vitalsData)){

	$vitals['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
		    'thead' => array(
			    'tr' => array(
				    array(
					    'th' => array(
						    array(
							    '@attributes' => array(
								    'align' => 'right'
							    ),
						        '@value' => 'Date / Time:'
						    )
					    )
				    )
			    )
		    ),
		    'tbody' => array(
			    'tr' => array(
				    array(
					    'th' => array(
						    array(
							    '@attributes' => array(
								    'align' => 'left'
							    ),
							    '@value' => 'Height'
						    )
					    )

				    ),
				    array(
					    'th' => array(
						    array(
							    '@attributes' => array(
								    'align' => 'left'
							    ),
							    '@value' => 'Weight'
						    )
					    )

				    ),
				    array(
					    'th' => array(
						    array(
							    '@attributes' => array(
								    'align' => 'left'
							    ),
							    '@value' => 'Blood Pressure'
						    )
					    )

				    )
			    )
		    )
		)
	);
	$vitals['section']['entry'] = array();

	foreach($vitalsData as $item){
		/**
		 * strip date (yyyy-mm-dd hh:mm:ss => yyyymmdd)
		 */
		$date = preg_replace('/(\d{4})-(\d{2})-(\d{2}) \d{2}:\d{2}:\d{2}/', '$1$2$3', $item['date']);

		/**
		 * date
		 */
		$vitals['section']['text']['table']['thead']['tr'][0]['th'][] = array(
			'@value' => date('F j, Y', strtotime($item['date']))
		);
		/**
		 * Height
		 */
		$vitals['section']['text']['table']['tbody']['tr'][0]['td'][] = array(
			'@value' => $item['height_cm'] . ' cm'
		);
		/**
		 * Weight
		 */
		$vitals['section']['text']['table']['tbody']['tr'][1]['td'][] = array(
			'@value' => $item['weight_kg'] . ' kg'
		);
		/**
		 * Blood Pressure
		 */
		$vitals['section']['text']['table']['tbody']['tr'][2]['td'][] = array(
			'@value' => $item['bp_systolic'] .'/'. $item['bp_diastolic'] . ' mmHg'
		);

		/**
		 * Code Entry
		 */
		$vitals['section']['entry'][] = array(
			'@attributes' => array(
				'typeCode' => 'DRIV'
			),
		    'organizer' => array(
			    '@attributes' => array(
				    'classCode' => 'CLUSTER',
				    'moodCode' => 'EVN'
			    ),
		        'templateId' => array(
			        '@attributes' => array(
				        'root' => '2.16.840.1.113883.10.20.22.4.26'
			        )
		        ),
		        'id' => array(
			        '@attributes' => array(
				        'root' => UUID::v4()
			        )
		        ),
		        'code' => array(
			        '@attributes' => array(
				        'code' => '46680005',
				        'codeSystem' => '2.16.840.1.113883.6.96',
				        'displayName' => 'Vital signs'
			        )
		        ),
		        'statusCode' => array(
			        '@attributes' => array(
				        'code' => 'completed'
			        )
		        ),
		        'effectiveTime' => array(
			        '@attributes' => array(
				        'value' => $date
			        )
		        ),
		        'component' => array(
			        array(
				        'observation' => array(
					        '@attributes' => array(
						        'classCode' => 'OBS',
						        'moodCode' => 'EVN'
					        ),
					        'templateId' => array(
						        '@attributes' => array(
							        'root' => '2.16.840.1.113883.10.20.22.4.27'
						        )
					        ),
					        'id' => array(
						        '@attributes' => array(
							        'root' => UUID::v4()
						        )
					        ),
					        'code' => array(
						        '@attributes' => array(
							        'code' => '8302-2',
							        'codeSystem' => '2.16.840.1.113883.6.1',
							        'displayName' => 'Height'
						        )
					        ),
					        'statusCode' => array(
						        '@attributes' => array(
							        'code' => 'completed'
						        )
					        ),
					        'effectiveTime' => array(
						        '@attributes' => array(
							        'value' => $date
						        )
					        ),
					        'value' => array(
						        '@attributes' => array(
							        'xsi:type' => 'PQ',
							        'value' => $item['height_cm'],
							        'unit' => 'cm'
						        )
					        )
				        )
			        ),
			        array(
				        'observation' => array(
					        '@attributes' => array(
						        'classCode' => 'OBS',
						        'moodCode' => 'EVN'
					        ),
					        'templateId' => array(
						        '@attributes' => array(
							        'root' => '2.16.840.1.113883.10.20.1.31'
						        )
					        ),
					        'id' => array(
						        '@attributes' => array(
							        'root' => UUID::v4()
						        )
					        ),
					        'code' => array(
						        '@attributes' => array(
							        'code' => '3141-9',
							        'codeSystem' => '2.16.840.1.113883.6.1',
							        'displayName' => 'Weight Measured'
						        )
					        ),
					        'statusCode' => array(
						        '@attributes' => array(
							        'code' => 'completed'
						        )
					        ),
					        'effectiveTime' => array(
						        '@attributes' => array(
							        'value' => $date
						        )
					        ),
					        'value' => array(
						        '@attributes' => array(
							        'xsi:type' => 'PQ',
							        'value' => $item['weight_kg'],
							        'unit' => 'kg'
						        )
					        )
				        )
			        ),
			        array(
				        'observation' => array(
					        '@attributes' => array(
						        'classCode' => 'OBS',
						        'moodCode' => 'EVN'
					        ),
					        'templateId' => array(
						        '@attributes' => array(
							        'root' => '2.16.840.1.113883.10.20.1.31'
						        )
					        ),
					        'id' => array(
						        '@attributes' => array(
							        'root' => UUID::v4()
						        )
					        ),
					        'code' => array(
						        '@attributes' => array(
							        'code' => '8480-6',
							        'codeSystem' => '2.16.840.1.113883.6.1',
							        'displayName' => 'BP Systolic'
						        )
					        ),
					        'statusCode' => array(
						        '@attributes' => array(
							        'code' => 'completed'
						        )
					        ),
					        'effectiveTime' => array(
						        '@attributes' => array(
							        'value' => $date
						        )
					        ),
					        'value' => array(
						        '@attributes' => array(
							        'xsi:type' => 'PQ',
							        'value' => $item['bp_systolic'],
							        'unit' => 'mm[Hg]'
						        )
					        )
				        )

			        ),
			        array(
				        'observation' => array(
					        '@attributes' => array(
						        'classCode' => 'OBS',
						        'moodCode' => 'EVN'
					        ),
					        'templateId' => array(
						        '@attributes' => array(
							        'root' => '2.16.840.1.113883.10.20.1.31'
						        )
					        ),
					        'id' => array(
						        '@attributes' => array(
							        'root' => UUID::v4()
						        )
					        ),
					        'code' => array(
						        '@attributes' => array(
							        'code' => '8462-4',
							        'codeSystem' => '2.16.840.1.113883.6.1',
							        'displayName' => 'BP Diastolic'
						        )
					        ),
					        'statusCode' => array(
						        '@attributes' => array(
							        'code' => 'completed'
						        )
					        ),
					        'effectiveTime' => array(
						        '@attributes' => array(
							        'value' => $date
						        )
					        ),
					        'value' => array(
						        '@attributes' => array(
							        'xsi:type' => 'PQ',
							        'value' => $item['bp_diastolic'],
							        'unit' => 'mm[Hg]'
						        )
					        )
				        )

			        )
		        )
		    )
		);
	}
}

if($requiredVitals || isset($vitals['section']['entry'])){
	$body[] = $vitals;
}
unset($vitalsData, $vitals);
//endregion


//region Immunizations Section
/**
 * Immunizations section - (SHALL)
 * ************************************************************************************************************
 * The Immunizations section defines a patient’s current immunization status and pertinent immunization
 * history. The primary use case for the Immunization section is to enable communication of a patient’s
 * immunization status. The section should include current immunization status, and may contain the entire
 * immunization history that is relevant to the period of time being summarized.
 * ************************************************************************************************************
 */
$immunizations = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => $requiredImmunization ? '2.16.840.1.113883.10.20.22.2.2.1' : '2.16.840.1.113883.10.20.22.2.2'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '11369-6',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Immunizations',
		'text' => ''
	)
);

$immunizationsData = $Medical->getPatientImmunizationsByPid($pid);
if(!empty($immunizationsData)){

	$immunizations['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Vaccine'
							),
							array(
								'@value' => 'Date'
							),
							array(
								'@value' => 'Status'
							)
						)
					)
				)
			),
			'tbody' => array(
				'tr' => array()
			)
		)
	);
	$immunizations['section']['entry'] = array();

	foreach($immunizationsData as $item){
		$date = preg_replace('/(\d{4})-(\d{2})-(\d{2}) \d{2}:\d{2}:\d{2}/', '$1$2', $item['administered_date']);
		$immunizations['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => ucwords($item['vaccine_name'])
				),
				array(
					'@value' => date('F Y', strtotime($item['administered_date']))
				),
				array(
					'@value' => 'Completed'
				)
			)

		);
		$immunizations['section']['entry'][] = array(
			'substanceAdministration' => array(
				'@attributes' => array(
					'classCode' => 'SBADM',
					'moodCode' => 'EVN',
				    'negationInd' => 'false',
				    'nullFlavor' => 'NI'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.52'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'completed'
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'value' => $date
					)
				),
			    'routeCode' => array(
				    '@attributes' => array(
					    'code' => 'C28161',
					    'codeSystem' => '2.16.840.1.113883.3.26.1.1',
					    'codeSystemName' => 'NCI Thesaurus',
					    'displayName' => 'INTRAMUSCULAR',
				    )
			    ),
			    'consumable' => array(
				    'manufacturedProduct' => array(
					    '@attributes' => array(
							'classCode' => 'MANU'
						),
					    'templateId' => array(
						    '@attributes' => array(
							    'root' => '2.16.840.1.113883.10.20.22.4.54'
						    )
					    ),
				        'manufacturedMaterial' => array(
					        'code' => array(
						        '@attributes' => array(
							        'code' => $item['code'],
							        'codeSystem' => '2.16.840.1.113883.12.292', // CVX
							        'displayName' => ucwords($item['vaccine_name'])
						        )
					        )
				        )
				    )
			    )
			)
		);
	}

}

if($requiredImmunization || isset($immunizations['section']['entry'])){
	$body[] = $immunizations;
}
unset($immunizationsData, $immunizations);
//endregion


//region Medications Section
/**
 * Medications section - (SHALL)
 * -------------------
 * The Medications section defines a patient’s current medications and pertinent medication history. At a
 * minimum, the currently active medications should be listed, with an entire medication history as an option,
 * particularly when the summary document is used for comprehensive data export. The section may also
 * include a patient’s prescription history, and enables the determination of the source of a medication list
 * (e.g. from a pharmacy system vs. from the patient).
 */

$medications = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => $requiredMedications ? '2.16.840.1.113883.10.20.22.2.1.1' : '2.16.840.1.113883.10.20.22.2.1'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '10160-0',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Medications',
		'text' => ''
	)
);
$medicationsData = $Medical->getPatientMedicationsByPid($pid);
if(!empty($medicationsData)){

	$medications['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Medication'
							),
							array(
								'@value' => 'Instructions'
							),
							array(
								'@value' => 'Start Date'
							),
							array(
								'@value' => 'Status'
							)
						)
					)
				)
			),
			'tbody' => array(
				'tr' => array()
			)
		)
	);

	$medications['section']['entry'] = array();

	foreach($medicationsData as $item){
		$medications['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => $item['STR']
				),
				array(
					'@value' => $item['dose'] . ' ' .$item['prescription_when']
				),
				array(
					'@value' => date('F j, Y', strtotime($item['begin_date']))
				),
				array(
					'@value' => isset($item['begin_date']) && $item['begin_date'] == '0000-00-00 00:00:00' ? 'No longer active' : 'Active'
				)
			)

		);


		$medications['section']['entry'][] = array(
			'substanceAdministration' => array(
				'@attributes' => array(
					'classCode' => 'SBADM',
					'moodCode' => 'EVN'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.16'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'completed'
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS'
					),
					'low' => array(
						'@attributes' => array(
							'value' => date('Ymd', strtotime($item['begin_date']))
						)
					),
					'high' => array(
						'@attributes' => array(
							'value' => date('Ymd', strtotime($item['begin_date']))
						)
					)
				),
				'consumable' => array(
					'manufacturedProduct' => array(
						'@attributes' => array(
							'classCode' => 'MANU'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.23'
							)
						),
						'manufacturedMaterial' => array(
							'code' => array(
								'@attributes' => array(
									'code' => $item['RXCUI'],
									'codeSystem' => '2.16.840.1.113883.6.88', // CVX
									'displayName' => ucwords($item['STR']),
									'codeSystemName' => 'RxNorm'
								)
							)
						)
					)
				),
			    'text' => $item['STR']
			)
		);
	}

}

if($requiredMedications || isset($medications['section']['entry'])){
	$body[] = $medications;
}
unset($medicationsData, $medications);
//endregion


//region Plan of Care Section
/**
 * Plan of Care section - (OPTIONAL)
 * --------------------
 * The plan of care section contains data defining pending orders, interventions, encounters, services, and
 * procedures for the patient. It is limited to prospective, unfulfilled, or incomplete orders and requests only.
 * All active, incomplete, or pending orders, appointments, referrals, procedures, services, or any other
 * pending event of clinical significance to the current and ongoing care of the patient should be listed, unless
 * constrained due to issues of privacy.
 */
$planOfCareData = array();
$planOfCare = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.22.2.10'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '18776-5',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Plan',
		'text' => ''
	)
);

if(!empty($planOfCareData)){

	$planOfCare['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Planned Activity'
							),
							array(
								'@value' => 'Planned Date'
							)
						)
					)
				)
			)
		)
	);


	$planOfCare['section']['text']['table']['tbody']['tr'] = array();
	$planOfCare['section']['entry'] = array();

	foreach($planOfCareData as $item){

		$planOfCare['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => 'Test'
				),
				array(
					'@value' => 'Ting'
				)
			)

		);

		$planOfCare['section']['entry'][] = array(
			'@attributes' => array(
				'typeCode' => 'DRIV'
			),
			'observation' => array(
				'@attributes' => array(
					'classCode' => 'OBS',
					'moodCode' => 'RQO'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.1.25'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => '23426006',
						'codeSystem' => '2.16.840.1.113883.6.96',
						'displayName' => 'Pulmonary function test',
					)
				),
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'new'
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'value' => '20000421'
					)
				)
			)
		);
	}
}

if($requiredPlanOfCare || isset($planOfCare['section']['entry'])){
	$body[] = $planOfCare;
}
unset($planOfCareData, $planOfCare);
//endregion


//region Active Problems Section
/**
 * Problems section - (SHALL)
 * ----------------
 * This section lists and describes all relevant clinical problems at the time the summary is generated. At a
 * minimum, all pertinent current and historical problems should be listed. CDA R2 represents problems as
 * Observations.
 */
$problems = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => $requiredProblems ? '2.16.840.1.113883.10.20.22.2.5.1' : '2.16.840.1.113883.10.20.22.2.5'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '11450-4',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Problems',
		'text' => ''
	)
);
$problemsData = $Medical->getPatientProblemsByPid($pid);
if(!empty($problemsData)){

	$problems['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Condition'
							),
							array(
								'@value' => 'Effective Dates'
							),
							array(
								'@value' => 'Condition Status'
							)
						)
					)
				)
			),
			'tbody' => array(
				'tr' => array()
			)
		)
	);
	$problems['section']['entry'] = array();

	foreach($problemsData as $item){

		$problems['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => 'Condition Data'
				),
				array(
					'@value' => 'Datate Data'
				),
				array(
					'@value' => 'Active'
				)
			)

		);

		$problems['section']['entry'][] = array(
			'act' => array(
				'@attributes' => array(
					'classCode' => 'ACT',
					'moodCode' => 'EVN'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.3'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => 'CONC',
						'codeSystem' => '2.16.840.1.113883.5.6'
					)
				),
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'active', // active ||  suspended ||  aborted ||  completed
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					),
					'low' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					),
					'high' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					)
				),
				'entryRelationship' => array(
					'@attributes' => array(
						'typeCode' => 'SUBJ'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.4'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						/**
						 *  404684003	SNOMEDCT	Finding
						 *	409586006	SNOMEDCT	Complaint
						 *	282291009	SNOMEDCT	Diagnosis
						 *	64572001	SNOMEDCT	Condition
						 *	248536006	SNOMEDCT	Functional limitation
						 *	418799008	SNOMEDCT	Symptom
						 *	55607006	SNOMEDCT	Problem
						 *	373930000	SNOMEDCT	Cognitive function finding
	                     */
						'code' => array(
							'@attributes' => array(
								'code' => '55607006',
								'codeSystem' => '2.16.840.1.113883.6.96'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed', // active ||  suspended ||  aborted ||  completed
								//'codeSystem' => '2.16.840.1.113883.5.14'
							)
						),
						'effectiveTime' => array(
							'@attributes' => array(
								'xsi:type' => 'IVL_TS',
							),
							'low' => array(
								'@attributes' => array(
									'value' => '19320924'
								)
							),
							'high' => array(
								'@attributes' => array(
									'value' => '19320924'
								)
							)
						),
						'value' => array(
							'@attributes' => array(
								'xsi:type' => 'CD',
								'code' => '195967001',
								'codeSystem' => '2.16.840.1.113883.6.96' // SNOMED
							)
						),
						'entryRelationship' => array(
							'@attributes' => array(
								'typeCode' => 'REFR'
							),
							'observation' => array(
								'@attributes' => array(
									'classCode' => 'OBS',
									'moodCode' => 'EVN'
								),
								'templateId' => array(
									'@attributes' => array(
										'root' => '2.16.840.1.113883.10.20.22.4.6'
									)
								),
								'code' => array(
									'@attributes' => array(
										'code' => '33999-4',
										'codeSystem' => '2.16.840.1.113883.6.1'
									)
								),
								'statusCode' => array(
									'@attributes' => array(
										'code' => 'completed',
										//'codeSystem' => '2.16.840.1.113883.5.14'
									)
								),
								/**
	                             * 55561003	    SNOMEDCT	Active
								 * 73425007	    SNOMEDCT	Inactive
								 * 413322009	SNOMEDCT	Resolved
	                             */
								'value' => array(
									'@attributes' => array(
										'xsi:type' => 'CD',
										'code' => '413322009',
										'codeSystem' => '2.16.840.1.113883.6.96' // SNOMED
									)
								)
							)

						)
					)

				)
			)

		);
	}

}

if($requiredProblems || !empty($problems['section']['entry'])){
	$body[] = $problems;
}
unset($problemsData, $problems);
//endregion


//region Allergies Section
/**
 * Allergies Section
 * --------------
 * This section is used to list and describe any allergies, adverse reactions, and alerts that are pertinent to the
 * patient’s current or past medical history. At a minimum, currently active and any relevant historical
 * allergies and adverse reactions should be listed.
 */
$allergiesData = $Medical->getPatientAllergiesByPid($pid);
$allergies = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => $requiredAllergies ? '2.16.840.1.113883.10.20.22.2.6.1' : '2.16.840.1.113883.10.20.22.2.6'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '48765-2',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Allergies, adverse reactions, alerts',
		'text' => ''
	)
);

if(!empty($allergiesData)){
	$allergies['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Substance'
							),
							array(
								'@value' => 'Reaction'
							),
							array(
								'@value' => 'Status'
							)
						)
					)
				)
			),
			'tbody' => array(
				'tr' => array()
			)
		)
	);
	$allergies['section']['entry'] = array();


	foreach($allergiesData as $item){

		$allergies['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => 'Substance Data'
				),
				array(
					'@value' => 'Reaction Data'
				),
				array(
					'@value' => 'Status Data'
				)
			)

		);


		$allergies['section']['entry'][] = array(
			'act' => array(
				'@attributes' => array(
					'classCode' => 'ACT',
					'moodCode' => 'EVN'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.30'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'code' => array(
					'@attributes' => array(
						'nullFlavor' => 'NA',
//						'codeSystem' => '2.16.840.1.113883.5.6'
					)
				),
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'active', // active ||  suspended ||  aborted ||  completed
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					),
					'low' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					),
					'high' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					)
				),
				'entryRelationship' => array(
					'@attributes' => array(
						'typeCode' => 'SUBJ'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.7'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => 'ASSERTION',
								'codeSystem' => '2.16.840.1.113883.5.4'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed', // active ||  suspended ||  aborted ||  completed
								//'codeSystem' => '2.16.840.1.113883.5.14'
							)
						),
						'effectiveTime' => array(
							// If it is unknown when the allergy began, this effectiveTime SHALL contain low/@nullFLavor="UNK" (CONF:9103)
							'@attributes' => array(
								'xsi:type' => 'IVL_TS',
							),
							'low' => array(
								'@attributes' => array(
									'value' => '19320924'
								)
							),
							'high' => array(
								'@attributes' => array(
									'value' => '19320924'
								)
							)
						),
						/**
						 * 420134006	SNOMEDCT	Propensity to adverse reactions
						 * 418038007	SNOMEDCT	Propensity to adverse reactions to substance
						 * 419511003	SNOMEDCT	Propensity to adverse reactions to drug
						 * 418471000	SNOMEDCT	Propensity to adverse reactions to food
						 * 419199007	SNOMEDCT	Allergy to substance
						 * 416098002	SNOMEDCT	Drug allergy
						 * 414285001	SNOMEDCT	Food allergy
						 * 59037007	    SNOMEDCT	Drug intolerance
						 * 235719002	SNOMEDCT	Food intolerance
                         */
						'value' => array(
							'@attributes' => array(
								'xsi:type' => 'CD',
								'code' => '416098002', // TODO
								'codeSystem' => '2.16.840.1.113883.6.96' // SNOMED
							)
						),
						'entryRelationship' => array(
							// Reaction Observation
							array(
								'@attributes' => array(
									'typeCode' => 'MFST',
									'inversionInd' => 'true'
								),
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.9'
										)
									),
									'id' => array(
										'@attributes' => array(
											'root' => UUID::v4()
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => '33999-4', // TODO: allergy SNOMED code
											'codeSystem' => $codes['SNOMED-CT']
										)
									),
									'statusCode' => array(
										'@attributes' => array(
											'code' => 'completed'
										)
									),
									/**
		                             * 55561003	    SNOMEDCT	Active
									 * 73425007	    SNOMEDCT	Inactive
									 * 413322009	SNOMEDCT	Resolved
		                             */
									'value' => array(
										'@attributes' => array(
											'xsi:type' => 'CD',
											'code' => '413322009',
											'codeSystem' => $codes['SNOMED-CT'] // SNOMED
										)
									)
								)
							),
							//  Severity Observation
							array(
								'@attributes' => array(
									'typeCode' => 'SUBJ',
									'inversionInd' => 'true'
								),
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.8'
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => 'SEV',
											'codeSystem' => '2.16.840.1.113883.5.4'
										)
									),
									'statusCode' => array(
										'@attributes' => array(
											'code' => 'completed'
										)
									),
									/**
		                             * 255604002	SNOMEDCT	Mild
									 * 371923003	SNOMEDCT	Mild to moderate
									 * 6736007	    SNOMEDCT	Moderate
									 * 371924009	SNOMEDCT	Moderate to severe
									 * 24484000	    SNOMEDCT	Severe
									 * 399166001	SNOMEDCT	Fatal
		                             */
									'value' => array(
										'@attributes' => array(
											'xsi:type' => 'CD',
											'code' => '255604002', // TODO...
											'codeSystem' => $codes['SNOMED-CT'] // SNOMED
										)
									)
								)
							),
							// Allergy Status Observation
							array(
								'@attributes' => array(
									'typeCode' => 'SUBJ',
									'inversionInd' => 'true'
								),
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.28'
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => '33999-4',
											'codeSystem' => '2.16.840.1.113883.6.1'
										)
									),
									'statusCode' => array(
										'@attributes' => array(
											'code' => 'completed'
										)
									),
									/**
		                             * 55561003	    SNOMEDCT	Active
									 * 73425007	    SNOMEDCT	Inactive
									 * 413322009	SNOMEDCT	Resolved
		                             */
									'value' => array(
										'@attributes' => array(
											'xsi:type' => 'CE',
											'code' => '413322009', // TODO...
											'codeSystem' => '2.16.840.1.113883.6.96' // SNOMED
										)
									)
								)
							)
						)
					)

				)
			)
		);

	}
}
if($requiredAllergies || !empty($allergies['section']['entry'])){
	$body[] = $allergies;
}
unset($allergiesData, $allergies);
//endregion


//region Social History Section TODO
$socialHistory = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.22.2.17'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '29762-2',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Social History',
		'text' => array(
			'table' => array(
				'@attributes' => array(
					'border' => '1',
					'width' => '100%'
				),
				'thead' => array(
					'tr' => array(
						array(
							'th' => array(
								array(
									'@value' => 'Social History Element'
								),
								array(
									'@value' => 'Description'
								),
								array(
									'@value' => 'Effective Dates'
								)
							)
						)
					)
				),
				'tbody' => array(
					'tr' => array()
				)
			),
			'entry' => array()
		)
	)
);

/**
 * This Social History Observation defines the patient's occupational, personal (e.g., lifestyle),
 * social, and environmental history and health risk factors, as well as administrative data such
 * as marital status, race, ethnicity, and religious affiliation.
 */
//$socialHistory['section']['entry'][] = array(
//$socialHistory['section']['text']['table']['tbody']['tr'][] = array(
//	'td' => array(
//		array(
//			'@value' => 'Social History Element Data'
//		),
//		array(
//			'@value' => 'ReactiDescriptionon Data'
//		),
//		array(
//			'@value' => 'Effective Data'
//		)
//	)
//);
//	'observation' => array(
//		'@attributes' => array(
//			'classCode' => 'OBS',
//			'moodCode' => 'EVN'
//		),
//		'templateId' => array(
//			'@attributes' => array(
//				'root' => '2.16.840.1.113883.10.20.22.4.38'
//			)
//		),
//		'id' => array(
//			'@attributes' => array(
//				'root' => UUID::v4()
//			)
//		),
//		/**
//         * Code	        System  	Print Name
//		 * 229819007	SNOMEDCT	Tobacco use and exposure
//		 * 256235009	SNOMEDCT	Exercise
//		 * 160573003	SNOMEDCT	Alcohol intake
//		 * 364393001	SNOMEDCT	Nutritional observable
//		 * 364703007	SNOMEDCT	Employment detail
//		 * 425400000	SNOMEDCT	Toxic exposure status
//		 * 363908000	SNOMEDCT	Details of drug misuse behavior
//		 * 228272008	SNOMEDCT	Health-related behavior
//		 * 105421008	SNOMEDCT	Educational Achievement
//         */
//		'code' => array(
//			'@attributes' => array(
//				'code' => '229819007',
//				'codeSystem' => $codes['SNOMED-CT'],
//				'displayName' => 'Tobacco use and exposure'
//			)
//		),
//		'statusCode' => array(
//			'@attributes' => array(
//				'code' => 'completed', // active ||  suspended ||  aborted ||  completed
//			)
//		),
//		'value' => array(
//			'@attributes' => array(
//				'xsi:type' => 'ST'
//			),
//			'@value' => 'Smoking Data'
//		)
//	)
//);

/**
 * This clinical statement represents a patient's current smoking status. The vocabulary selected for this
 * clinical statement is the best approximation of the statuses in Meaningful Use (MU) Stage 1.
 *
 * If the patient is a smoker (77176002), the effectiveTime/low element must be present. If the patient
 * is an ex-smoker (8517006), both the effectiveTime/low and effectiveTime/high element must be present.
 *
 * The smoking status value set includes a special code to communicate if the smoking status is unknown
 * which is different from how Consolidated CDA generally communicates unknown information.
 */
 $socialHistory['section']['text']['table']['tbody']['tr'][] = array(
	'td' => array(
		array(
			'@value' => 'Social History Element Data'
		),
		array(
			'@value' => 'ReactiDescriptionon Data'
		),
		array(
			'@value' => 'Effective Data'
		)
	)
);
$socialHistory['section']['entry'][] = array(
	'observation' => array(
		'@attributes' => array(
			'classCode' => 'OBS',
			'moodCode' => 'EVN'
		),
		'templateId' => array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.22.4.78'
			)
		),
		'id' => array(
			'@attributes' => array(
				'root' => UUID::v4()
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => 'ASSERTION',
				'codeSystem' => '2.16.840.1.113883.5.4'
			)
		),
		'statusCode' => array(
			'@attributes' => array(
				'code' => 'completed', // active ||  suspended ||  aborted ||  completed
			)
		),
		/**
         * Code	            System	    Print Name
		 * 449868002	    SNOMEDCT	Current every day smoker
		 * 428041000124106	SNOMEDCT	Current some day smoker
		 * 8517006	        SNOMEDCT	Former smoker
		 * 266919005	    SNOMEDCT	Never smoker (Never Smoked)
		 * 77176002	        SNOMEDCT	Smoker, current status unknown
		 * 266927001	    SNOMEDCT	Unknown if ever smoked
         */
		'value' => array(
			'@attributes' => array(
				'xsi:type' => 'CD',
				'code' => '449868002',
				'codeSystem' => $codes['SNOMED-CT']
			)
		),
		'effectiveTime' => array(
			'@attributes' => array(
				'xsi:type' => 'IVL_TS',
			),
			'low' => array(
				'@attributes' => array(
					'value' => '19320924'
				)
			),
			'high' => array(
				'@attributes' => array(
					'value' => '19320924'
				)
			)
		),
	)
);

/**
 * This clinical statement represents current and/or prior pregnancy dates
 * enabling investigators to determine if the subject of the case report
 * was pregnant during the course of a condition.
 */
 $socialHistory['section']['text']['table']['tbody']['tr'][] = array(
	'td' => array(
		array(
			'@value' => 'Social History Element Data'
		),
		array(
			'@value' => 'ReactiDescriptionon Data'
		),
		array(
			'@value' => 'Effective Data'
		)
	)
);
$socialHistory['section']['entry'][] = array(
	'observation' => array(
		'@attributes' => array(
			'classCode' => 'OBS',
			'moodCode' => 'EVN'
		),
		'templateId' => array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.15.3.8'
			)
		),
		'id' => array(
			'@attributes' => array(
				'root' => UUID::v4()
			)
		),
		/**
         * Code	        System  	Print Name
		 * 229819007	SNOMEDCT	Tobacco use and exposure
		 * 256235009	SNOMEDCT	Exercise
		 * 160573003	SNOMEDCT	Alcohol intake
		 * 364393001	SNOMEDCT	Nutritional observable
		 * 364703007	SNOMEDCT	Employment detail
		 * 425400000	SNOMEDCT	Toxic exposure status
		 * 363908000	SNOMEDCT	Details of drug misuse behavior
		 * 228272008	SNOMEDCT	Health-related behavior
		 * 105421008	SNOMEDCT	Educational Achievement
         */
		'code' => array(
			'@attributes' => array(
				'code' => 'ASSERTION',
				'codeSystem' => '2.16.840.1.113883.5.4'
			)
		),
		'statusCode' => array(
			'@attributes' => array(
				'code' => 'completed', // active ||  suspended ||  aborted ||  completed
			)
		),
		'value' => array(
			'@attributes' => array(
				'xsi:type' => 'CD',
				'code' => '77386006', // Pregnant
				'codeSystem' => '2.16.840.1.113883.6.96'
			)
		),
		'effectiveTime' => array(
			'@attributes' => array(
				'xsi:type' => 'IVL_TS',
			),
			'low' => array(
				'@attributes' => array(
					'value' => '19320924' // TODO
				)
			),
			'high' => array(
				'@attributes' => array(
					'value' => '19320924' // TODO
				)
			)
		),
		'entryRelationship' => array(
			'@attributes' => array(
				'typeCode' => 'REFR'
			),
			'observation' => array(
				'@attributes' => array(
					'classCode' => 'OBS',
					'moodCode' => 'EVN'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.15.3.1'
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => '11778-8',
						'codeSystem' => '2.16.840.1.113883.6.1'
					)
				),
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'completed'
					)
				),
				'value' => array(
					'@attributes' => array(
						'xsi:type' => 'TS',
						'value' => '20150123' // Estimated Date Of Delivery
					)
				)
			)
		)
	)
);

$body[] = $socialHistory;
unset($socialHistoryData, $socialHistory);
//endregion

//region Results Section
/**
 * Results section
 * ************************************************************************************************************
 * This section contains the results of observations generated by laboratories, imaging procedures, and other
 * procedures. The scope includes hematology, chemistry, serology, virology, toxicology, microbiology, plain
 * x-ray, ultrasound, CT, MRI, angiography, cardiac echo, nuclear medicine, pathology, and procedure
 * observations. The section may contain all results for the period of time being summarized, but should
 * include notable results such as abnormal values or relevant trends.
 * ************************************************************************************************************
 */
$results = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => $requiredResults ? '2.16.840.1.113883.10.20.22.2.3.1' : '2.16.840.1.113883.10.20.22.2.3'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '30954-2',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Results',
		'text' => ''
	)
);
$resultsData = array();
if(!empty($allergiesData)){
	$results['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Substance'
							),
							array(
								'@value' => 'Reaction'
							),
							array(
								'@value' => 'Status'
							)
						)
					)
				)
			),
			'tbody' => array(
				'tr' => array()
			)
		)
	);
	$results['section']['entry'] = array();


	foreach($allergiesData as $item){

		$results['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => 'Substance Data'
				),
				array(
					'@value' => 'Reaction Data'
				),
				array(
					'@value' => 'Status Data'
				)
			)

		);


		$results['section']['entry'][] = $order = array(
			'organizer' => array(
				'@attributes' => array(
					'classCode' => 'CLUSTER', // CLUSTER || BATTERY
					'moodCode' => 'EVN'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.1'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => 'NA',
						'CodeSystem' => '2.16.840.1.113883.6.1' // LOINC
					)
				),
				/**
                 * Code	        System	    Print Name
				 * aborted	    ActStatus	aborted
				 * active	    ActStatus	active
				 * cancelled	ActStatus	cancelled
				 * completed	ActStatus	completed
				 * held	        ActStatus	held
				 * suspended	ActStatus	suspended
                 */
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'completed',
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					),
					'low' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					),
					'high' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					)
				),
				'component' => array()
			)
		);

		$observations = array(); // TODO

		foreach($observations As $observation){


			$order['organizer']['component'][] = array(
				'component' => array(
					'@attributes' => array(
						'classCode' => 'OBS',
						'moodCode' => 'EVN'
					),
					'templateId' => array(
						'@attributes' => array(
							'root' => '2.16.840.1.113883.10.20.22.4.2'
						)
					),
					'id' => array(
						'@attributes' => array(
							'root' => UUID::v4()
						)
					),
					'code' => array(
						'@attributes' => array(
							'code' => '33765-9', // TODO
							'CodeSystem' => '2.16.840.1.113883.6.1', // LOINC
							'displayName' => 'WBC'
						)
					),
					/**
	                 * Code	        System	    Print Name
					 * aborted	    ActStatus	aborted
					 * active	    ActStatus	active
					 * cancelled	ActStatus	cancelled
					 * completed	ActStatus	completed
					 * held	        ActStatus	held
					 * suspended	ActStatus	suspended
	                 */
					'statusCode' => array(
						'@attributes' => array(
							'code' => 'completed'
						)
					),
					'value' => array(
						'@attributes' => array(
							'xsi:type' => 'PQ',
							'value' => '6.7',
							'unit' => '0+3/ul'
						)
					)
				)
			);
		}
	}
}


if($requiredResults || !empty($results['section']['entry'])){
	$body[] = $results;
}
unset($resultsData, $results, $order);
//endregion


//region Functional Status Section

$functionalStatus = array(
	'section' => array(
		'templateId' => array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.22.2.14'
			)
		),
		'code' => array(
			'@attributes' => array(
				'code' => '47420-5',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		),
		'title' => 'Functional status assessment',
		'text' => ''
	)
);
$functionalStatusData = array();
if(!empty($functionalStatusData)){
	$functionalStatus['section']['text'] = array(
		'table' => array(
			'@attributes' => array(
				'border' => '1',
				'width' => '100%'
			),
			'thead' => array(
				'tr' => array(
					array(
						'th' => array(
							array(
								'@value' => 'Functional Condition'
							),
							array(
								'@value' => 'Effective Dates'
							),
							array(
								'@value' => 'Condition Status'
							)
						)
					)
				)
			),
			'tbody' => array(
				'tr' => array()
			)
		)
	);
	$functionalStatus['section']['entry'] = array();


	foreach($functionalStatusData as $item){

		$functionalStatus['section']['text']['table']['tbody']['tr'][] = array(
			'td' => array(
				array(
					'@value' => 'Functional Condition Data'
				),
				array(
					'@value' => 'Effective Dates Data'
				),
				array(
					'@value' => 'Condition Status Data'
				)
			)

		);


		$functionalStatus['section']['entry'][] = $order = array(
			'organizer' => array(
				'@attributes' => array(
					'classCode' => 'CLUSTER', // CLUSTER || BATTERY
					'moodCode' => 'EVN'
				),
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.1'
					)
				),
				'id' => array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => 'NA',
						'CodeSystem' => '2.16.840.1.113883.6.1' // LOINC
					)
				),
				/**
                 * Code	        System	    Print Name
				 * aborted	    ActStatus	aborted
				 * active	    ActStatus	active
				 * cancelled	ActStatus	cancelled
				 * completed	ActStatus	completed
				 * held	        ActStatus	held
				 * suspended	ActStatus	suspended
                 */
				'statusCode' => array(
					'@attributes' => array(
						'code' => 'completed',
					)
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					),
					'low' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					),
					'high' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					)
				),
				'component' => array()
			)
		);
	}
}


if($requiredResults || !empty($functionalStatus['section']['entry'])){
	$body[] = $functionalStatus;
}
unset($functionalStatusData, $functionalStatus);

//endregion


$templateId = array(
	'toc' => '2.16.840.1.113883.10.20.22.1.1' // transition of care template
);


/**
 * The header and body render
 */
$data = array(
	'@attributes' => array(
		'xmlns' => 'urn:hl7-org:v3',
		'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
		'xsi:schemaLocation' => 'urn:hl7-org:v3 CDA.xsd'
	),
    'realmCode' => array(
	    '@attributes' => array(
		    'code' => 'US'
	    )
    ),
    'typeId' => array(
	    '@attributes' => array(
		    'root' => '2.16.840.1.113883.1.3',
		    'extension' => 'POCD_HD000040'
	    )
    ),
    'templateId' => array(
	    '@attributes' => array(
		    'root' => $templateId['toc']
	    )
    ),
    'id' => array(
	    '@attributes' => array(
		    'root' => 'MDHT',
		    'extension' => '1912668293'
	    )
    ),
    'code' => array(
	    '@attributes' => array(
		    'code' => '95483297'
	    )
    ),
    'title' => $facility['name'] . ' - Continuity of Care Document',
    'effectiveTime' => array(
	    '@attributes' => array(
		    'value' =>  $timeNow
	    )
    ),
    'confidentialityCode' => array(
	    '@attributes' => array(
		    'code' => 'N',
		    'codeSystem' => '2.16.840.1.113883.5.25'
	    )
    ),
    'languageCode' => array(
	    '@attributes' => array(
		    'code' => 'en-US'
	    )
    ),
    'recordTarget' => $patient,
    'author' => $author,
    //'informant' => $informant,
    'custodian' => $custodian,
    //'legalAuthenticator' => $legalAuthenticator,
    'component' => array(
	    'structuredBody' => array(
		    'component' => $body
	    )
    )
);




if($_REQUEST['action'] == 'view'){
	/**
	 * Build the CCR XML Object
	 */
	Array2XML::init('1.0', 'UTF-8', true, array('xml-stylesheet' => 'type="text/xsl" href="' . $_SESSION['url'] . 'lib/CCRCDA/schema/cda2.xsl"'));
	$xml = Array2XML::createXML('ClinicalDocument', $data);
	header('Content-type: application/xml');
	print $xml->saveXML();

} elseif($_REQUEST['action'] == 'export'){
	/**
	 * Build the CCR XML Object
	 */
	Array2XML::init('1.0', 'UTF-8', true, array('xml-stylesheet' => 'type="text/xsl" href="' . $_SESSION['url'] . 'lib/CCRCDA/schema/cda2.xsl"'));
	$xml = Array2XML::createXML('ClinicalDocument', $data);

	/**
	 * Create a ZIP archive for delivery
	 */
	$zip = new ZipArchive();
	$tempDirectory = $_SESSION['site']['temp']['path'] . '/';
	$filename = $pid . "-" . $patientData['fname'] . $patientData['lname'];
	if($zip->open($tempDirectory . $filename . ".zip", ZipArchive::CREATE) !== true)
		exit("cannot open <$filename.zip>\n");
	$zip->addFromString($filename . ".xml", $xml->saveXML());
	$zip->addFromString("ccr.xsl", file_get_contents($_SESSION['root'] . '/lib/CCRCDA/schema/cda2.xsl'));
	$zip->close();

	/**
	 * Stream the file to the client
	 */
	header("Content-Type: application/zip");
	header("Content-Length: " . filesize($tempDirectory . $filename . ".zip"));
	header("Content-Disposition: attachment; filename=\"$filename.zip\"");
	readfile($tempDirectory . $filename . ".zip");
	unlink($tempDirectory . $filename . ".zip");
}