<?php

/**
 * 3.17	Cognitive Status Observation (V2)
 *
 * This template represents a patient’s cognitive status (e.g., mood, memory, ability to make decisions) and
 * problems that limit cognition (e.g., amnesia, dementia, aggressive behavior). The template may include assessment
 * scale observations, identify supporting caregivers, and provide information about non-medicinal supplies.
 *
 * Contains:
 * Assessment Scale Observation
 * Author Participation (NEW)
 * Caregiver Characteristics
 * Non-Medicinal Supply Activity (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class advanceDirectiveOrganizer
 * @package LevelEntry
 */
class cognitiveStatusObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception ('SHALL contain exactly one [1..1] effectiveTime (CONF:14261)');

        if(!isset($PortionData['code']) &&
            !isset($PortionData['displayName']) &&
            !isset($PortionData['codeSystemName']))
            throw new Exception ('SHALL contain exactly one [1..1] value (CONF:14263).
            If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96) (CONF:14271)');

        if(count($PortionData['Authors']) > 0){
            throw new Exception ('SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet
            Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC (CONF:28823)');
        }
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
    public static function Structure(){
        return [
            'CognitiveStatusObservation' => [
                'code' => 'SHALL contain exactly one [1..1] value If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96)',
                'displayName' => 'SHALL contain exactly one [1..1] value If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96)',
                'codeSystemName' => 'SHALL contain exactly one [1..1] value If xsi:type=“CD”, SHOULD contain a code from SNOMED CT (CodeSystem: 2.16.840.1.113883.6.96)',
                LevelOther\authorParticipation::Structure(),
                caregiverCharacteristics::Structure(),
                nonMedicinalSupplyActivity::Structure(),
                assessmentScaleObservation::Structure()
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
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.74.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '311465003',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'codeSystemName' => 'SNOED-CT',
                            'displayName' => 'Cognitive functions'
                        ]
                    ],
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'displayName' => $PortionData['displayName'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName']
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if (count($PortionData['Authors']) > 0) {
                foreach ($PortionData['Authors'] as $Author) {
                    $Entry['observation']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:14272) such that it
            // SHALL contain exactly one [1..1] Non-Medicinal Supply Activity (V2)
            if (count($PortionData['NonMedicinalSupplyActivity']) > 0)
                $Entry['observation']['entryRelationship'][] = nonMedicinalSupplyActivity::Insert(
                    $PortionData['NonMedicinalSupplyActivity'],
                    $CompleteData
                );

            // MAY contain zero or more [0..*] entryRelationship (CONF:14272) such that it
            // SHALL contain exactly one [1..1] Caregiver Characteristics
            if (count($PortionData['CaregiverCharacteristics']) > 0)
                $Entry['observation']['entryRelationship'][] = caregiverCharacteristics::Insert(
                    $PortionData['CaregiverCharacteristics'],
                    $CompleteData
                );

            // MAY contain zero or more [0..*] entryRelationship (CONF:14272) such that it
            // b.	SHALL contain exactly one [1..1] Assessment Scale Observation
            if (count($PortionData['AssessmentScaleObservation']) > 0)
                $Entry['observation']['entryRelationship'][] = assessmentScaleObservation::Insert(
                    $PortionData['AssessmentScaleObservation'],
                    $CompleteData
                );

            return $Entry;
        } catch (Exception $Error) {
            return $Error;
        }
    }

}
