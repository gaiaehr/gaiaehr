<?php

/**
 * 2.61	Problem Section (entries required) (V2)
 *
 * This section lists and describes all relevant clinical problems at the time the document is generated.
 * At a minimum, all pertinent current and historical problems should be listed.  Overall health status may be
 * represented in this section.
 *
 * Contains:
 * Health Status Observation (V2)
 * Problem Concern Act (Condition) (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class problems
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
                                'root' => '2.16.840.1.113883.10.20.22.2.5.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11450-4',
                                'displayName' => 'Problem List',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Problem List',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // Health Status Observation (V2)
            // ...
            // Problem Concern Act (Condition) (V2)
            // ...


            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
