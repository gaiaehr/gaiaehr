<?php

/**
 * 3.96	Sensory and Speech Status (NEW)
 *
 * This template represents a patient’s sensory or speech ability. It may contain an assessment scale observations
 * related to the sensory or speech ability.
 *
 * Contains:
 * Assessment Scale Observation
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class sensoryAndSpeechStatus
 * @package LevelEntry
 */
class sensoryAndSpeechStatus
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHOULD be selected from ValueSet Sensory and Speech Problem Type');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Sensory and Speech Problem Type');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHOULD be selected from ValueSet Sensory and Speech Problem Type');
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');
        if(!isset($PortionData['mentalCode']))
            throw new Exception('SHOULD be selected from ValueSet Mental and Functional Status Response Value Set');
        if(!isset($PortionData['mentalCodeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Mental and Functional Status Response Value Set');
        if(!isset($PortionData['mentalDisplayName']))
            throw new Exception('SHOULD be selected from ValueSet Mental and Functional Status Response Value Set');
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
            'SensoryAndSpeechStatus' => [
                'code' => 'SHOULD be selected from ValueSet Sensory and Speech Problem Type',
                'codeSystemName' => 'SSHOULD be selected from ValueSet Sensory and Speech Problem Type',
                'displayName' => 'SHOULD be selected from ValueSet Sensory and Speech Problem Type',
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'mentalCode' => 'SHOULD be selected from ValueSet Mental and Functional Status Response Value Set',
                'mentalCodeSystemName' => 'SHOULD be selected from ValueSet Mental and Functional Status Response Value Set',
                'mentalDisplayName' => 'SHOULD be selected from ValueSet Mental and Functional Status Response Value Set',
                assessmentScaleObservation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.127'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['mentalCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['mentalCodeSystemName']),
                            'codeSystemName' => $PortionData['mentalCodeSystemName'],
                            'displayName' => $PortionData['mentalDisplayName']
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Assessment Scale Observation
            if(count($PortionData['AssessmentScaleObservation']) > 0)
            {
                foreach($PortionData['AssessmentScaleObservation'] as $AssessmentScaleObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        assessmentScaleObservation::Insert(
                            $AssessmentScaleObservation,
                            $CompleteData
                        )
                    ];
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
