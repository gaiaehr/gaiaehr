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
     * @param $Data
     */
    private static function Validate($Data)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data)
    {

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

            // Outcome Observation (NEW) [1..1]
            $Section['component']['section']['entry'][] = [
                'observation' => LevelEntry\outcomeObservation::Insert($PortionData, $CompleteData)
            ];

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
