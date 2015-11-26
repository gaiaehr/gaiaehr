<?php

/**
 * 3.76	Preoperative Diagnosis (V2)
 *
 * This template represents the surgical diagnosis or diagnoses assigned to the patient before the surgical
 * procedure and is the reason for the surgery. The preoperative diagnosis is, in the opinion of the surgeon,
 * the diagnosis that will be confirmed during surgery.
 *
 * Contains:
 * Postprocedure Diagnosis (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class preoperativeDiagnosis
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
                                'root' => '2.16.840.1.113883.10.20.22.2.34.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10219-4',
                                'displayName' => 'PREOPERATIVE DIAGNOSIS',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Preoperative Diagnosis',
                        'text' => self::Narrative($PortionData['PreoperativeDiagnosis'])
                    ]
                ]
            ];

            // Preoperative Diagnosis (V2) [0..1]
            foreach($PortionData['PostprocedureDiagnosis']['Activity'] as $Activity) {
                $Section['component']['section']['entry'][] = [
                    '@attributes' => [
                        'typeCode' => 'DRIV'
                    ],
                    'act' => LevelEntry\preoperativeDiagnosis::Insert($Activity, $CompleteData)
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
