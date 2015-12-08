<?php

/**
 * 3.7 Allergy Concern Act (V2)
 *
 * This template reflects an ongoing concern on behalf of the provider that placed the allergy on a patient’s
 * allergy list. So long as the underlying condition is of concern to the provider
 * (i.e. so long as the allergy, whether active or resolved, is of ongoing concern and interest to the provider),
 * the statusCode is “active”. Only when the underlying allergy is no longer of concern is the statusCode set
 * to “completed”. The effectiveTime reflects the time that the underlying allergy was felt to be a concern.
 *
 * The statusCode of the Allergy Concern Act is the definitive indication of the status of the concern,
 * whereas the effectiveTime of the nested Allergy - Intolerance Observation is the definitive indication of
 * whether or not the underlying allergy is resolved.
 *
 * The effectiveTime/low of the Allergy Concern Act asserts when the concern became active.
 * This equates to the time the concern was authored in the patient's chart. The effectiveTime/high asserts
 * when the concern was completed (e.g. when the clinician deemed there is no longer any need to track
 * the underlying condition).
 *
 * Contains:
 * Allergy - Intolerance Observation (V2)
 * Author Participation (NEW)
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
class allergyConcernAct
{

    /**
     * @param $PortionData
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'AllergyConcernAct' => [
                'active' => '',
                'firstDate' => ''
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
                'act' => [
                    '@attributes' => [
                        'classCode' => 'ACT',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.30.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'code' => 'CONC',
                        'codeSystem' => '2.16.840.1.113883.5.6'
                    ],
                    'statusCode' => Component::statusCode($PortionData['active']),
                    'effectiveTime' => [
                        'low' => [
                            '@attributes' => [
                                'low' => Component::time($PortionData['firstDate'])
                            ]
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['act']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Allergy - Intolerance Observation (V2)
            if(count($PortionData['AllergyIntoleranceObservation']) > 0)
            {
                foreach ($PortionData['AllergyIntoleranceObservation'] as $AllergyIntoleranceObservation)
                {
                    $Entry['act']['entryRelationship'][] = allergyIntoleranceObservation::Insert(
                        $AllergyIntoleranceObservation,
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

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

    }

}
