<?php

/**
 * 2.48	Objective Section
 *
 * The Objective section contains data about the patient gathered through tests, measures, or observations
 * that produce a quantified or categorized result. It includes important and relevant positive and negative
 * test results, physical findings, review of systems, and other measurements and observations.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class objective
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
                                'root' => '2.16.840.1.113883.10.20.21.2.1',
                                'extension' => $Data['Objective']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '61149-1',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Objective Data'
                            ]
                        ],
                        'title' => 'Objective Data',
                        'text' => self::Narrative($Data['Objective'])
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
