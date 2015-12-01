<?php

/**
 * 2.66	Procedure Implants Section
 *
 * The Procedure Implants section records any materials placed during the procedure including
 * stents, tubes, and drains.
 *
 * Contains:
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
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try
        {
            // Validate first
            self::Validate($Data);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.40',
                                'extension' => $Data['ProcedureImplants']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '59771-6',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Implants'
                            ]
                        ],
                        'title' => 'Procedure Implants',
                        'text' => self::Narrative($Data['ProcedureImplants'])
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
