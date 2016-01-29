<?php

/**
 * 3.14	Characteristics of Home Environment (NEW)
 *
 * This template represents the patient's home environment including, but not limited to, type of residence
 * (trailer, single family home, assisted living), living arrangement (e.g., alone, with parents), and
 * housing status (e.g., evicted, homeless, home owner).
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
class characteristicsOfHomeEnvironment
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']) &&
            !isset($PortionData['systemCodeName']) &&
            !isset($PortionData['displayName'])){
            throw new Exception ('SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet
            Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC (CONF:28823).');
        }
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
            'CharacteristicsOfHomeEnvironment' => [
                'code' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC',
                'systemCodeName' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC',
                'displayName' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.109'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => '224249004',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'displayName' => 'Characteristics of Home Environment'
                        ]
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => 'completed'
                        ]
                    ],
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId( $PortionData['systemCodeName'] ),
                        'codeSystemName' => $PortionData['systemCodeName'],
                        'displayName' => $PortionData['displayName']
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
