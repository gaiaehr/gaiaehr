<?php

/**
 * 2.46	Mental Status Section (NEW)
 *
 * The Mental Status Section contains observation and evaluations related to patient's psychological and
 * mental competency and deficits including cognitive functioning (e.g., mood, anxiety, perceptual disturbances)
 * and cognitive ability (e.g., concentration, intellect, visual-spatial perception).

 *
 * Contains:
 * Assessment Scale Observation
 * Caregiver Characteristics
 * Cognitive Abilities Observation (NEW)
 * Cognitive Status Observation (V2)
 * Cognitive Status Organizer (V2)
 * Mental Status Observation (NEW)
 * Non-Medicinal Supply Activity (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class mentalStatus
{
    /**
     * @param $PortionData
     * @throws Exception
     *
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
            'MentalStatus' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\cognitiveStatusOrganizer::Structure(),
                LevelEntry\cognitiveStatusObservation::Structure(),
                LevelEntry\caregiverCharacteristics::Structure(),
                LevelEntry\assessmentScaleObservation::Structure(),
                LevelEntry\nonMedicinalSupplyActivity::Structure(),
                LevelEntry\cognitiveAbilitiesObservation::Structure(),
                LevelEntry\mentalStatusObservation::Structure()
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.2.14'),
                        'code' => [
                            '@attributes' => [
                                'code' => '10190-7',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Mental Status'
                            ]
                        ],
                        'title' => 'Mental Status',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Cognitive Status Organizer (V2)
            if(count($PortionData['CognitiveStatusOrganizer']) > 0)
            {
                foreach ($PortionData['CognitiveStatusOrganizer'] as $CognitiveStatusOrganizer)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\cognitiveStatusOrganizer::Insert(
                        $CognitiveStatusOrganizer,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Cognitive Status Observation (V2)
            if(count($PortionData['CognitiveStatusObservation']) > 0)
            {
                foreach ($PortionData['CognitiveStatusObservation'] as $CognitiveStatusObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\cognitiveStatusObservation::Insert(
                        $CognitiveStatusObservation,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Caregiver Characteristics
            if(count($PortionData['CaregiverCharacteristics']) > 0)
            {
                foreach ($PortionData['CaregiverCharacteristics'] as $CaregiverCharacteristics)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\caregiverCharacteristics::Insert(
                        $CaregiverCharacteristics,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Assessment Scale Observation
            if(count($PortionData['AssessmentScaleObservation']) > 0)
            {
                foreach ($PortionData['AssessmentScaleObservation'] as $AssessmentScaleObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\assessmentScaleObservation::Insert(
                        $AssessmentScaleObservation,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Non-Medicinal Supply Activity (V2)
            if(count($PortionData['NonMedicinalSupplyActivity']) > 0)
            {
                foreach ($PortionData['NonMedicinalSupplyActivity'] as $NonMedicinalSupplyActivity)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\nonMedicinalSupplyActivity::Insert(
                        $NonMedicinalSupplyActivity,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Cognitive Abilities Observation (NEW)
            if(count($PortionData['CognitiveAbilitiesObservation']) > 0)
            {
                foreach ($PortionData['CognitiveAbilitiesObservation'] as $CognitiveAbilitiesObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\cognitiveAbilitiesObservation::Insert(
                        $CognitiveAbilitiesObservation,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Mental Status Observation (NEW)
            if(count($PortionData['MentalStatusObservation']) > 0)
            {
                foreach ($PortionData['MentalStatusObservation'] as $MentalStatusObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\mentalStatusObservation::Insert(
                        $MentalStatusObservation,
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
