<?php

/**
 * 3.98	Service Delivery Location
 *
 * This clinical statement represents the location of a service event where an act,
 * observation or procedure took place.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class serviceDeliveryLocation
 * @package LevelEntry
 */
class serviceDeliveryLocation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'ServiceDeliveryLocation' => [
                'code' => 'SHALL be selected from ValueSet HealthcareServiceLocation',
                'codeSystemName' => 'SHALL be selected from ValueSet HealthcareServiceLocation',
                'displayName' => 'SHALL be selected from ValueSet HealthcareServiceLocation',
                'addr' => [
                    'use' => 'SHOULD contain zero or more [0..*] addr',
                    'streetAddressLine' => 'SHOULD contain zero or more [0..*] addr',
                    'city' => 'SHOULD contain zero or more [0..*] addr',
                    'state' => 'SHOULD contain zero or more [0..*] addr',
                    'postalCode' => 'SHOULD contain zero or more [0..*] addr',
                    'country' => 'SHOULD contain zero or more [0..*] addr'
                ],
                'telecom' => [
                    'use' => 'SHOULD contain zero or more [0..*] telecom',
                    'value' => 'SHOULD contain zero or more [0..*] telecom'
                ],
                'locationName' => 'MAY contain zero or one [0..1] name'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|Exception
     */
    public static function Insert($PortionData, $CompleteData)
    {
        try {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'participantRole' => [
                    '@attributes' => [
                        'classCode' => 'SDLOC'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.32'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'addr' => Component::addr(
                        $PortionData['addr']['use'],
                        $PortionData['addr']['streetAddressLine'],
                        $PortionData['addr']['city'],
                        $PortionData['addr']['state'],
                        $PortionData['addr']['postalCode'],
                        $PortionData['addr']['country']
                    ),
                    'telecom' => Component::telecom(
                        $PortionData['telecom']['use'],
                        $PortionData['telecom']['value']
                    ),
                    'playingEntity' => [
                        '@attributes' => [
                            'classCode' => 'PLC'
                        ],
                        'name' => $PortionData['locationName']
                    ]
                ]
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
