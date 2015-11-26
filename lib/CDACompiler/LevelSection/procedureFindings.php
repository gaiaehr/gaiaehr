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
     * @return array
     */
    public static function Structure()
    {
        return [
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
                                'root' => '2.16.840.1.113883.10.20.22.2.28',
                                'extension' => $PortionData['ProcedureFindings']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '59776-5',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Finding'
                            ]
                        ],
                        'title' => 'Procedure Finding',
                        'text' => self::Narrative($PortionData['ProcedureFindings'])
                    ]
                ]
            ];

            // Problem Observation (V2) [0..*]
            foreach($PortionData['ProcedureFindings'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'observation' => LevelEntry\problemObservation::Insert($Observation, $CompleteData)
                ];
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
