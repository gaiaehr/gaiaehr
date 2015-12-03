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
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
        // ...
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
            'PostprocedureDiagnosis' => [

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

            // Postprocedure Diagnosis (V2) [0..1]
            foreach($PortionData['PostprocedureDiagnosis']['Activity'] as $Activity) {
                $Section['component']['section']['entry'][] = [
                    '@attributes' => [
                        'typeCode' => 'DRIV'
                    ],
                    'act' => LevelEntry\postprocedureDiagnosis::Insert($Activity, $CompleteData)
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
