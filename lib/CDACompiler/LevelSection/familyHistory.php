<?php

/**
 * 2.16	Family History Section
 *
 * This section contains data defining the patient’s genetic relatives in terms of possible or relevant health
 * risk factors that have a potential impact on the patient’s healthcare risk profile.
 *
 * Contains:
 * 3.33	Family History Organizer
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class familyHistory
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
                                'root' => '2.16.840.1.113883.10.20.22.2.15'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10157-6',
                                'displayName' => 'Family History',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Family History',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // 3.33	Family History Organizer
            // ...

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
