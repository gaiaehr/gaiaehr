<?php

/**
 * 2.37	Immunizations Section (entries required) (V2)
 *
 * The Immunizations section defines a patient's current immunization status and pertinent immunization history.
 * The primary use case for the Immunization section is to enable communication of a patient's immunization status.
 * The section should include current immunization status, and may contain the entire immunization history
 * that is relevant to the period of time being summarized.
 *
 * Contains:
 * Immunization Activity (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class immunizations
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
     * @return array
     */
    public static function Structure()
    {
        return [
            'Immunizations' => [

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
            self::Validate($Data['Immunizations']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11369-6',
                                'displayName' => 'History of Immunizations',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'History of Immunizations',
                        'text' => self::Narrative($Data['Immunizations'])
                    ]
                ]
            ];

            // Immunization Activity (V2) [1..*]
            foreach($Data['Immunizations']['Activities'] as $Activity) {
                $Section['component']['section']['entry'][] = [
                    '@attributes' => [
                        'typeCode' => 'DRIV'
                    ],
                    'act' => LevelEntry\immunizationActivity::Insert($Activity, $Data)
                ];
            }


            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
