<?php

/**
 * 3.4	Advance Directive Organizer (NEW)
 *
 * This clinical statement groups a set of advance directive observations.
 *
 * Contains:
 * Advance Directive Observation (V2)
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
class advanceDirectiveOrganizer
{

    /**
     * @param $PortionData
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'AdvanceDirectiveOrganizer' => [
                'effectiveTime' => '',
                advanceDirectiveObservation::Structure()
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
                'organizer' => [
                    '@attributes' => [
                        'classCode' => 'CLUSTER',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.108'),
                    'code' => [
                        'code' => '310301000',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'codeSystemName' => 'SNOMED-CT',
                        'displayName' => 'Advance Healthcare Directive Status'
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($CompleteData['effectiveTime'])
                ]
            ];

            // SHALL contain at least one [1..*] component
            // SHALL contain exactly one [1..1] Advance Directive Observation (V2)
            if(count($PortionData['AdvanceDirectiveObservation']) > 0)
            {
                foreach ($PortionData['AdvanceDirectiveObservation'] as $AdvanceDirectiveObservation)
                {
                    $Entry['organizer']['component'][] = advanceDirectiveObservation::Insert(
                        $AdvanceDirectiveObservation,
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
