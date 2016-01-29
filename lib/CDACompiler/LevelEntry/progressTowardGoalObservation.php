<?php

/**
 * 3.87	Progress Toward Goal Observation (NEW)
 *
 * This template represents a patient's Progress Toward a Goal. It can describe whether a goal has been achieved
 * or not and can also describe movement a patient is making toward the achievement of a goal
 * (eg. "Goal not achieved - no discernible change", "Goal not achieved - progressing toward goal" or
 * "Goal not achieved - declining from goal").
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
 * Class progressTowardGoalObservation
 * @package LevelEntry
 */
class progressTowardGoalObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['productCode']))
            throw new Exception('This playingDevice SHOULD contain zero or one [0..1] code');
        if(!isset($PortionData['productCodeSystemName']))
            throw new Exception('This playingDevice SHOULD contain zero or one [0..1] code');
        if(!isset($PortionData['productDisplayName']))
            throw new Exception('This playingDevice SHOULD contain zero or one [0..1] code');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'ProgressTowardGoalObservation' => [
                'code' => 'SHALL be selected from ValueSet Goal Achievement',
                'codeSystemName' => 'SHALL be selected from ValueSet Goal Achievement',
                'displayName' => 'SHALL be selected from ValueSet Goal Achievement'
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
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.110'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => 'ASSERTION',
                        'codeSystem' => '2.16.840.1.113883.5.4'
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'displayName' => $PortionData['displayName'],
                            'codeSystemName' => $PortionData['codeSystemName']
                        ]
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
