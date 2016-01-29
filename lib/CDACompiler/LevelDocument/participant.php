<?php

/**
 * 1.13.9	participant
 *
 * The participant element identifies other supporting participants, including parents, relatives, caregivers,
 * insurance policyholders, guarantors, and other participants related in some way to the patient.
 *
 * A supporting person or organization is an individual or an organization with a relationship to the patient.
 * A supporting person who is playing multiple roles would be recorded in multiple participants
 * (e.g., emergency contact and next-of-kin)
 *
 * 11.	MAY contain zero or more [0..*] participant (CONF:28703).
 *
 * Unless otherwise specified by the document specific header constraints, when participant/@typeCode is IND,
 * associatedEntity/@classCode SHALL be selected from ValueSet 2.16.840.1.113883.11.20.9.33
 * INDRoleclassCodes STATIC 2011-09-30
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class participant
 * @package LevelDocument
 */
class participant
{
    /**
     * @param $PortionData
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'Participant' => [
                'typeCode' => 'CodeSystem: HL7ParticipationType',
                'code' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
                'displayName' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
                'codeSystemName' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
                'Narrated' => 'This playingEntity/name MAY be used for the vehicle name in text, such as Normal Saline (CONF:10087)'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return array|Exception
     */
    public static function Insert($PortionData)
    {
        try {

            // Validate first
            self::Validate($PortionData);

            // Build the section
            $Document = [
                'participant' => [
                    '@attributes' => [
                        'typeCode' => $PortionData['typeCode']
                    ],
                    'associatedEntity' => [
                        '@attributes' => [
                            'classCode' => 'NOK'
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => $PortionData['relationship']['code'],
                                'displayName' => $PortionData['relationship']['displayName'],
                                'codeSystem' => Utilities::CodingSystemId($PortionData['relationship']['codeSystemName']),
                                'codeSystemName' => $PortionData['relationship']['codeSystemName']
                            ]
                        ],
                        'addr' => Component::addr(
                            $PortionData['address']['use'],
                            $PortionData['address']['streetAddressLine'],
                            $PortionData['address']['city'],
                            $PortionData['address']['state'],
                            $PortionData['address']['postalCode'],
                            $PortionData['address']['country']
                        ),
                        'telecom' => Component::telecom($Data['telecom']),
                        'name' => Component::name($Data['name'])
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] time (CONF:10004)
            if(isset($PortionData['startDate']) && isset($PortionData['endDate']))
            {
                $Document['time'] = [
                    '@attributes' => [
                        'xsi:type' => 'IVL_TS'
                    ],
                    'low' => $PortionData['startDate'],
                    'high' => $PortionData['endDate'],
                ];
            }

            return $Document;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}

