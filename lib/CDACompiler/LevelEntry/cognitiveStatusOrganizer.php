<?php

/**
 * 3.18	Cognitive Status Organizer (V2)
 *
 * This template groups related cognitive status observations into categories. This organizer template may be used
 * to group questions in a Patient Health Questionnaire (PHQ).
 *
 * Contains:
 * Cognitive Status Observation (V2)
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
class cognitiveStatusOrganizer
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['CognitiveStatusObservation']) < 1)
            throw new Exception ('SHALL contain exactly one [1..1] Cognitive Status Observation (V2) (templateId:2.16.840.1.113883.10.20.22.4.74.2) (CONF:14381)');
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
    public static function Structure(){
        return [
            'CognitiveStatusOrganizer' => [
                cognitiveStatusObservation::Structure()
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
                'organizer' => [
                    '@attributes' => [
                        'classCode' => 'CLUSTER',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.75.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => 'd3',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'codeSystemName' => 'ICF',
                            'displayName' => 'Communication'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed')
                ]
            ];

            // SHALL contain at least one [1..*] component (CONF:14373)
            if (count($PortionData['CognitiveStatusObservation']) > 0)
                $Entry['organizer']['component'][] = cognitiveStatusObservation::Insert(
                    $PortionData['CognitiveStatusObservation'],
                    $CompleteData
                );

            return $Entry;
        } catch (Exception $Error) {
            return $Error;
        }
    }

}
