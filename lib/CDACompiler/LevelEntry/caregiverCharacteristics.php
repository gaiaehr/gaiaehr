<?php

/**
 * 3.13	Caregiver Characteristics
 *
 * This clinical statement represents a caregiver’s willingness to provide care and the abilities of that
 * caregiver to provide assistance to a patient in relation to a specific need.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class advanceDirectiveOrganizer
 * @package LevelEntry
 */
class caregiverCharacteristics
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['statusCode']))
            throw new Exception ('SHALL contain exactly one [1..1] statusCode (CONF:14233).');
        if(count($PortionData['Participant']) < 0)
            throw new Exception ('SHALL contain at least one [1..*] participant (CONF:14227).');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    public static function Structure(){
        return [
            'CaregiverCharacteristics' => [
                'statusCode' => 'SHALL contain exactly one [1..1] statusCode (CONF:14233).',
                'Participant' => [
                    0 => [
                        'code' => 'SHALL contain at least one [1..*] participant (CONF:14227).',
                        'codeSystemName' => 'System Name (LOINC, SNOMED-CT)',
                        'codeDisplayname' => 'Display Name (Mother, Father, ect.)'
                    ]
                ]
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
        try
        {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.72'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'statusCode' => [
                        '@attributes' => [
                            'code' => $PortionData['statusCode']
                        ]
                    ],
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => '422615001',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'displayName' => 'caregiver difficulty providing physical care'
                    ],
                    'participant' => self::participant($PortionData['Participant'])
                ]
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }


    // SHALL contain at least one [1..*] participant (CONF:14227).
    // Such participants SHALL contain exactly one [1..1] participantRole
    function participant($Participant)
    {
        foreach ($Participant as $Value)
        {
            $Entry['observation']['participant'][] = [
                '@attributes' => [
                    'typeCode' => 'IND'
                ],
                'participantRole' => [
                    '@attributes' => [
                        'classCode' => 'CAREGIVER'
                    ],
                    'code' => [
                        'code' => $Value['code'],
                        'codeSystem' => Utilities::CodingSystemId( $Value['codeSystemName'] ),
                        'codeSystemName' => $Value['codeDisplayname']
                    ]
                ]
            ];
        }
        return $Entry;
    }

}
