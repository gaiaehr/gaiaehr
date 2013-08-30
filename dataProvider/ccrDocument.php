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

if (!isset($_SESSION))
{
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

$_SESSION['url'] = 'http://localhost/gaiaehr';
$_SESSION['root'] = '/var/www/gaiaehr';

include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');
include_once ($_SESSION['root'] . '/classes/UUID.php');
include_once ($_SESSION['root'] . '/classes/Array2XML.php');

include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/PoolArea.php');
include_once ($_SESSION['root'] . '/dataProvider/Medical.php');
include_once ($_SESSION['root'] . '/dataProvider/PreventiveCare.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/DiagnosisCodes.php');

$patientGUID = UUID::v4();
$softwareGUID = UUID::v4();
$paymentProviderGUID = UUID::v4();
$subscriberGUID = UUID::v4();
$pdfDocumentGUID = UUID::v4();

$family1GUID = UUID::v4();
$family2GUID = UUID::v4();

$familyMemberLink1 = UUID::v4();
$familyMemberLink2 = UUID::v4();


/**
 * Medical Equipment section
 * -------------------------
 * The Medical Equipment section defines a patient’s implanted and external medical devices and equipment
 * that their health status depends on, as well as any pertinent equipment or device history. This section is also
 * used to itemize any pertinent current or historical durable medical equipment (DME) used to help maintain
 * the patient’s health status. All pertinent equipment relevant to the diagnosis, care, and treatment of a patient
 * should be included.
 */
$medicalEquipment = array(

);


/**
 * Medications section
 * -------------------
 * The Medications section defines a patient’s current medications and pertinent medication history. At a
 * minimum, the currently active medications should be listed, with an entire medication history as an option,
 * particularly when the summary document is used for comprehensive data export. The section may also
 * include a patient’s prescription history, and enables the determination of the source of a medication list
 * (e.g. from a pharmacy system vs. from the patient).
 */
$medications = array(
    'Medications' => array(
        'Medication' => array(
            array( // Medication 1
                'CCRDocumentObjectID' => UUID::v4(),
                'Status' => array(
                    'Text' => 'Active'
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'Product' => array(
                    'ProductName' => array(
                        'Text' => 'Albuterol inhalant',
                        'Code' => array(
                            'Value' => '307782',
                            'CodingSystem' => 'RxNorm'
                        )
                    )
                ),
                'Directions' => array(
                    'Direction' => array(
                        array( // Direction 1
                            'Dose' => array(
                                'Value' => '2'
                            ),
                            'Route' => array(
                                'Code' => array(
                                    'Value' => 'IPINHL',
                                    'CodingSystem' => 'RouteOfAdministration'
                                )
                            ),
                            'Frequency' => array(
                                'Value' => 'QID'
                            ),
                            'Indication' => array(
                                'PRNFlag' => array(
                                    'Text' => 'Wheezing',
                                    'Code' => array(
                                        'Value' => '56018004',
                                        'CodingSystem' => 'SNOMED CT'
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            array( // Medication 2
                'CCRDocumentObjectID' => UUID::v4(),
                'Status' => array(
                    'Text' => 'Active'
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'Product' => array(
                    'ProductName' => array(
                        'Text' => 'Clopidogrel',
                        'Code' => array(
                            'Value' => '309362',
                            'CodingSystem' => 'RxNorm'
                        )
                    ),
                    'BrandName' => array(
                        'Text' => 'Plavix'
                    ),
                    'Strength' => array(
                        'Value' => '75',
                        'Units' => array(
                            'Unit' => 'MG'
                        )
                    ),
                    'Form' => array(
                        'Text' => 'TABLET'
                    )
                ),
                'Directions' => array(
                    'Direction' => array(
                        array( // Direction 1
                            'Dose' => array(
                                'Value' => '2'
                            ),
                            'Route' => array(
                                'Code' => array(
                                    'Value' => 'IPINHL',
                                    'CodingSystem' => 'RouteOfAdministration'
                                )
                            ),
                            'Frequency' => array(
                                'Value' => 'Daily'
                            )
                        )
                    )
                )
            ),
            array( // Medication 3
                'CCRDocumentObjectID' => UUID::v4(),
                'Status' => array(
                    'Text' => 'Active'
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'Product' => array(
                    'ProductName' => array(
                        'Text' => 'Metoprolol',
                        'Code' => array(
                            'Value' => '430618',
                            'CodingSystem' => 'RxNorm'
                        )
                    ),
                    'Strength' => array(
                        'Value' => '25',
                        'Units' => array(
                            'Unit' => 'MG'
                        )
                    ),
                    'Form' => array(
                        'Text' => 'TABLET'
                    )
                ),
                'Directions' => array(
                    'Direction' => array(
                        array( // Direction 1
                            'Dose' => array(
                                'Value' => '1'
                            ),
                            'Route' => array(
                                'Code' => array(
                                    'Value' => 'PO',
                                    'CodingSystem' => 'RouteOfAdministration'
                                )
                            ),
                            'Frequency' => array(
                                'Value' => 'BID'
                            )
                        )
                    )
                )
            ),
            array( // Medication 4
                'CCRDocumentObjectID' => UUID::v4(),
                'DateTime' => array(
                    'DateTimeRange' => array(
                        'BeginRange' => array(
                            'ExactDateTime' => '2000-03-28'
                        )
                    )
                ),
                'Status' => array(
                    'Text' => 'Active'
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'Product' => array(
                    'ProductName' => array(
                        'Text' => 'Prednisone',
                        'Code' => array(
                            'Value' => '312615',
                            'CodingSystem' => 'RxNorm'
                        )
                    ),
                    'Strength' => array(
                        'Value' => '20',
                        'Units' => array(
                            'Unit' => 'MG'
                        )
                    ),
                    'Form' => array(
                        'Text' => 'TABLET'
                    )
                ),
                'Directions' => array(
                    'Direction' => array(
                        array( // Direction 1
                            'Dose' => array(
                                'Value' => '1'
                            ),
                            'Route' => array(
                                'Code' => array(
                                    'Value' => 'PO',
                                    'CodingSystem' => 'RouteOfAdministration'
                                )
                            ),
                            'Frequency' => array(
                                'Value' => 'Daily'
                            )
                        )
                    )
                )
            ),
            array( //Medication 5
                'CCRDocumentObjectID' => UUID::v4(),
                'DateTime' => array(
                    'DateTimeRange' => array(
                        'BeginRange' => array(
                            'ExactDateTime' => '2000-03-28'
                        ),
                        'EndRange' => array(
                            'ExactDateTime' => '2000-04-04'
                        )
                    )
                ),
                'Status' => array(
                    'Text' => 'No Longer Active'
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'Product' => array(
                    'ProductName' => array(
                        'Text' => 'Cephalexin',
                        'Code' => array(
                            'Value' => '197454',
                            'CodingSystem' => 'RxNorm'
                        )
                    ),
                    'BrandName' => array(
                        'Text' => 'Keflex'
                    ),
                    'Strength' => array(
                        'Value' => '500',
                        'Units' => array(
                            'Unit' => 'MG'
                        )
                    ),
                    'Form' => array(
                        'Text' => 'TABLET'
                    )
                ),
                'Directions' => array(
                    'Direction' => array(
                        array( // Direction 1
                            'Dose' => array(
                                'Value' => '1'
                            ),
                            'Route' => array(
                                'Code' => array(
                                    'Value' => 'PO',
                                    'CodingSystem' => 'RouteOfAdministration'
                                )
                            ),
                            'Frequency' => array(
                                'Value' => 'QID'
                            ),
                            'Indication' => array(
                                'Problem' => array(
                                    'CCRDocumentObjectID' => UUID::v4(),
                                    'Description' => array(
                                        'Text' => 'Bronchitis',
                                        'Code' => array(
                                            'Value' => '32398004',
                                            'CodingSystem' => 'SNOMED CT'
                                        )
                                    ),
                                    'Source' => array(
                                        'Actor' => array(
                                            'ActorID' => $softwareGUID
                                        )
                                    )
                                ),
                                'Source' => array(
                                    'Actor' => array(
                                        'ActorID' => $softwareGUID
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    )
);

/**
 * Alerts section
 * --------------
 * This section is used to list and describe any allergies, adverse reactions, and alerts that are pertinent to the
 * patient’s current or past medical history. At a minimum, currently active and any relevant historical
 * allergies and adverse reactions should be listed.
 */
$alerts = array(
  'Alerts' => array(
      'Alert' => array(
          array( // Alert 1
              'CCRDocumentObjectID' => UUID::v4(),
              'Description' => array(
                  'Text' => 'Adverse reaction to substance',
                  'Code' => array(
                      'Value' => '282100009',
                      'CodingSystem' => 'SNOMED CT'
                  )
              ),
              'Status' => array(
                  'Text' => 'Active'
              ),
              'Source' => array(
                  'Actor' => array(
                      'ActorID' => $softwareGUID
                  )
              ),
              'Agent' => array(
                  'Products' => array(
                      'Product' => array(
                          array( // Product 1
                              'CCRDocumentObjectID' => UUID::v4(),
                              'Source' => array(
                                  'Actor' => array(
                                      'ActorID' => $softwareGUID
                                  )
                              ),
                              'Product' => array(
                                  'ProductName' => array(
                                      'Text' => 'Penicillin',
                                      'Code' => array(
                                          'Value' => '70618',
                                          'CodingSystem' => 'RxNorm'
                                      )
                                  )
                              )
                          )
                      )
                  )
              ),
              'Reaction' => array(
                  'Description' => array(
                      'Text' => 'Hives',
                      'Code' => array(
                          'Value' => '247472004',
                          'CodingSystem' => 'SNOMED CT'
                      )
                  )
              )
          ),
          array( // Alert 2
              'CCRDocumentObjectID' => UUID::v4(),
              'Description' => array(
                  'Text' => 'Adverse reaction to substance',
                  'Code' => array(
                      'Value' => '282100009',
                      'CodingSystem' => 'SNOMED CT'
                  )
              ),
              'Status' => array(
                  'Text' => 'Active'
              ),
              'Source' => array(
                  'Actor' => array(
                      'ActorID' => $softwareGUID
                  )
              ),
              'Agent' => array(
                  'Products' => array(
                      'Product' => array(
                          array( // Product 1
                              'CCRDocumentObjectID' => UUID::v4(),
                              'Source' => array(
                                  'Actor' => array(
                                      'ActorID' => $softwareGUID
                                  )
                              ),
                              'Product' => array(
                                  'ProductName' => array(
                                      'Text' => 'Aspirin',
                                      'Code' => array(
                                          'Value' => '1191',
                                          'CodingSystem' => 'RxNorm'
                                      )
                                  )
                              )
                          )
                      )
                  )
              ),
              'Reaction' => array(
                  'Description' => array(
                      'Text' => 'Wheezing',
                      'Code' => array(
                          'Value' => '56018004',
                          'CodingSystem' => 'SNOMED CT'
                      )
                  )
              )
          ),
          array( // Alert 3
              'CCRDocumentObjectID' => UUID::v4(),
              'Description' => array(
                  'Text' => 'Adverse reaction to substance',
                  'Code' => array(
                      'Value' => '282100009',
                      'CodingSystem' => 'SNOMED CT'
                  )
              ),
              'Status' => array(
                  'Text' => 'Active'
              ),
              'Source' => array(
                  'Actor' => array(
                      'ActorID' => $softwareGUID
                  )
              ),
              'Agent' => array(
                  'Products' => array(
                      'Product' => array(
                          array( // Product 1
                              'CCRDocumentObjectID' => UUID::v4(),
                              'Source' => array(
                                  'Actor' => array(
                                      'ActorID' => $softwareGUID
                                  )
                              ),
                              'Product' => array(
                                  'ProductName' => array(
                                      'Text' => 'Codeine',
                                      'Code' => array(
                                          'Value' => '2670',
                                          'CodingSystem' => 'RxNorm'
                                      )
                                  )
                              )
                          )
                      )
                  )
              ),
              'Reaction' => array(
                  'Description' => array(
                      'Text' => 'Nausea',
                      'Code' => array(
                          'Value' => '73879007',
                          'CodingSystem' => 'SNOMED CT'
                      )
                  )
              )
          )
      )
  )
);

/**
 * Social History section
 * ----------------------
 * This section contains data defining the patient’s occupational, personal (e.g. lifestyle), social, and
 * environmental history and health risk factors, as well as administrative data such as marital status, race,
 * ethnicity and religious affiliation. Social history can have significant influence on a patient’s physical,
 * psychological and emotional health and wellbeing so should be considered in the development of a
 * complete record.
 */
$socialHistory = array(
    'SocialHistory' => array(
        'SocialHistoryElement' => array(
            array(
                'CCRDocumentObjectID' => UUID::v4(),
                'Type' => array(
                    'Text' => 'Smoking',
                    'Code' => array(
                        'Value' => '230056004',
                        'CodingSystem' => 'SNOMED CT'
                    )
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'Episodes' => array(
                    'Episode' => array(
                        array(
                            'CCRDocumentObjectID' => UUID::v4(),
                            'DateTime' => array(
                                'DateTimeRange' => array(
                                    'BeginRange' => array(
                                        'ExactDateTime' => '1947'
                                    ),
                                    'EndRange' => array(
                                        'ExactDateTime' => '1972'
                                    )
                                )
                            ),
                            'Description' => array(
                                'Text' => '1 pack per day'
                            ),
                            'Source' => array(
                                'Actor' => array(
                                    'ActorID' => $softwareGUID
                                )
                            )
                        ),
                        array(
                            'CCRDocumentObjectID' => UUID::v4(),
                            'DateTime' => array(
                                'DateTimeRange' => array(
                                    'BeginRange' => array(
                                        'ExactDateTime' => '1973'
                                    )
                                )
                            ),
                            'Description' => array(
                                'Text' => 'None'
                            ),
                            'Source' => array(
                                'Actor' => array(
                                    'ActorID' => $softwareGUID
                                )
                            )
                        ),
                        'Source' => array(
                            'Actor' => array(
                                'ActorID' => $softwareGUID
                            )
                        )
                    )
                )
            ),
            array(
                'CCRDocumentObjectID' => UUID::v4(),
                'DateTime' => array(
                    'DateTimeRange' => array(
                        'BeginRange' => array(
                            'ExactDateTime' => '1973'
                        )
                    )
                ),
                'Type' => array(
                    'Text' => 'ETOH Use',
                    'Code' => array(
                        'Value' => '160573003',
                        'CodingSystem' => 'SNOMED CT'
                    )
                ),
                'Description' => array(
                    'Text' => 'None'
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                )
            )
        )
    )
);

/**
 * Family History section
 * ----------------------
 * This section contains data defining the patient’s genetic relatives in terms of possible or relevant health risk
 * factors that have a potential impact on the patient’s healthcare risk profile.
 */
$familyHistoryProblems = array(
    'FamilyHistory' => array(
        'FamilyProblemHistory' => array(
            array(
                'CCRDocumentObjectID' => UUID::v4(),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'FamilyMember' => array(
                    'ActorID' =>$familyMemberLink1,
                    'ActorRole' => array(
                        'Text' => 'Father'
                    ),
                    'HealthStatus' => array(
                        'Description' => array(
                            'Text' => 'Deceased'
                        ),
                        'CauseOfDeath' => 'Yes',
                        'Source' => array(
                            'Actor' => array(
                                'ActorID' => $softwareGUID
                            )
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                ),
                'Problem' => array(
                    'Description' => array(
                        'Text' => 'Myocardial Infarction',
                        'Code' => array(
                            'Value' => '22298006',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Episodes' => array(
                        'Number' => '1',
                        'Episode' => array(
                            'CCRDocumentObjectID' => UUID::v4(),
                            'DateTime' => array(
                                'Type' => array(
                                    'Text' => 'Age At Onset'
                                ),
                                'Age' => array(
                                    'Value' => '57',
                                    'Units' => array(
                                        'Unit' => 'Years'
                                    )
                                )
                            ),
                            'Source' => array(
                                'Actor' => array(
                                    'ActorID' => $softwareGUID
                                )
                            )
                        ),
                        'Source' => array(
                            'Actor' => array(
                                'ActorID' => $softwareGUID
                            )
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                )
            ),
            array(
                'CCRDocumentObjectID' => UUID::v4(),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'FamilyMember' => array(
                    'ActorID' =>$familyMemberLink1,
                    'ActorRole' => array(
                        'Text' => 'Father'
                    ),
                    'HealthStatus' => array(
                        'Description' => array(
                            'Text' => 'Deceased'
                        ),
                        'CauseOfDeath' => 'No',
                        'Source' => array(
                            'Actor' => array(
                                'ActorID' => $softwareGUID
                            )
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                ),
                'Problem' => array(
                    'Description' => array(
                        'Text' => 'Hypertension',
                        'Code' => array(
                            'Value' => '59621000',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Episodes' => array(
                        'Number' => '1',
                        'Episode' => array(
                            'CCRDocumentObjectID' => UUID::v4(),
                            'DateTime' => array(
                                'Type' => array(
                                    'Text' => 'Age At Onset'
                                ),
                                'Age' => array(
                                    'Value' => '40',
                                    'Units' => array(
                                        'Unit' => 'Years'
                                    )
                                )
                            ),
                            'Source' => array(
                                'Actor' => array(
                                    'ActorID' => $softwareGUID
                                )
                            )
                        ),
                        'Source' => array(
                            'Actor' => array(
                                'ActorID' => $softwareGUID
                            )
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                )
            ),
            array(
                'CCRDocumentObjectID' => UUID::v4(),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'FamilyMember' => array(
                    'ActorID' =>$familyMemberLink2,
                    'ActorRole' => array(
                        'Text' => 'Mother'
                    ),
                    'HealthStatus' => array(
                        'Description' => array(
                            'Text' => 'Alive And Well'
                        ),
                        'CauseOfDeath' => 'No',
                        'Source' => array(
                            'Actor' => array(
                                'ActorID' => $softwareGUID
                            )
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                ),
                'Problem' => array(
                    'Description' => array(
                        'Text' => 'Asthma',
                        'Code' => array(
                            'Value' => '195967001',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Episodes' => array(
                        'Number' => '1',
                        'Episode' => array(
                            'CCRDocumentObjectID' => UUID::v4(),
                            'DateTime' => array(
                                'Type' => array(
                                    'Text' => 'Age At Onset'
                                ),
                                'Age' => array(
                                    'Value' => '30',
                                    'Units' => array(
                                        'Unit' => 'Years'
                                    )
                                )
                            ),
                            'Source' => array(
                                'Actor' => array(
                                    'ActorID' => $softwareGUID
                                )
                            )
                        ),
                        'Source' => array(
                            'Actor' => array(
                                'ActorID' => $softwareGUID
                            )
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                )
            )
        )
    )
);

/**
 * Problems section
 * ----------------
 * This section lists and describes all relevant clinical problems at the time the summary is generated. At a
 * minimum, all pertinent current and historical problems should be listed. CDA R2 represents problems as
 * Observations.
 */
$problems = array(
    'Problems' => array(
        'Problem' => array(
            array( // Problem 1
                'CCRDocumentObjectID' => UUID::v4(),
                'DateTime' => array(
                    'ExactDateTime' => '1950'
                ),
                'Description' => array(
                    'Text' => 'Asthma',
                    'Code' => array(
                        'Value' => '195967001',
                        'CodingSystem' => 'SNOMED CT'
                    )
                ),
                'Status' => array(
                    'Text' => 'Active',
                    'Code' => array(
                        'Value' => '55561003',
                        'CodingSystem' => 'SNOMED CT'
                    )
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                )
            ),
            array( // Problem 2
                'CCRDocumentObjectID' => UUID::v4(),
                'DateTime' => array(
                    'ExactDateTime' => '1950'
                ),
                'Description' => array(
                    'Text' => 'Pneumonia',
                    'Code' => array(
                        'Value' => '233604007',
                        'CodingSystem' => 'SNOMED CT'
                    )
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                ),
                'Episodes' => array(
                    'Episode' => array(
                        array(
                            'CCRDocumentObjectID' => UUID::v4(),
                            'DateTime' => array(
                                'ExactDateTime' => '1950'
                            ),
                            'Status' => array(
                                'Text' => 'Resolved',
                                'Code' => array(
                                    'Value' => '413322009',
                                    'CodingSystem' => 'SNOMED CT'
                                )
                            ),
                            'Source' => array(
                                'Actor' => array(
                                    'ActorID' => $softwareGUID
                                )
                            )
                        ),
                        array(
                            'CCRDocumentObjectID' => UUID::v4(),
                            'DateTime' => array(
                                'ExactDateTime' => '1950'
                            ),
                            'Status' => array(
                                'Text' => 'Resolved',
                                'Code' => array(
                                    'Value' => '413322009',
                                    'CodingSystem' => 'SNOMED CT'
                                )
                            ),
                            'Source' => array(
                                'Actor' => array(
                                    'ActorID' => $softwareGUID
                                )
                            )
                        )
                    )
                )
            ),
            array( // Problem 3
                'CCRDocumentObjectID' => UUID::v4(),
                'DateTime' => array(
                    'ExactDateTime' => '1997-01'
                ),
                'Description' => array(
                    'Text' => 'Myocardial infarction',
                    'Code' => array(
                        'Value' => '22298006',
                        'CodingSystem' => 'SNOMED CT'
                    )
                ),
                'Status' => array(
                    'Text' => 'Resolved',
                    'Code' => array(
                        'Value' => '413322009',
                        'CodingSystem' => 'SNOMED CT'
                    )
                ),
                'Source' => array(
                    'Actor' => array(
                        'ActorID' => $softwareGUID
                    )
                )
            )
        )
    )
);

/**
 * Functional Status section
 * -------------------------
 * Functional Status describes the patient’s status of normal functioning at the time the Care Record was
 * created. Functional statuses include information regarding the patient relative to:
 * • Ambulatory ability
 * • Mental status or competency
 * • Activities of Daily Living (ADLs), including bathing, dressing, feeding, grooming
 * • Home / living situation having an effect on the health status of the patient
 * • Ability to care for self
 * • Social activity, including issues with social cognition, participation with friends and acquaintances other than family members
 * • Occupation activity, including activities partly or directly related to working, housework or
 * • volunteering, family and home responsibilities or activities related to home and family
 * • Communication ability, including issues with speech, writing or cognition required for communication
 * Perception, including sight, hearing, taste, skin sensation, kinesthetic sense, proprioception, or balance
 * Any deviation from normal function that the patient displays and is recorded in the record should be
 * included. Of particular interest are those limitations that would in any way interfere with self care or the
 * medical therapeutic process. In addition, an improvement, any change in or noting that the patient has
 * normal functioning status is also valid for inclusion.
 */
$functional = array(
    'FunctionalStatus' => array(
        'Function' => array(
            'CCRDocumentObjectID' => UUID::v4(),
            'Type' => array(
                'Text' => 'Ambulatory Status'
            ),
            'Source' => array(
                'Actor' => array(
                    'ActorID' => $softwareGUID
                )
            ),
            'Problem' => array(
                array(
                    'CCRDocumentObjectID' => UUID::v4(),
                    'DateTime' => array(
                        'ExactDateTime' => '1999-11-07'
                    ),
                    'Description' => array(
                        'Text' => 'Dependence on cane',
                        'Code' => array(
                            'Value' => '105504002',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Status' => array(
                        'Text' => 'Active',
                        'Code' => array(
                            'Value' => '55561003',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                ),
                array(
                    'CCRDocumentObjectID' => UUID::v4(),
                    'DateTime' => array(
                        'ExactDateTime' => '1999'
                    ),
                    'Description' => array(
                        'Text' => 'Memory impairment',
                        'Code' => array(
                            'Value' => '386807006',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Status' => array(
                        'Text' => 'Active',
                        'Code' => array(
                            'Value' => '55561003',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                )
            )
        )
    )
);

/**
 * Supporters
 * ----------
 * Represents the patient’s sources of support such as immediate family, relatives, and guardian at the time the
 * summarization is generated. Support information also includes next of kin, caregivers, and support
 * organizations. At a minimum, key support contacts relative to healthcare decisions, including next of kin,
 * should be included.
 */
$supporters = array(
    'Support' => array(
        'SupportProvider' => array(
            array(
                'ActorID' => $family1GUID,
                'ActorRole' => array(
                    'Text' => 'Guardian'
                ),
            ),
            array(
                'ActorID' => $family2GUID,
                'ActorRole' => array(
                    'Text' => 'Next of Kin'
                )
            )
        )
    )
);

/**
 *   Advance Directives section
 * ----------------------------
 * This section contains data defining the patient’s advance directives and any reference to supporting
 * documentation. The most recent and up-to-date directives are required, if known, and should be listed in as
 * much detail as possible. This section contains data such as the existence of living wills, healthcare proxies,
 * and CPR and resuscitation status. If referenced documents are available, they can be included in the CCD
 * exchange package.
 */
$advanceDirectives = array(
    'AdvanceDirectives' => array(
        'AdvanceDirective' => array(
            'CCRDocumentObjectID' => UUID::v4(),
            'DateTime' => array(
                'Type' => array(
                    'Text' => 'Verified With Patient'
                ),
                'ExactDateTime' => '1999-11-07'
            ),
            'Type' => array(
                'Text' => 'Resuscitation status',
                'Code' => array(
                    'Value' => '304251008',
                    'CodingSystem' => 'SNOMED CT'
                )
            ),
            'Description' => array(
                'Text' => 'Do not resuscitate',
                'Code' => array(
                    'Value' => '304253006',
                    'CodingSystem' => 'SNOMED CT'
                )
            ),
            'Status' => array(
                'Text' => 'Current and verified',
                'Code' => array(
                    'Value' => '15240007',
                    'CodingSystem' => 'SNOMED CT'
                )
            ),
            'Source' => array(
                'Actor' => array(
                    'ActorID' => $softwareGUID
                )
            ),
            'ReferenceID' => $pdfDocumentGUID
        )
    )
);

/**
 * Payers Section
 * --------------
 * Payers contains data on the patient’s payers, whether a ‘third party’ insurance, self-pay, other payer or
 * guarantor, or some combination of payers, and is used to define which entity is the responsible fiduciary for
 * the financial aspects of a patient’s care.
 */
$players = array(
    'Payers' => array(
        'Payer' => array(
            'CCRDocumentObjectID' => UUID::v4(),
            'Type' => array(
                'Text' => 'Extended healthcare',
                'Code' => array(
                    'Value' => 'EHCPOL',
                    'CodingSystem' => 'ActCode'
                )
            ),
            'Source' => array(
                'Actor' => array(
                    'ActorID' => $softwareGUID
                )
            ),
            'PaymentProvider' => array(
                'ActorID' => $paymentProviderGUID
            ),
            'Subscriber' => array(
                'ActorID' => $subscriberGUID,
                'ActorRole' => array(
                    'Text' => 'Covered party'
                )
            ),
            'Authorizations' => array(
                'Authorization' => array(
                    'CCRDocumentObjectID' => UUID::v4(),
                    'Description' => array(
                        'Text' => 'Colonoscopy',
                        'Code' => array(
                            'Value' => '73761001',
                            'CodingSystem' => 'SNOMED CT'
                        )
                    ),
                    'Source' => array(
                        'Actor' => array(
                            'ActorID' => $softwareGUID
                        )
                    )
                )
            )
        )
    )
);

/**
 * The header and body render
 */
$ccrArray = array(
    '@attributes' => array(
        'xmlns' => 'urn:astm-org:CCR'
    ),
    'CCRDocumentObjectID' => UUID::v4(),
    'Language' => array(
        'Text' => 'English',
        'Code' => array(
            'Value' => 'en-US',
            'CodingSystem' => 'IETF1766'
        )
    ),
    'Version' => 'V1.0',
    'DateTime' => array(
        'ExactDateTime' => date('Y-m-d TH:i:s')
    ),
    'Patient' => array(
        'ActorID' => $patientGUID
    ),
    'From' => array(
        'ActorLink' => array(
            'ActorID' => $softwareGUID,
            'ActorRole' => array(
                'Text' => 'author'
            )
        )
    ),
    'Purpose' => array(
        'Description' => array(
            'Text' => 'Transfer of care',
            'Code' => array(
                'Value' => '308292007',
                'CodingSystem' => 'SNOMED CT'
            )
        )
    ),
    'Body' => array(
        $players,
        $advanceDirectives,
        $supporters,
        $functional,
        $problems,
        $familyHistoryProblems,
        $socialHistory,
        $alerts,
        $medications
    )
);

Array2XML::init('1.0', 'UTF-8', true, array('xml-stylesheet' => 'type="text/xsl" href="'.$_SESSION['url'].'/lib/CCRCDA/schema/ccr.xsl"'));
$xml = Array2XML::createXML('ContinuityOfCareRecord', $ccrArray);

header('Content-type: application/xml');
echo $xml->saveXML();

if($_REQUEST['action'] == 'viewccr')
{

}