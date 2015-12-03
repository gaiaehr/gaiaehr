<?php

/**
 * 3.73	Postprocedure Diagnosis (V2)
 *
 * This template represents the diagnosis or diagnoses discovered or confirmed during the procedure.
 * They may be the same as preprocedure diagnoses or indications.
 *
 * Contains:
 * Problem Observation (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class policyActivity
 * @package LevelEntry
 */
class postprocedureDiagnosis
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['ProblemObservation']) < 0)
            throw new Exception('SHALL contain exactly one [1..1] Problem Observation (V2)');
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
            'PostprocedureDiagnosis' => [
                problemObservation::Structure()
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
                'act' => [
                    '@attributes' => [
                        'moodCode' => 'EVN',
                        'classCode' => 'ACT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.51.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => '59769-0',
                        'codeSystem' => '2.16.840.1.113883.6.1',
                        'codeSystemName' => 'LOINC',
                        'displayName' => 'Postprocedure Diagnosis'
                    ]
                ]
            ];

            // MAY contain zero or more [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Problem Observation (V2)
            if(count($PortionData['ProblemObservation']) > 0)
            {
                foreach($PortionData['ProblemObservation'] as $ProblemObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        problemObservation::Insert(
                            $ProblemObservation,
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
