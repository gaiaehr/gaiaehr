<?php

/**
 * 2.81	Vital Signs Section (entries required) (V2)
 *
 * The Vital Signs section contains relevant vital signs for the context and use case of the document type,
 * such as blood pressure, heart rate, respiratory rate, height, weight, body mass index, head circumference,
 * pulse oximetry, temperature and body surface area. The section should include notable vital signs such as
 * the most recent, maximum and/or minimum, baseline, or relevant trends.
 *
 * Vital signs are represented in the same way as other results, but are aggregated into their own section
 * to follow clinical conventions.
 *
 * Contains:
 * 3.108 Vital Signs Organizer (V2)
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class vitalSigns
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Allergies']))
            throw new Exception('2.4 Allergies Section (entries required) (V2)');
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
            'VitalSigns' => [

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
            self::Validate($PortionData['VitalSigns']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.4.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '8716-3',
                                'displayName' => 'VITAL SIGNS',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Vital Signs',
                        'text' => self::Narrative($PortionData['VitalSigns'])
                    ]
                ]
            ];

            // 3.108 Vital Signs Organizer (V2)
            // ...

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
