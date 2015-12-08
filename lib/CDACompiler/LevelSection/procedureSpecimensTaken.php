<?php

/**
 * 2.68	Procedure Specimens Taken Section
 *
 * The Procedure Specimens Taken section records the tissues, objects, or samples taken from the patient during
 * the procedure including biopsies, aspiration fluid, or other samples sent for pathological analysis.
 * The narrative may include a description of the specimens.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class procedureSpecimensTaken
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
            'ProcedureSpecimensTaken' => [

            ]
        ];
    }

    /**
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($PortionData)
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
                                'root' => '2.16.840.1.113883.10.20.22.2.31',
                                'extension' => $PortionData['ProcedureSpecimensTaken']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '59773-2',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Specimens Taken'
                            ]
                        ],
                        'title' => 'Procedure Specimens Taken',
                        'text' => self::Narrative($Data['ProcedureSpecimensTaken'])
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
