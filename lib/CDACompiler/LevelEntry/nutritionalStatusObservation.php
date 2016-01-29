<?php

/**
 * 3.61	Nutritional Status Observation (NEW)
 *
 * This template describes the overall nutritional status of the patient and findings related to nutritional status.
 *
 * Contains:
 * Nutrition Assessment (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class nutritionalStatusObservation
 * @package LevelEntry
 */
class nutritionalStatusObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Nutritional Status');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Nutritional Status');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Nutritional Status');
        if(count($PortionData['NutritionAssessment']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] Nutrition Assessment (NEW)');
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
            'NutritionalStatusObservation' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'code' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Nutritional Status',
                'displayName' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Nutritional Status',
                'codeSystemName' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Nutritional Status',
                nutritionAssessment::Structure()
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
                'observation' => [
                    '@attributes' => [
                        'moodCode' => 'EVN',
                        'classCode' => 'OBS'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.124'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'xsi:type' => 'CD',
                        'code' => '87276001',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'codeSystemName' => 'SNOMED CT',
                        'displayName' => 'nutritional status'
                    ],
                    'statusCode' => Component::statusCode('active'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'codeSystem' => Component::NUCCProviderCodes($PortionData['codeSystemName']),
                        'displayName' => $PortionData['displayName'],
                        'codeSystemName' => $PortionData['codeSystemName']
                    ]
                ]
            ];

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Nutrition Assessment (NEW)
            if(count($PortionData['NutritionAssessment']) > 0)
            {
                foreach($PortionData['NutritionAssessment'] as $NutritionAssessment)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        problemObservation::Insert(
                            $NutritionAssessment,
                            $CompleteData
                        )
                    ];
                }
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
