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
     * @param $PortionData
     * @throws Exception
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
            'SurgeryDescription' => [

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
            self::Validate($PortionData['SurgeryDescription']);

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
                        'text' => self::Narrative($PortionData['SurgeryDescription'])
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
