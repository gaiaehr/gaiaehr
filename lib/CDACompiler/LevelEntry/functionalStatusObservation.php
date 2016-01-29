<?php

/**
 * 3.34	Functional Status Observation (V2)
 *
 * This template represents the patient's physical function (e.g., mobility status, activities of daily living,
 * self-care status) and problems that limit function (dyspnea, dysphagia). The template may include assessment
 * scale observations, identify supporting caregivers, and provide information about non-medicinal supplies.
 * This template is used to represent physical or developmental function of all patient populations and is not
 * limited to the long-term care population.
 *
 * Contains:
 * Assessment Scale Observation
 * Author Participation (NEW)
 * Caregiver Characteristics
 * Non-Medicinal Supply Activity (V2)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class encounterDiagnosis
 * @package LevelEntry
 */
class functionalStatusObservation
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:13930)');

        if(!isset($PortionData['physicalFunctionCode']) &&
            !isset($PortionData['physicalFunctionDisplayName']) &&
            !isset($PortionData['physicalFunctionCodeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] value (CONF:13932)
            If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96) (CONF:14234)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'FunctionalStatusObservation' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'physicalFunctionCode' => 'SHALL contain exactly one [1..1] value If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96)',
                'physicalFunctionDisplayName' => 'SHALL contain exactly one [1..1] value If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96)',
                'physicalFunctionCodeSystemName' => 'SHALL contain exactly one [1..1] value If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96)',
                LevelDocument\author::Structure(),
                nonMedicinalSupplyActivity::Structure(),
                caregiverCharacteristics::Structure(),
                assessmentScaleObservation::Structure()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
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

            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.67.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => '364644000',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'displayName' => 'functional observable',
                            'codeSystemName' => 'SNOMED CT'
                        ]
                    ],
                    'text' => self::Narrative($PortionData),
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['physicalFunctionCode'],
                            'displayName' => $PortionData['physicalFunctionDisplayName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['physicalFunctionCodeSystemName']),
                            'codeSystemName' => $PortionData['physicalFunctionCodeSystemName']
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] entryRelationship (CONF:8675)
            // SHALL contain exactly one [1..1] Age Observation
            if(count($PortionData['Authors']) > 0)
            {
                foreach($PortionData['Authors'] as $Author)
                {
                    $Entry['observation']['author'][] = LevelDocument\author::Insert($Author);
                }
            }

            // MAY contain zero or one [0..1] entryRelationship (CONF:13892)
            // SHALL contain exactly one [1..1] Non-Medicinal Supply Activity (V2)
            if(count($PortionData['NonMedicinalSupplyActivity']) > 0)
            {
                foreach($PortionData['NonMedicinalSupplyActivity'] as $NonMedicinalSupplyActivity)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => nonMedicinalSupplyActivity::Insert(
                            $NonMedicinalSupplyActivity,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship (CONF:13895)
            // SHALL contain exactly one [1..1] Caregiver Characteristics
            if(count($PortionData['CaregiverCharacteristics']) > 0)
            {
                foreach($PortionData['CaregiverCharacteristics'] as $CaregiverCharacteristics)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => caregiverCharacteristics::Insert(
                            $CaregiverCharacteristics,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship (CONF:14465)
            // SHALL contain exactly one [1..1] Assessment Scale Observation
            if(count($PortionData['AssessmentScaleObservation']) > 0)
            {
                foreach($PortionData['AssessmentScaleObservation'] as $AssessmentScaleObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'observation' => assessmentScaleObservation::Insert(
                            $AssessmentScaleObservation,
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
