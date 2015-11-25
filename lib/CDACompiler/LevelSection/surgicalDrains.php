<?php

/**
 * 2.79	Surgical Drains Section
 *
 * The Surgical Drains section may be used to record drains placed during the surgical procedure. Optionally,
 * surgical drain placement may be represented with a text element in the Procedure Description Section.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class surgicalDrains
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
            self::Validate($Data);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.7.13'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11537-8',
                                'displayName' => 'Surgical Drains',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Surgical Drains',
                        'text' => self::Narrative($Data)
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
