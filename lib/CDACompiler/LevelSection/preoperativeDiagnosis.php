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
            'PreoperativeDiagnosis' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\preoperativeDiagnosis::Structure()
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

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Preoperative Diagnosis (V2)
            if(count($PortionData['PreoperativeDiagnosis']) > 0) {
                foreach ($PortionData['PreoperativeDiagnosis'] as $PreoperativeDiagnosis) {
                    $Section['component']['section']['entry'][] = LevelEntry\preoperativeDiagnosis::Insert(
                        $PreoperativeDiagnosis,
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
