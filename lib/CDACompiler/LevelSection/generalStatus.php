<?php

/**
 * 2.20	General Status Section
 *
 * The General Status section describes general observations and readily observable attributes of the patient,
 * including affect and demeanor, apparent age compared to actual age, gender, ethnicity, nutritional status based
 * on appearance, body build and habitus (e.g., muscular, cachectic, obese), developmental or other deformities,
 * gait and mobility, personal hygiene, evidence of distress, and voice quality and speech.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class generalStatus
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'GeneralStatus' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text'
            ]
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
                                'root' => '2.16.840.1.113883.10.20.2.5'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10210-3',
                                'displayName' => 'General Status',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'General Status',
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
