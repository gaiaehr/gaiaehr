<?php

/**
 * 2.19	Functional Status Section (V2)
 *
 * The Functional Status Section contains observations and assessments of a patient's physical abilities.
 * A patient’s functional status may include information regarding the patient’s general function such as ambulation,
 * ability to perform Activities of Daily Living (ADLs) (e.g., bathing, dressing, feeding, grooming) or
 * Instrumental Activities of Daily Living (IADLs) (e.g., shopping, using a telephone, balancing a check book).
 * Problems that impact function (e.g., dyspnea, dysphagia) can be contained in the section.
 *
 * Contains:
 * Assessment Scale Observation
 * Caregiver Characteristics
 * Functional Status Observation (V2)
 * Functional Status Organizer (V2)
 * Non-Medicinal Supply Activity (V2)
 * Self-Care Activities (ADL and IADL) (NEW)
 * Sensory and Speech Status (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class functionalStatus
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
            'FunctionalStatus' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\functionalStatusOrganizer::Structure(),
                LevelEntry\functionalStatusObservation::Structure(),
                LevelEntry\caregiverCharacteristics::Structure(),
                LevelEntry\assessmentScaleObservation::Structure(),
                LevelEntry\nonMedicinalSupplyActivity::Structure(),
                LevelEntry\selfCareActivitiesADLAndIADL::Structure(),
                LevelEntry\sensoryAndSpeechStatus::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.14.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '47420-5',
                                'displayName' => 'Functional Status',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Functional Status',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Functional Status Organizer (V2)
            if(count($PortionData['FunctionalStatusOrganizer']) > 0)
            {
                foreach ($PortionData['FunctionalStatusOrganizer'] as $FunctionalStatusOrganizer)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\functionalStatusOrganizer::Insert(
                        $FunctionalStatusOrganizer,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Functional Status Observation (V2)
            if(count($PortionData['FunctionalStatusObservation']) > 0)
            {
                foreach ($PortionData['FunctionalStatusObservation'] as $FunctionalStatusObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\functionalStatusObservation::Insert(
                        $FunctionalStatusObservation,
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
                foreach ($PortionData['AssessmentScaleObservation'] as $NonMedicinalSupplyActivity)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\nonMedicinalSupplyActivity::Insert(
                        $NonMedicinalSupplyActivity,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Self-Care Activities (ADL and IADL) (NEW)
            if(count($PortionData['SelfCareActivitiesADLAndIADL']) > 0)
            {
                foreach ($PortionData['SelfCareActivitiesADLAndIADL'] as $SelfCareActivitiesADLAndIADL)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\selfCareActivitiesADLAndIADL::Insert(
                        $SelfCareActivitiesADLAndIADL,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Sensory and Speech Status (NEW)
            if(count($PortionData['SensoryAndSpeechStatus']) > 0)
            {
                foreach ($PortionData['SensoryAndSpeechStatus'] as $SensoryAndSpeechStatus)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\sensoryAndSpeechStatus::Insert(
                        $SensoryAndSpeechStatus,
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
