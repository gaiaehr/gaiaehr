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
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
        if(!isset($Data['Anesthesia']))
            throw new Exception('2.5 Anesthesia Section (V2) - Not found, skipping...');
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
            'Anesthesia' => [

            ]
        ];
    }

    /**
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($CompleteData)
    {
        try
        {
            // Validate first
            self::Validate($CompleteData['Anesthesia']);

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
                        'text' => self::Narrative($CompleteData['Anesthesia'])
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Procedure Activity Procedure (V2)
            if(count($CompleteData['ProcedureActivityProcedure']) > 0)
            {
                foreach ($CompleteData['ProcedureActivityProcedure'] as $ProcedureActivityProcedure)
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
            if(count($CompleteData['MedicationActivity']) > 0)
            {
                foreach ($CompleteData['MedicationActivity'] as $MedicationActivity)
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
