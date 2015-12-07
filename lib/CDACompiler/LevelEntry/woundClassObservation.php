<?php

/**
 * 3.110	Wound Class Observation
 *
 * NHSN patient safety protocol on this topic is an adaptation of (not a change to) the American College of
 * Surgeons (ACoS) definitions, which are the definitions used by SNOMED. Thus, SNOMED wound-class codes are
 * appropriate for use with this observation.
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
 * Class woundClassObservation
 * @package LevelEntry
 */
class woundClassObservation {

    /**
     * @param $PortionData
     * @throws Exception
     */
    public static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL be selected from ValueSet NHSNWoundClassCode');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL be selected from ValueSet NHSNWoundClassCode');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL be selected from ValueSet NHSNWoundClassCode');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     * @throws Exception
     */
    public static function Narrative($PortionData)
    {
    }

    public static function Structure(){
        return [
            'WoundClassObservation' => [
                'code' => 'SHALL be selected from ValueSet NHSNWoundClassCode',
                'codeSystemName' => 'SHALL be selected from ValueSet NHSNWoundClassCode',
                'displayName' => 'SHALL be selected from ValueSet NHSNWoundClassCode'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|\Exception|Exception
     */
    public static function Insert($PortionData, $CompleteData)
    {
        try{
            // Validate first
            self::Validate($PortionData);

            // Compose the segment
            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.5.2.1.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '420089007',
                            'codeSystem' => '2.16.840.1.113883.6.96'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ]
                ]
            ];

            return $Entry;
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }

}
