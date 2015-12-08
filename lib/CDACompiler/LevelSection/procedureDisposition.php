<?php

/**
 * 2.63	Procedure Disposition Section
 *
 * The Procedure Disposition section records the status and condition of the patient at the completion of the
 * procedure or surgery. It often also states where the patent was transferred to for the next level of care.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class procedureDisposition
{

    /**
     * @param $PortionData
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'ProcedureDisposition' => [

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
                                'root' => '2.16.840.1.113883.10.20.18.2.12',
                                'extension' => $PortionData['ProcedureDisposition']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '59775-7',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Disposition'
                            ]
                        ],
                        'title' => 'Procedure Disposition',
                        'text' => self::Narrative($PortionData['ProcedureDisposition'])
                    ]
                ]
            ];

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
