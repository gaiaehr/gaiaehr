<?php

/**
 * 2.23	Health Status Evaluations/Outcomes Section (NEW)
 *
 * This template contains observations regarding the outcome of care resulting from the interventions used to
 * treat the patient. These observations represent status, at points in time, related to established care
 * plan goals and/or interventions.
 *
 * Contains:
 * Outcome Observation (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class healthStatusEvaluationsOutcomes
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');
        if(count($PortionData['OutcomeObservation'])<0)
            throw new Exception('SHALL contain exactly one [1..1] Outcome Observation (NEW) ');
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
    public static function Structure()
    {
        return [
            'HealthStatusEvaluationsOutcomes' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\outcomeObservation::Structure()
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

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.61',
                                'extension' => $PortionData['AdvanceDirectives']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11383-7',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Patient Problem Outcome'
                            ]
                        ],
                        'title' => 'Patient Problem Outcome',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [1..*] entry
            // SHALL contain exactly one [1..1] Outcome Observation (NEW)
            if(count($PortionData['OutcomeObservation']) > 0)
            {
                foreach ($PortionData['OutcomeObservation'] as $OutcomeObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\outcomeObservation::Insert(
                        $OutcomeObservation,
                        $CompleteData
                    );
                }
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
