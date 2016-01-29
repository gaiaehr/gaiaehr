<?php

/**
 * 2.47	Nutrition Section (NEW)
 *
 * The Nutrition Section represents diet and nutrition information including special diet requirements and
 * restrictions (e.g. soft mechanical diet, liquids only, enteral feeding). It also represents the overall
 * nutritional status of the patient, nutrition assessment findings, and diet recommendations.
 *
 * Contains:
 * Nutritional Status Observation (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class nutrition
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
            'Nutrition' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\nutritionalStatusObservation::Structure()
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.2.5.2'),
                        'code' => [
                            '@attributes' => [
                                'code' => '61144-2',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Diet and Nutrition'
                            ]
                        ],
                        'title' => 'Diet and Nutrition',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Nutritional Status Observation (NEW)
            if(count($PortionData['NutritionalStatusObservation']) > 0)
            {
                foreach ($PortionData['NutritionalStatusObservation'] as $NutritionalStatusObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\nutritionalStatusObservation::Insert(
                        $NutritionalStatusObservation,
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
