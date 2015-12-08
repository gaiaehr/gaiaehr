<?php

/**
 * 3.56	Mental Status Observation (NEW)
 *
 * This template represents observations relating intellectual and mental powers and state of mind.
 * Mental status observations in a clinical note often have a psychological focus (e.g., level of consciousness,
 * mood, anxiety level, reasoning ability).
 *
 * Contains:
 * Assessment Scale Observation
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
class mentalStatusObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['statusCode']))
            throw new Exception('SHALL contain exactly one [1..1] statusCode');

        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');

        if(!isset($PortionData['code']))
            throw new Exception('SHOULD be selected from ValueSet Mental and Functional Status Response Value Set');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Mental and Functional Status Response Value Set');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHOULD be selected from ValueSet Mental and Functional Status Response Value Set');
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'MentalStatusObservation' => [
                'statusCode' => 'SHALL contain exactly one [1..1] statusCode, which SHALL be selected from ValueSet Medication Fill Status',
                'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime',
                'code' => 'SHOULD be selected from ValueSet Mental and Functional Status Response Value Set',
                'codeSystemName' => 'SHOULD be selected from ValueSet Mental and Functional Status Response Value Set',
                'displayName' => 'SHOULD be selected from ValueSet Mental and Functional Status Response Value Set',
                LevelOther\authorParticipation::Structure(),
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.125'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'xsi:type' => 'CD',
                        'code' => '285231000',
                        'displayName' => 'Mental Function',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'codeSystemName' => 'SNOMED CT'
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'displayName' => $PortionData['displayName'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName']
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Authors']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['observation']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Assessment Scale Observation
            if(count($PortionData['AssessmentScaleObservation']) > 0)
            {
                foreach($PortionData['AssessmentScaleObservation'] as $AssessmentScaleObservation)
                {
                    $Entry['observation']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'observation' => assessmentScaleObservation::Insert(
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

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

    }

}
