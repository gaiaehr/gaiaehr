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
    }

    private static function Structure()
    {
        return [
            'scaleSupportingCode' => '',
            'scaleSupportingName' => '',
            'scaleSupportingSystemName' => '',
            'scaleSupportingSystemName' => '',
            'status' => ''
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
                '@attributes' => [
                    'classCode' => 'OBS',
                    'moodCode' => 'EVN'
                ],
                'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.86'),
                'id' => Component::id( Utilities::UUIDv4() ),
                'code' => [
                    'code' => $PortionData['scaleSupportingCode'],
                    'displayName' => $PortionData['scaleSupportingName'],
                    'codeSystem' => Utilities::CodingSystemId($PortionData['scaleSupportingSystemName']),
                    'codeSystemName' => Utilities::CodingSystemId($PortionData['scaleSupportingSystemName']),
                ],
                'statusCode' => [
                    '@attributes' => [
                        'code' => $PortionData['status']
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

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

    }

}
