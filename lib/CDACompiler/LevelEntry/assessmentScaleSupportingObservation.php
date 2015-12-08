<?php

/**
 * 3.10	Assessment Scale Supporting Observation
 *
 * An Assessment Scale Supporting observation represents the components of a scale used in an Assessment
 * Scale Observation. The individual parts that make up the component may be a group of cognitive or functional
 * status observations.
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
class assessmentScaleSupportingObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
    }

    private static function Structure()
    {
        return [
            'AssessmentScaleSupportingObservation' => [
                'code' => 'SHALL contain exactly one [1..1] code',
                'displayName' => 'SHALL contain exactly one [1..1] code',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code'
            ]
        ];
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData){

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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.86'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'code' => $PortionData['code'],
                        'displayName' => $PortionData['displayName'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                    ],
                    'statusCode' => Component::statusCode('completed')
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
