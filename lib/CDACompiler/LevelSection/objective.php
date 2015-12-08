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
            'Objective' => [

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
                                'root' => '2.16.840.1.113883.10.20.21.2.1',
                                'extension' => $PortionData['Objective']['date']
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
                        'text' => self::Narrative($PortionData['Objective'])
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
