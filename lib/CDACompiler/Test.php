<?php

require_once('CDA.php');

$CDA = new CDA();

$data = array(
    'ClinicalDocument' => [
        'version'=>'2',
        'realm' => 'US',
        'title' => 'Visit Summary',
        'effectiveDate' => date('Ymdhm-0400'),
        'confidentiality' => 'N',
        'languageCode' => 'US',
        'languageName' => 'USA',
        'code' => '34117-2',
        'documentName' => 'History and Physical Note',
        'codeSystemName' => 'LOINC'
    ],
    'Patient' => [
        'name' => [
            'given' => 'Eve',
            'family' => 'Everywoman',
            'familyQualifier' => 'BR'
        ],
        'socialSecurity' => '444-555-7777',
        'gender' => [
            'code' => 'F',
            'displayName' => 'Female',
            'codeSystemName' => 'AdministrativeGender'
        ],
        'address' => [
            'use' => 'HP',
            'streetAddressLine' => '2222 Home Street',
            'city' => 'Beaverton',
            'state' => 'PR',
            'postalCode' => '97867',
            'country' => 'US'
        ],
        'telecom' => [
            'use' => 'HP',
            'value' => 'tel: 777-777-7777'
        ],
        'dateOfBirth' => '19770113',
        'marital' => [
            'code' => 'M',
            'displayName' => 'Married',
            'codeSystemName' => 'MaritalStatusCode'
        ],
        'religious' => [
            'code' => '1013',
            'displayName' => 'Christian (non-Catholic, non-specific)',
            'codeSystemName' => 'HL7 Religious Affiliation'
        ],
        'race' => [
            'code' => '2106-3',
            'displayName' => 'White',
            'codeSystemName' => 'Race & Ethnicity - CDC'
        ],
        'ethnic' => [
            'code' => '2186-5',
            'displayName' => 'Not Hispanic or Latino',
            'codeSystemName' => 'Race & Ethnicity - CDC'
        ],
        'birthplace' => [
            'address' => [
                'streetAddressLine' => '4444 Home Street',
                'city' => 'Beaverton',
                'state' => 'OR',
                'postalcode' => '97867',
                'country' => 'US'
            ]
        ],
        'guardians' => [ // <-- This block can be one o more
            'code' => 'POWATT',
            'displayName' => 'Power of Attorney',
            'codeSystemName' => 'ResponsibleParty',
            'addr' => [
                'use' => 'HP',
                'streetAddressLine' => '2222 Home Street',
                'city' => 'Beaverton',
                'state' => 'OR',
                'postalCode' => '97867',
                'country' => 'US'
            ],
        ],
        'guardianPerson' => [
            'given' => 'Boris',
            'family' => 'Betterhalf'
        ],
        'language' => [
            'primary' => 'eng',
            'ability' => 'ESP',
            'proficiency' => 'G'
        ]
    ],
    // The location of the Clinic and name
    'Clinic' => [
        'uin' => '219BX',
        'npi' => '1234567890',
        'name' => 'The DoctorsTogether Physician Group',
        'telecom' => [
            'use' => 'WP',
            'value' => 'tel: +(555)-555-5000'
        ],
        'address' => [
            'streetAddressLine' => '1007 Health Drive',
            'city' => 'Portland',
            'state' => 'OR',
            'postalCode' => '99123',
            'country' => 'US'
        ]
    ],
    // Providers that participated in the wellness of the patient
    'Providers' => array(
        0 => [
            'npi' => '5555555555',
            'directMessageAddress' => 'adameveryman@direct.sampleHISP.com',
            'role' => 'ONESELF',
            'functionCode' => 'PCP',
            'taxonomy' => '207QA0505X',
            'RelationshipCode' => 'ONESELF',
            'RelationshipName' => 'Oneself',
            'codeSystemName' => 'PersonalAndLegalRelationshipRole',
            'address' => [
                'use' => 'HP',
                'streetAddressLine' => '2222 Home Street',
                'city' => 'Boston',
                'state' => 'MA',
                'postalCode' => '02368',
                'country' => 'US'
            ],
            'telecom' => [
                0 => [
                    'use' => 'WP',
                    'value' => 'provider@gmail.com'
                ],
                2 => [
                    'use' => 'HP',
                    'value' => 'tel:(555)555-2004'
                ]
            ],
            'name' => [
                'given' => 'Adam',
                'family' => 'Everyman'
            ]
        ],
        1 => [
            'npi' => '4444444444',
            'directMessageAddress' => 'adameveryman@direct.sampleHISP.com',
            'role' => 'ONESELF',
            'functionCode' => 'PCP',
            'taxonomy' => '207QA0505X',
            'RelationshipCode' => 'ONESELF',
            'RelationshipName' => 'Oneself',
            'codeSystemName' => 'PersonalAndLegalRelationshipRole',
            'address' => [
                'use' => 'HP',
                'streetAddressLine' => '2222 Home Street',
                'city' => 'Boston',
                'state' => 'MA',
                'postalCode' => '02368',
                'country' => 'US'
            ],
            'telecom' => [
                0 => [
                    'use' => 'WP',
                    'value' => 'provider@gmail.com'
                ],
                2 => [
                    'use' => 'HP',
                    'value' => 'tel: (555)555-2004'
                ]
            ],
            'name' => [
                'given' => 'Segundito',
                'family' => 'Doctorcito'
            ]
        ]
    ),
    // When sending the CDA Document via Direct Messaging, you must use this
    // as the destination.
    'destinationProvider' => [
        'npi' => '5555555555',
        'directMessageAddress' => 'adameveryman@direct.sampleHISP.com',
        'clinicName' => 'Clinic Taquitos',
        'address' => [
            'use' => 'HP',
            'streetAddressLine' => '2222 Home Street',
            'city' => 'Boston',
            'state' => 'MA',
            'postalCode' => '02368',
            'country' => 'US'
        ],
        'telecom' => [
            'use' => 'HP',
            'value' => 'tel:(555)555-2004'
        ],
        'name' => [
            'given' => 'Adam',
            'family' => 'Everyman'
        ]
    ],
    // This group means the user that generated the CDA
    'User' => [
        'datetime' => '201209151030-0800',
        'address' => [
            'use' => 'HP',
            'streetAddressLine' => '2222 Home Street',
            'city' => 'Boston',
            'state' => 'MA',
            'postalCode' => '02368',
            'country' => 'US'
        ],
        'telecom' => [
            'use' => 'WP',
            'value' => 'tel: (555)555-2004'
        ],
        'name' => [
            'given' => 'Adam',
            'family' => 'Everyman'
        ]
    ],
    'UserCreated' => [
        'datetime' => '201209151030-0800',
        'directMessageAddress' => 'adameveryman@direct.sampleHISP.com',
        'who' => 'ONESELF',
        'address' => [
            'use' => 'HP',
            'streetAddressLine' => '2222 Home Street',
            'city' => 'Boston',
            'state' => 'MA',
            'postalCode' => '02368',
            'country' => 'US'
        ],
        'telecom' => [
            'use' => 'WP',
            'value' => 'tel:(555)555-2004'
        ],
        'name' => [
            'given' => 'Adam',
            'family' => 'Everyman'
        ]
    ],
    'Participants' => [
        0 => [
            'startDate' => '20100101', // Start of the relationship
            'endDate' => '20150101', // End of the relationship
            'relationship' => 'MTH', // Mother
            'address' => [
                'use' => 'HP',
                'streetAddressLine' => '2222 Home Street',
                'city' => 'Boston',
                'state' => 'MA',
                'postalCode' => '02368',
                'country' => 'US'
            ],
            'telecom' => [
                'use' => 'HP',
                'value' => 'tel:(555)555-2004'
            ],
            'name' => [
                'given' => 'Mommy',
                'family' => 'Guitierrez'
            ]
        ],
        1 => [
            'startDate' => '20100101', // Start of the relationship
            'endDate' => '20150101', // End of the relationship
            'relationship' => 'FTH', // Mother
            'address' => [
                'use' => 'HP',
                'streetAddressLine' => '2222 Home Street',
                'city' => 'Boston',
                'state' => 'MA',
                'postalCode' => '02368',
                'country' => 'US'
            ],
            'telecom' => [
                'use' => 'HP',
                'value' => 'tel:(555)555-2004'
            ],
            'name' => [
                'given' => 'Father',
                'family' => 'Hernan'
            ]
        ]
    ],
    'AdvanceDirectives' => [
        0 => [
            'date' => date('Ymd'),
            'taxonomy' => '163W00000X',
            'name' => [
                'given' => 'Registered Nurse',
                'family' => 'Hellen'
            ],
            'observations' => [
                0 => [
                    'observationDate' => '20150101',
                    'statusCode' => 'completed',
                    'beginDate' => '20150101',
                    'endDate' => 'NA',
                    'didCode' => '439569004',
                    'didCodeSystemName' => 'SNOMED-CT',
                    'didDisplayName' => 'Resuscitation',
                    'didText' => 'Cardiopulmonary resuscitation: for a patient in cardiac or respiratory arrest',
                    'resultCode' => '304253006',
                    'resultCodeSystemName' => 'SNOMED-CT',
                    'resultDisplayName' => 'Not for resuscitation',
                    'resultText' => 'Do not resuscitate',
                    'providerTaxonomy' => 'Registered nurse',
                    'providerTaxonomyCode' => '163W00000X',
                    'providerTaxonomySystem' => 'NUCC',
                    'address' => [
                        'use' => 'HP',
                        'streetAddressLine' => '2222 Home Street',
                        'city' => 'Boston',
                        'state' => 'MA',
                        'postalCode' => '02368',
                        'country' => 'US'
                    ],
                    'telecom' => [
                        'use' => 'WP',
                        'phone' => 'tel:(995)555-1006'
                    ]
                ]
            ]
        ]
    ],
    'Allergies' => [
        0 => [
            'status' => 'active',
            'firstDate' => '20150101',
            'authors' => [
                0 => [
                    'name' => [
                        'given' => 'Megamind',
                        'family' => 'Sinestro'
                    ]
                ]
            ]
        ]
    ],
    'VitalSigns' => [
        0 => []
    ]

);

echo $CDA->Compile($data);


