<?php

/**
 * 3.6	Allergy - Intolerance Observation (V2)
 *
 * This template reflects a discrete observation about a patient's allergy or intolerance. Because it is a
 * discrete observation, it will have a statusCode of "completed". The effectiveTime, also referred to as the
 * “biologically relevant time” is the time at which the observation holds for the patient. For a provider seeing
 * a patient in the clinic today, observing a history of penicillin allergy that developed five years ago,
 * the effectiveTime is five years ago.
 *
 * The effectiveTime of the Allergy - Intolerance Observation is the definitive indication of whether or not the
 * underlying allergy/intolerance is resolved. If known to be resolved, then an effectiveTime/high would be present.
 * If the date of resolution is not known, then effectiveTime/high will be present with a nullFlavor of "UNK".
 *
 * NOTE: The agent responsible for an allergy or adverse reaction is not always a manufactured material
 * (for example, food allergies), nor is it necessarily consumed. The following constraints reflect limitations
 * in the base CDA R2 specification, and should be used to represent any type of responsible agent.
 *
 * Contains:
 * Author Participation (NEW)
 * Reaction Observation (V2)
 * Severity Observation (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class ageObservation
 * @package LevelEntry
 */
class allergyIntoleranceObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['onsetDate']))
            throw new Exception('This effectiveTime SHALL contain exactly one [1..1] onsetDate (CONF:31538).');

        if(!isset($PortionData['substanceCode']) ||
            !isset($PortionData['substanceName']) ||
            !isset($PortionData['substancecodeSystemName']))
                throw new Exception('Need substanceCode, substanceName, & substancecodeSystemName for substance ');

        if(!isset($PortionData['allergyCode']) ||
            !isset($PortionData['allergyDisplayName']) ||
            !isset($PortionData['allergyCodeSystemName']))
                throw new Exception('Need substanceCode, substanceName, & substancecodeSystemName for substance ');
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
            'AllergyIntoleranceObservation' => [
                'onsetDate' => '',
                'resolvedDate' => '',
                'allergyCode' => '',
                'allergyDisplayName' => '',
                'allergyCodeSystemName' => '',
                'substanceCode' => '',
                'substanceName' => '',
                'substancecodeSystemName' => ''
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.7.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'code' => 'ASSERTION',
                        'codeSystem' => '2.16.840.1.113883.5.4'
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => 'completed'
                        ]
                    ],
                    'effectiveTime' => Component::time($PortionData['onsetDate'], $PortionData['resolvedDate']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['allergyCode'],
                            'displayName' => $PortionData['allergyDisplayName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['allergyCodeSystemName']),
                            'codeSysteName' => $PortionData['allergyCodeSystemName']
                        ]
                    ],
                    'participant' => [
                        '@attributes' => [
                            'typeCode' => 'CSM'
                        ],
                        'participantRole' => [
                            '@attributes' => [
                                'classCode' => 'MANU'
                            ],
                            'playingEntity' => [
                                'code' => [
                                    '@attributes' => [
                                        'code' => $PortionData['substanceCode'],
                                        'displayName' => $PortionData['substanceName'],
                                        'codeSystem' => Utilities::CodingSystemId($PortionData['substancecodeSystemName']),
                                        'codeSystemName' => $PortionData['substancecodeSystemName']
                                    ]
                                ]
                            ]
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
