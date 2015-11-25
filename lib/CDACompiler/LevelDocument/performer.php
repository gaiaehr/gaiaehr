<?php

/**
 * 1.3.3	performer
 *
 * The serviceEvent/performer represents the healthcare providers involved in the current or pertinent historical
 * care of the patient. Preferably, the patient’s key healthcare providers would be listed, particularly their
 * primary physician and any active consulting physicians, therapists, and counselors.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class performer
 * @package LevelDocument
 */
class performer
{
    /**
     * @param $Data
     */
    private static function Validate($Data)
    {

    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'npi' => '',
            'displayName' => '',
            'codeSystemName' => '',
            'taxonomyCode' => '',
            'taxonomyDisplayName' => '',
            'taxonomyCodeSystemName' => '',
            'address' => [
                'use' => '',
                'streetAddressLine' => '',
                'city' => '',
                'state' => '',
                'postalCode' => '',
                'country' => ''
            ],
            'telecom' => [
                'use' => '',
                'value' => ''
            ],
            'name' => [
                'prefix' => '',
                'prefixQualifier' => '',
                'given' => '',
                'givenQualifier' => '',
                'family' => '',
                'familyQualifier' => '',
                'name' => '',
                'nameQualifier' => ''
            ],
            'Clinic' => [
                'telecom' => [
                    'use' => '',
                    'value' => ''
                ],
                'address' => [
                    'use' => '',
                    'streetAddressLine' => '',
                    'city' => '',
                    'state' => '',
                    'postalCode' => '',
                    'country' => ''
                ]
            ]
        ];
    }

    /**
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try {

            // Validate first
            self::Validate($Data);

            // Build the section
            $Document = [
                '@attributes' => [
                    'typeCode' => 'PRF'
                ],
                'functionCode' => [
                    '@attributes' => [
                        'code' => $Data['code'],
                        'codeSystem' => Utilities::CodingSystemId($Data['codeSystemName']),
                        'codeSystemName' => $Data['codeSystemName'],
                        'displayName' => $Data['displayName']
                    ]
                ],
                'assignedEntity' => [
                    'id' => [
                        '@attributes' => [
                            'root' => '2.16.840.1.113883.4.6',
                            'extension' => $Data['npi']
                        ]
                    ],
                    'code' => [
                        '@attributes' => [
                            'code' => $Data['taxonomyCode'],
                            'displayName' => $Data['taxonomyDisplayName'],
                            'codeSystem' => Utilities::CodingSystemId($Data['taxonomyCodeSystemName']),
                            'codeSystemName' => $Data['taxonomyCodeSystemName']
                        ]
                    ],
                    'addr' => Component::addr(
                        $Data['address']['use'],
                        $Data['address']['streetAddressLine'],
                        $Data['address']['city'],
                        $Data['address']['state'],
                        $Data['address']['postalCode'],
                        $Data['address']['country']
                    ),
                    'telecom' => Component::telecom(
                        $Data['telecom']['use'],
                        $Data['telecom']['value']
                    ),
                    'assignedPerson' => [
                        'name' => Component::name(
                            $Data['name']['prefix'],
                            $Data['name']['prefixQualifier'],
                            $Data['name']['given'],
                            $Data['name']['givenQualifier'],
                            $Data['name']['family'],
                            $Data['name']['familyQualifier'],
                            $Data['name']['name'],
                            $Data['name']['nameQualifier']
                        )
                    ],
                    'representedOrganization' => [
                        'id' => [
                            'root' => '1.2.16.840.1.113883.4.6',
                            'extension' => '219BX'
                        ],
                        'name' => $Data['Clinic']['name'],
                        'telecom' => Component::telecom(
                            $Data['Clinic']['telecom']['use'],
                            $Data['Clinic']['telecom']['value']
                        ),
                        'addr' => Component::addr(
                            $Data['Clinic']['address']['use'],
                            $Data['Clinic']['address']['streetAddressLine'],
                            $Data['Clinic']['address']['city'],
                            $Data['Clinic']['address']['state'],
                            $Data['Clinic']['address']['postalCode'],
                            $Data['Clinic']['address']['country']
                        )
                    ]
                ]
            ];

            return $Document;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }
}

