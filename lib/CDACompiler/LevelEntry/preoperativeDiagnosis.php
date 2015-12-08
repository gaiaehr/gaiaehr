<?php

/**
 * 3.76	Preoperative Diagnosis (V2)
 *
 * This template represents the surgical diagnosis or diagnoses assigned to the patient before the surgical
 * procedure and is the reason for the surgery. The preoperative diagnosis is, in the opinion of the surgeon,
 * the diagnosis that will be confirmed during surgery.
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
 * Class preoperativeDiagnosis
 * @package LevelEntry
 */
class preoperativeDiagnosis
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['ProblemObservation']) < 1)
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
            'PreoperativeDiagnosis' => [
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.65'),
                    'code' => [
                        'code' => '10219-4',
                        'codeSystem' => '2.16.840.1.113883.6.1',
                        'codeSystemName' => 'LOINC',
                        'displayName' => 'Preoperative Diagnosis'
                    ]
                ]
            ];

            // SHALL contain at least one [1..*] entryRelationship (CONF:10093)
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
