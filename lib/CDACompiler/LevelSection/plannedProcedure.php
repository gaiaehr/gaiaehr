<?php

/**
 * 2.56	Planned Procedure Section (V2)
 *
 * This section contains the procedure(s) that a clinician planned based on the preoperative assessment.
 *
 * Contains:
 * Planned Procedure (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class plannedProcedure
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
            'PlannedProcedure' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\plannedProcedure::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.30.2',
                                'extension' => $PortionData['PlannedProcedure']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '59772-4',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Planned Procedure'
                            ]
                        ],
                        'title' => 'Planned Procedure',
                        'text' => self::Narrative($PortionData['PlannedProcedure'])
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Procedure (V2)
            if(count($PortionData['PlannedProcedure']) > 0) {
                foreach ($PortionData['PlannedProcedure'] as $PlannedProcedure) {
                    $Section['component']['section']['entry'][] = LevelEntry\plannedProcedure::Insert(
                        $PlannedProcedure,
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
