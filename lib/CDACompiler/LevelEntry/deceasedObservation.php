<?php

/**
 * 3.24	Deceased Observation (V2)
 *
 * This template represents the observation that a patient has died. It also represents the cause of death,
 * indicated by an entryRelationship type of ‘CAUS’. This template allows for more specific representation of
 * data than is available with the use of dischargeDispositionCode.
 *
 * Contains:
 * Problem Observation (V2)
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
class deceasedObservation
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:14814)');

        if(count($PortionData['ProblemObservation']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] Problem Observation (V2) (templateId:2.16.840.1.113883.10.20.22.4.4.2) (CONF:14870)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'DeceasedObservation' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime (CONF:14855)',
                problemObservation::Structure()
            ]
        ];
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.79'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => $PortionData['effectiveTime'],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => '419099009',
                            'displayName' => 'Dead',
                            'codeSystem' => '2.16.840.1.113883.6.96'
                        ]
                    ]
                ]
            ];

            // SHALL contain exactly one [1..1] Problem Observation (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.4.2) (CONF:14870).
            if (count($PortionData['ProblemObservation']) > 0)
            {
                foreach ($PortionData['ProblemObservation'] as $Problem)
                {
                    $Entry['observation']['entry'][] = problemObservation::Insert(
                        $Problem,
                        $CompleteData
                    );
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
