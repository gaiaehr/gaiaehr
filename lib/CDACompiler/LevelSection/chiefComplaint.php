<?php

/**
 * 2.9	Chief Complaint Section
 *
 * This section records the patient's chief complaint (the patient’s own description).
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class chiefComplaint
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
     * @return array
     */
    public static function Structure()
    {
        return [
        ];
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
                                'root' => '1.3.6.1.4.1.19376.1.5.3.1.1.13.2.1',
                                'extension' => $PortionData['AdvanceDirectives']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10154-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'CHIEF COMPLAINT'
                            ]
                        ],
                        'title' => 'Chief Complaint',
                        'text' => self::Narrative($PortionData)
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
