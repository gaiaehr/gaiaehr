<?php

/**
 * 2.78	Surgery Description Section
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class surgeryDescription
{
    /**
     * @param $Data
     * @throws Exception
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
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try
        {
            // Validate first
            self::Validate($Data['SurgeryDescription']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.26'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '29554-3',
                                'displayName' => 'SURGERY DESCRIPTION',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Surgical Drains',
                        'text' => self::Narrative($Data['SurgeryDescription'])
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
