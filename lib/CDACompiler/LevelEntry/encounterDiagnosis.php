<?php

/**
 * 3.29	Encounter Diagnosis (V2)
 *
 * This template wraps relevant problems or diagnoses at the close of a visit or that need to be followed
 * after the visit. If the encounter is associated with a Hospital Discharge, the Hospital Discharge Diagnosis
 * must be used. This entry requires at least one Problem Observation entry.
 *
 * Contains:
 * Problem Observation (V2)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class encounterDiagnosis
 * @package LevelEntry
 */
class encounterDiagnosis
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['ProblemObservation']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] Problem Observation (V2)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'EncounterDiagnosis' => [
                'effectiveTime' => 'effectiveTime',
                problemObservation::Structure()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
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
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.80.2'),
                    'code' => [
                        '@attributes' => [
                            'code' => '29308-4',
                            'displayName' => 'DIAGNOSIS',
                            'codeSystem' => '2.16.840.1.113883.6.1',
                            'codeSystemName' => 'LOINC'
                        ]
                    ],
                    'statusCode' => Component::statusCode('active'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
                ]
            ];


            // SHALL contain at least one [1..*] entryRelationship (CONF:14892)
            // SHALL contain exactly one [1..1] Problem Observation (V2)
            if(count($PortionData['ProblemObservation']) > 0)
            {
                foreach($PortionData['ProblemObservation'] as $Observation)
                {
                    $Entry['act']['entryRelationship'][] = problemObservation::Insert(
                        $Observation,
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
