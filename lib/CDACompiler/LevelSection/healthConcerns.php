<?php

/**
 * 2.22	Health Concerns Section (NEW)
 *
 * The Health Concerns section contains data that describes an interest or worry about a health state or
 * process that has the potential to require attention, intervention or management.
 *
 * Contains:
 * Health Concern Act (NEW)
 * Health Status Observation (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class healthConcerns
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
            'HealthConcerns' => [

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
                                'root' => '2.16.840.1.113883.10.20.22.2.58',
                                'extension' => $PortionData['AdvanceDirectives']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '46030-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Health Concerns Section'
                            ]
                        ],
                        'title' => 'Health Concerns Section',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // Compile Health Status Observation (V2) [1..1]
            $Section['component']['section']['entry'][] = LevelEntry\healthStatusObservation::Insert(
                $PortionData,
                $CompleteData
            );

            // Compile Health Status Observation (V2) [1..1]
            $Section['component']['section']['entry'][] = LevelEntry\healthConcern::Insert(
                $PortionData,
                $CompleteData
            );

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
