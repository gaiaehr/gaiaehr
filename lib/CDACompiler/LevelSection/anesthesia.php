<?php

/**
 * 2.5	Anesthesia Section (V2)
 *
 * The Anesthesia section records the type of anesthesia (e.g., general or local) and may state the actual agent used.
 * This may be a subsection of the Procedure Description section. The full details of anesthesia are usually found in
 * a separate Anesthesia Note.
 *
 * Contains:
 * Medication Activity (V2)
 * Procedure Activity Procedure (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class anesthesia
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
            'Anesthesia' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\procedureActivityProcedure::Structure(),
                LevelEntry\medicationActivity::Structure()
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
            self::Validate($PortionData['Anesthesia']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.25.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '59774-0',
                                'displayName' => 'Anesthesia',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Procedure Anesthesia',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Procedure Activity Procedure (V2)
            if(count($PortionData['ProcedureActivityProcedure']) > 0)
            {
                foreach ($PortionData['ProcedureActivityProcedure'] as $ProcedureActivityProcedure)
                {
                    $Section['component']['section']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'DRIV'
                        ],
                        LevelEntry\procedureActivityProcedure::Insert(
                            $ProcedureActivityProcedure,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Medication Activity (V2)
            if(count($PortionData['MedicationActivity']) > 0)
            {
                foreach ($PortionData['MedicationActivity'] as $MedicationActivity)
                {
                    $Section['component']['section']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'DRIV'
                        ],
                        LevelEntry\medicationActivity::Insert(
                            $MedicationActivity,
                            $CompleteData
                        )
                    ];
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
