<?php

/**
 * 3.58	Number of Pressure Ulcers Observation
 *
 * This clinical statement enumerates the number of pressure ulcers observed in a particular stage.
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
class numberOfPressureUlcersObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['PressureUlcerStage']) < 0)
            throw new Exception('SHALL contain exactly one [1..1] entryRelationship');
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
            'NumberOfPressureUlcersObservation' => [
                'code' => 'SHOULD be selected from ValueSet Pressure Ulcer Stage',
                'codeSystemName' => 'SHOULD be selected from ValueSet Pressure Ulcer Stage',
                'displayName' => 'SHOULD be selected from ValueSet Pressure Ulcer Stage'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.76'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'code' => '2264892003',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'displayName' => 'Number of pressure ulcers'
                    ],
                    'statusCode' => Component::statusCode('completed')
                ]
            ];

            // MAY contain zero or one [0..1] participant
            // This observation SHALL contain exactly one [1..1] value with @xsi:type="CD",
            // where the code SHOULD be selected from ValueSet Pressure Ulcer Stage
            if(count($PortionData['PressureUlcerStage']) > 0){
                foreach($PortionData['PressureUlcerStage'] as $PressureUlcerStage)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        'observation' => [
                            '@attributes' => [
                                'typeCode' => 'OBS',
                                'moodCode' => 'EVN'
                            ],
                            'value' => [
                                'xsi:type' => 'CD',
                                'code' => $PressureUlcerStage['code'],
                                'codeSystem' => Utilities::CodingSystemId($PressureUlcerStage['codeSystemName']),
                                'displayName' => $PressureUlcerStage['displayName']
                            ]
                        ]
                    ];
                }
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
