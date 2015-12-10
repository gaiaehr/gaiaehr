<?php

/**
 * 2.58	Postprocedure Diagnosis Section (V2)
 *
 * The Postprocedure Diagnosis section records the diagnosis or diagnoses discovered or confirmed during the procedure.
 * Often it is the same as the pre-procedure diagnosis or indication.
 *
 * Contains:
 * Postprocedure Diagnosis (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class postprocedureDiagnosis
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
            'PostprocedureDiagnosis' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\postprocedureDiagnosis::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.36.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '59769-0',
                                'displayName' => 'Postprocedure Diagnosis',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Postprocedure Diagnosis',
                        'text' => self::Narrative($PortionData['PostprocedureDiagnosis'])
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Postprocedure Diagnosis (V2)
            if(count($PortionData['PostprocedureDiagnosis']) > 0) {
                foreach ($PortionData['PostprocedureDiagnosis'] as $PostprocedureDiagnosis) {
                    $Section['component']['section']['entry'][] = LevelEntry\postprocedureDiagnosis::Insert(
                        $PostprocedureDiagnosis,
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
