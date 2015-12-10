<?php

/**
 * 2.65	Procedure Findings Section (V2)
 *
 * The Procedure Findings section records clinically significant observations confirmed or discovered
 * during a procedure or surgery.
 *
 * Contains:
 * Problem Observation (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class procedureFindings
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');
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
            'ProcedureFindings' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\problemObservation::Structure()
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.2.28'),
                        'code' => [
                            '@attributes' => [
                                'code' => '59776-5',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Finding'
                            ]
                        ],
                        'title' => 'Procedure Finding',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Problem Observation (V2)
            if(count($PortionData['ProblemObservation']) > 0) {
                foreach ($PortionData['ProblemObservation'] as $ProblemObservation) {
                    $Section['component']['section']['entry'][] = LevelEntry\problemObservation::Insert(
                        $ProblemObservation,
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
