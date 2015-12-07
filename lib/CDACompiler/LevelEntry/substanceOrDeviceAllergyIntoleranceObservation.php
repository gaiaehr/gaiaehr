<?php

/**
 * 3.104	Substance or Device Allergy - Intolerance Observation (V2)
 *
 * This template reflects a discrete observation about a patient's allergy or intolerance to a substance or device.
 * Because it is a discrete observation, it will have a statusCode of "completed". The effectiveTime, also
 * referred to as the “biologically relevant time” is the time at which the observation holds for the patient.
 * For a provider seeing a patient in the clinic today, observing a history of penicillin allergy that developed five
 * years ago, the effectiveTime is five years ago.
 *
 * The effectiveTime of the Substance or Device Allergy - Intolerance Observation is the definitive indication
 * of whether or not the underlying allergy/intolerance is resolved. If known to be resolved, then an
 * effectiveTime/high would be present. If the date of resolution is not known, then effectiveTime/high will be
 * present with a nullFlavor of "UNK".
 *
 * Contains:
 * Allergy Status Observation (DEPRECATED)
 * Author Participation (NEW)
 * Reaction Observation (V2)
 * Severity Observation (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class substanceOrDeviceAllergyIntoleranceObservation
 * @package LevelEntry
 */
class substanceOrDeviceAllergyIntoleranceObservation
{
    /**
     * @param $PortionData
     * @throws Exception
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
     * @return array
     */
    public static function Structure()
    {
        return [
            'SubstanceOrDeviceAllergyIntoleranceObservation' => [
                'onsetDate' => 'SHALL contain exactly one [1..1] effectiveTime',
                'resolutionDate' => 'SHALL contain exactly one [1..1] effectiveTime',
                LevelOther\authorParticipation::Structure(),
                reactionObservation::Structure(),
                severityObservation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.24.3.90.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['onsetDate'], $PortionData['resolutionDate'])
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['observation']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Reaction Observation (V2)
            if(count($PortionData['ReactionObservation']) > 0)
            {
                foreach ($PortionData['ReactionObservation'] as $ReactionObservation)
                {
                    $Entry['observation']['entryRelationship'][] = reactionObservation::Insert(
                        $ReactionObservation,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Severity Observation (V2)
            if(count($PortionData['SeverityObservation']) > 0)
            {
                foreach ($PortionData['SeverityObservation'] as $SeverityObservation)
                {
                    $Entry['observation']['entryRelationship'][] = severityObservation::Insert(
                        $SeverityObservation,
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
