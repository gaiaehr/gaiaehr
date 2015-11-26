<?php

/**
 * 2.76	Social History Section (V2)
 *
 * This section contains social history data that influences a patient’s physical, psychological or
 * emotional health (e.g. smoking status, pregnancy). Demographic data, such as marital status, race,
 * ethnicity, and religious affiliation, is captured in the header.
 *
 * Contains:
 * Caregiver Characteristics
 * Characteristics of Home Environment (NEW)
 * Cultural and Religious Observation (NEW)
 * Current Smoking Status (V2)
 * Pregnancy Observation
 * Social History Observation (V2)
 * Tobacco Use (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class socialHistory
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
                                'root' => '2.16.840.1.113883.10.20.22.2.17.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '29762-2',
                                'displayName' => 'Social History',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Social History',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // 3.108 Vital Signs Organizer (V2)
            // ...
            // Caregiver Characteristics
            // ...
            // Characteristics of Home Environment (NEW)
            // ...
            // Cultural and Religious Observation (NEW)
            // ...
            // Current Smoking Status (V2)
            // ...
            // Pregnancy Observation
            // ...
            // Social History Observation (V2)
            // ...
            // Tobacco Use (V2)
            // ...

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
