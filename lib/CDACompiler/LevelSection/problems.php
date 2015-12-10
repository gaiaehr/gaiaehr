<?php

/**
 * 2.61	Problem Section (entries required) (V2)
 *
 * This section lists and describes all relevant clinical problems at the time the document is generated.
 * At a minimum, all pertinent current and historical problems should be listed.  Overall health status may be
 * represented in this section.
 *
 * Contains:
 * Health Status Observation (V2)
 * Problem Concern Act (Condition) (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class problems
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');
        if(count($PortionData['ProblemConcernAct'])<1)
            throw new Exception('Such entries SHALL contain exactly one [1..1] Problem Concern Act (Condition) (V2)');
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
            'Problems' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\problemConcernAct::Structure(),
                LevelEntry\healthStatusObservation::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.5.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11450-4',
                                'displayName' => 'Problem List',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Problem List',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // MAY contain zero or more [1..*] entry
            // SHALL contain exactly one [1..1] Problem Concern Act (Condition) (V2)
            if(count($PortionData['ProblemConcernAct']) > 0) {
                foreach ($PortionData['ProblemConcernAct'] as $ProblemConcernAct) {
                    $Section['component']['section']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'DRIV'
                        ],
                        LevelEntry\problemConcernAct::Insert(
                            $ProblemConcernAct,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Health Status Observation (V2)
            if(count($PortionData['HealthStatusObservation']) > 0) {
                foreach ($PortionData['HealthStatusObservation'] as $HealthStatusObservation) {
                    $Section['component']['section']['entry'][] = LevelEntry\healthStatusObservation::Insert(
                        $HealthStatusObservation,
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
