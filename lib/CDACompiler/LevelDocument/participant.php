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
            'code' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
            'displayName' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
            'codeSystemName' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
            'Narrated' => 'This playingEntity/name MAY be used for the vehicle name in text, such as Normal Saline (CONF:10087)'
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
                'participant' => [
                    '@attributes' => [
                        'typeCode' => 'IND'
                    ],
                    'associatedEntity' => [
                        '@attributes' => [
                            'classCode' => 'NOK'
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => $Data['relationship']['code'],
                                'displayName' => $Data['relationship']['displayName'],
                                'codeSystem' => Utilities::CodingSystemId($Data['relationship']['codeSystemName']),
                                'codeSystemName' => $Data['relationship']['codeSystemName']
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
                        'telecom' => Component::telecom($Data['telecom']),
                        'name' => Component::name($Data['name'])
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] time (CONF:10004)
            if(isset($Data['startDate']) && isset($Data['endDate']))
            {
                $Document['time'] = [
                    '@attributes' => [
                        'xsi:type' => 'IVL_TS'
                    ],
                    'low' => $Data['startDate'],
                    'high' => $Data['endDate'],
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

