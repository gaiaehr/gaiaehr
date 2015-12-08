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
            'Nutrition' => [

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
                                'root' => '2.16.840.1.113883.10.20.2.5.2',
                                'extension' => $PortionData['Nutrition']['date']
                            ]
                        ],
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

            // Nutritional Status Observation (NEW) [0..*]
            if(count($PortionData['Nutrition']['Observations']) > 0) {
                foreach ($PortionData['Nutrition']['Observations'] as $Observation) {
                    $Section['component']['section']['entry'][] = LevelEntry\nutritionalStatusObservation::Insert(
                        $Observation,
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
