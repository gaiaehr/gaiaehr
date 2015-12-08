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
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        // ...
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
            'Immunizations' => [

            ]
        ];
    }

    /**
     * @param $PortionData
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
                    LevelEntry\immunizationActivity::Insert($Activity, $Data)
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
