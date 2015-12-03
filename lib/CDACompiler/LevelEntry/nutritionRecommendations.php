<?php

/**
 * 3.60	Nutrition Recommendations (NEW)
 *
 * This template represents nutrition regimens (e.g., fluid restrictions, calorie minimum), interventions
 * (e.g., NPO, nutritional supplements), and procedures (e.g., G-Tube by bolus, TPN by central line). It may also
 * depict the need for nutrition education.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class nutritionRecommendations
 * @package LevelEntry
 */
class nutritionRecommendations
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Recommendations');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Recommendations');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Recommendations');
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
            'NutritionRecommendations' => [
                'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime',
                'code' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Recommendations',
                'displayName' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Recommendations',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Recommendations'
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
        try {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'act' => [
                    '@attributes' => [
                        'moodCode' => 'INT',
                        'classCode' => 'ACT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.130'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'displayName' => $PortionData['displayName']
                    ],
                    'statusCode' => Component::statusCode('active')
                ]
            ];

            // SHOULD contain zero or one [0..1] effectiveTime (CONF:31699)
            if (isset($PortionData['effectiveTime']))
            {
                $Entry['act']['effectiveTime'] = Component::time($PortionData['effectiveTime']);
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
