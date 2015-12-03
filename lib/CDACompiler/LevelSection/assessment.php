<?php

/**
 * 2.7	Assessment Section
 *
 * The Assessment section (also referred to as “impression” or “diagnoses” outside of the context of CDA)
 * represents the clinician's conclusions and working assumptions that will guide treatment of the patient.
 * The assessment may be a list of specific disease entities or a narrative block.
 *
 * Contains:
 * Planned Act (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class assessment
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
     * @return array
     */
    public static function Structure()
    {
        return [
            'Assessment' => [

            ]
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
            self::Validate($Data['Assessment']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.8'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '51848-0',
                                'displayName' => 'Assessments',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Assessments',
                        'text' => self::Narrative($Data['Assessment'])
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
