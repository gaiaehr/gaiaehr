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
            'HealthConcerns' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\healthStatusObservation::Structure(),
                LevelEntry\healthConcernAct::Structure()
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

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Health Status Observation (V2)
            if(count($PortionData['HealthStatusObservation']) > 0)
            {
                foreach ($PortionData['HealthStatusObservation'] as $HealthStatusObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\healthStatusObservation::Insert(
                        $HealthStatusObservation,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Health Concern Act (NEW)
            if(count($PortionData['HealthConcernAct']) > 0)
            {
                foreach ($PortionData['HealthConcernAct'] as $HealthConcernAct)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\healthConcernAct::Insert(
                        $HealthConcernAct,
                        $CompleteData
                    );
                }
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
