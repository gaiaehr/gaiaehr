<?php

/**
 * 3.39	Health Concern Act (NEW)
 *
 * This template represents a health concern.
 *
 * It is a wrapper for health concerns derived from a variety of sources within an EHR (such as Problem List,
 * Family History, Social History, Social Worker Note, etc.).
 *
 * A Health Concern Act can represent a health concern that a patient currently has. Health concerns require
 * intervention(s) to increase the likelihood of achieving the patient’s or providers’ goals of care.
 *
 * A Health Concern Act can also represent a health concern that is a risk. A risk is a clinical or socioeconomic
 * condition that the patient doesn't currently have, but the risk for developing that condition rises to the
 * level of concern such that an intervention and/or monitoring are needed.
 *
 * The code on the Health Concern Act is set to differentiate between the two types of health concerns.
 *
 * Contains:
 * Act Reference (NEW)
 * Allergy - Intolerance Observation (V2)
 * Assessment Scale Observation
 * Author Participation (NEW)
 * Caregiver Characteristics
 * Characteristics of Home Environment (NEW)
 * Cognitive Abilities Observation (NEW)
 * Cognitive Status Observation (V2)
 * Cultural and Religious Observation (NEW)
 * Current Smoking Status (V2)
 * Encounter Diagnosis (V2)
 * Family History Organizer
 * Functional Status Observation (V2)
 * Hospital Admission Diagnosis (V2)
 * Mental Status Observation (NEW)
 * Nutrition Assessment (NEW)
 * Nutritional Status Observation (NEW)
 * Patient Priority Preference (NEW)
 * Postprocedure Diagnosis (V2)
 * Pregnancy Observation
 * Preoperative Diagnosis (V2)
 * Problem Concern Act (Condition) (V2)
 * Problem Observation (V2)
 * Provider Priority Preference (NEW)
 * Reaction Observation (V2)
 * Result Observation (V2)
 * Result Organizer (V2)
 * Self-Care Activities (ADL and IADL) (NEW)
 * Sensory and Speech Status (NEW)
 * Social History Observation (V2)
 * Substance or Device Allergy - Intolerance Observation (V2)
 * Tobacco Use (V2)
 * Vital Sign Observation (V2)
 * Wound Observation (NEW)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class healthConcernAct
 * @package LevelEntry
 */
class healthConcernAct
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'healthConcernAct' => [
                'effectiveTime' => 'MAY contain zero or one [0..1] effectiveTime',
                LevelOther\authorParticipation::Structure(),
                LevelOther\problemObservation::Structure(),
                allergyIntoleranceObservation::Structure(),
                assessmentScaleObservation::Structure(),
                cognitiveStatusObservation::Structure(),
                selfCareActivitiesADLAndIADL::Structure(),
                cognitiveAbilitiesObservation::Structure(),
                currentSmokingStatus::Structure(),
                encounterDiagnosis::Structure(),
                familyHistoryOrganizer::Structure(),
                functionalStatusObservation::Structure(),
                hospitalAdmissionDiagnosis::Structure(),
                mentalStatusObservation::Structure(),
                nutritionAssessment::Structure(),
                postprocedureDiagnosis::Structure(),
                pregnancyObservation::Structure(),
                preoperativeDiagnosis::Structure(),
                reactionObservation::Structure(),
                resultObservation::Structure(),
                sensoryAndSpeechStatus::Structure(),
                socialHistoryObservation::Structure(),
                substanceOrDeviceAllergyIntoleranceObservation::Structure(),
                tobaccoUse::Structure(),
                vitalSignObservation::Structure(),
                woundObservation::Structure(),
                caregiverCharacteristics::Structure(),
                culturalAndReligiousObservation::Structure(),
                characteristicsOfHomeEnvironment::Structure(),
                nutritionalStatusObservation::Structure(),
                resultOrganizer::Structure(),
                providerPriorityPreference::Structure(),
                problemConcernActCondition::Structure(),
                actReference::Structure()
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
                'act' => [
                    '@attributes' => [
                        'classCode' => 'ACT',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.132'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => 'CONC',
                            'displayName' => 'handoff communication (procedure)',
                            'codeSystem' => '2.16.840.1.113883.5.6',
                            'codeSystemName' => 'HL7ActClass',
                            'displayName' => 'Concern'
                        ]
                    ],
                    'statusCode' => Component::statusCode('active'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach($PortionData['Author'] as $Author)
                {
                    $Entry['act']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Problem Observation (V2)
            if(count($PortionData['ProblemObservation']) > 0)
            {
                foreach($PortionData['ProblemObservation'] as $ProblemObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        problemObservation::Insert(
                            $ProblemObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Allergy - Intolerance Observation (V2)
            if(count($PortionData['AllergyIntoleranceObservation']) > 0)
            {
                foreach($PortionData['AllergyIntoleranceObservation'] as $AllergyIntoleranceObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        allergyIntoleranceObservation::Insert(
                            $AllergyIntoleranceObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Assessment Scale Observation
            if(count($PortionData['AssessmentScaleObservation']) > 0)
            {
                foreach($PortionData['AssessmentScaleObservation'] as $AssessmentScaleObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        assessmentScaleObservation::Insert(
                            $AssessmentScaleObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Self-Care Activities (ADL and IADL) (NEW)
            if(count($PortionData['SelfCareActivitiesADLAndIADL']) > 0)
            {
                foreach($PortionData['SelfCareActivitiesADLAndIADL'] as $SelfCareActivitiesADLAndIADL)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        selfCareActivitiesADLAndIADL::Insert(
                            $SelfCareActivitiesADLAndIADL,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Cognitive Abilities Observation (NEW)
            if(count($PortionData['CognitiveAbilitiesObservation']) > 0)
            {
                foreach($PortionData['CognitiveAbilitiesObservation'] as $CognitiveAbilitiesObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        cognitiveAbilitiesObservation::Insert(
                            $CognitiveAbilitiesObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Cognitive Status Observation (V2)
            if(count($PortionData['CognitiveStatusObservation']) > 0)
            {
                foreach($PortionData['CognitiveStatusObservation'] as $CognitiveStatusObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        cognitiveStatusObservation::Insert(
                            $CognitiveStatusObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Cognitive Status Observation (V2)
            if(count($PortionData['CurrentSmokingStatus']) > 0)
            {
                foreach($PortionData['CurrentSmokingStatus'] as $CurrentSmokingStatus)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => currentSmokingStatus::Insert(
                            $CurrentSmokingStatus,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Encounter Diagnosis (V2)
            if(count($PortionData['EncounterDiagnosis']) > 0)
            {
                foreach($PortionData['EncounterDiagnosis'] as $EncounterDiagnosis)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        encounterDiagnosis::Insert(
                            $EncounterDiagnosis,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Family History Organizer
            if(count($PortionData['FamilyHistoryOrganizer']) > 0)
            {
                foreach($PortionData['FamilyHistoryOrganizer'] as $FamilyHistoryOrganizer)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        familyHistoryOrganizer::Insert(
                            $FamilyHistoryOrganizer,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Functional Status Observation (V2)
            if(count($PortionData['FunctionalStatusObservation']) > 0)
            {
                foreach($PortionData['FunctionalStatusObservation'] as $FunctionalStatusObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        functionalStatusObservation::Insert(
                            $FunctionalStatusObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Hospital Admission Diagnosis (V2)
            if(count($PortionData['HospitalAdmissionDiagnosis']) > 0)
            {
                foreach($PortionData['HospitalAdmissionDiagnosis'] as $HospitalAdmissionDiagnosis)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        hospitalAdmissionDiagnosis::Insert(
                            $HospitalAdmissionDiagnosis,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Mental Status Observation (NEW)
            if(count($PortionData['MentalStatusObservation']) > 0)
            {
                foreach($PortionData['MentalStatusObservation'] as $MentalStatusObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        mentalStatusObservation::Insert(
                            $MentalStatusObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Nutrition Assessment (NEW)
            if(count($PortionData['NutritionAssessment']) > 0)
            {
                foreach($PortionData['NutritionAssessment'] as $NutritionAssessment)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        nutritionAssessment::Insert(
                            $NutritionAssessment,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Nutrition Assessment (NEW)
            if(count($PortionData['PostprocedureDiagnosis']) > 0)
            {
                foreach($PortionData['PostprocedureDiagnosis'] as $PostprocedureDiagnosis)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        postprocedureDiagnosis::Insert(
                            $PostprocedureDiagnosis,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Pregnancy Observation
            if(count($PortionData['PregnancyObservation']) > 0)
            {
                foreach($PortionData['PregnancyObservation'] as $PregnancyObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        pregnancyObservation::Insert(
                            $PregnancyObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Preoperative Diagnosis (V2)
            if(count($PortionData['PreoperativeDiagnosis']) > 0)
            {
                foreach($PortionData['PreoperativeDiagnosis'] as $PreoperativeDiagnosis)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        preoperativeDiagnosis::Insert(
                            $PreoperativeDiagnosis,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Reaction Observation (V2)
            if(count($PortionData['ReactionObservation']) > 0)
            {
                foreach($PortionData['ReactionObservation'] as $ReactionObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        reactionObservation::Insert(
                            $ReactionObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Reaction Observation (V2)
            if(count($PortionData['ResultObservation']) > 0)
            {
                foreach($PortionData['ResultObservation'] as $ResultObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        resultObservation::Insert(
                            $ResultObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Sensory and Speech Status (NEW)
            if(count($PortionData['SensoryAndSpeechStatus']) > 0)
            {
                foreach($PortionData['SensoryAndSpeechStatus'] as $SensoryAndSpeechStatus)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        sensoryAndSpeechStatus::Insert(
                            $SensoryAndSpeechStatus,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Social History Observation (V2)
            if(count($PortionData['SocialHistoryObservation']) > 0)
            {
                foreach($PortionData['SocialHistoryObservation'] as $SocialHistoryObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        socialHistoryObservation::Insert(
                            $SocialHistoryObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Substance or Device Allergy - Intolerance Observation (V2)
            if(count($PortionData['SubstanceOrDeviceAllergyIntoleranceObservation']) > 0)
            {
                foreach($PortionData['SubstanceOrDeviceAllergyIntoleranceObservation'] as $SubstanceOrDeviceAllergyIntoleranceObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        substanceOrDeviceAllergyIntoleranceObservation::Insert(
                            $SubstanceOrDeviceAllergyIntoleranceObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Tobacco Use (V2)
            if(count($PortionData['TobaccoUse']) > 0)
            {
                foreach($PortionData['TobaccoUse'] as $TobaccoUse)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        tobaccoUse::Insert(
                            $TobaccoUse,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Vital Sign Observation (V2)
            if(count($PortionData['VitalSignObservation']) > 0)
            {
                foreach($PortionData['VitalSignObservation'] as $VitalSignObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        vitalSignObservation::Insert(
                            $VitalSignObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Wound Observation (NEW)
            if(count($PortionData['WoundObservation']) > 0)
            {
                foreach($PortionData['WoundObservation'] as $WoundObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        woundObservation::Insert(
                            $WoundObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Caregiver Characteristics
            if(count($PortionData['CaregiverCharacteristics']) > 0)
            {
                foreach($PortionData['CaregiverCharacteristics'] as $CaregiverCharacteristics)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        caregiverCharacteristics::Insert(
                            $CaregiverCharacteristics,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Cultural and Religious Observation (NEW)
            if(count($PortionData['CulturalAndReligiousObservation']) > 0)
            {
                foreach($PortionData['CulturalAndReligiousObservation'] as $CulturalAndReligiousObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        culturalAndReligiousObservation::Insert(
                            $CulturalAndReligiousObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Characteristics of Home Environment (NEW)
            if(count($PortionData['CharacteristicsOfHomeEnvironment']) > 0)
            {
                foreach($PortionData['CharacteristicsOfHomeEnvironment'] as $CharacteristicsOfHomeEnvironment)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        characteristicsOfHomeEnvironment::Insert(
                            $CharacteristicsOfHomeEnvironment,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Nutritional Status Observation (NEW)
            if(count($PortionData['NutritionalStatusObservation']) > 0)
            {
                foreach($PortionData['NutritionalStatusObservation'] as $NutritionalStatusObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        nutritionalStatusObservation::Insert(
                            $NutritionalStatusObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Result Organizer (V2)
            if(count($PortionData['ResultOrganizer']) > 0)
            {
                foreach($PortionData['ResultOrganizer'] as $ResultOrganizer)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        resultOrganizer::Insert(
                            $ResultOrganizer,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
            if(count($PortionData['PatientPriorityPreference']) > 0)
            {
                foreach($PortionData['PatientPriorityPreference'] as $PatientPriorityPreference)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        patientPriorityPreference::Insert(
                            $PatientPriorityPreference,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Provider Priority Preference (NEW)
            if(count($PortionData['ProviderPriorityPreference']) > 0)
            {
                foreach($PortionData['ProviderPriorityPreference'] as $ProviderPriorityPreference)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        providerPriorityPreference::Insert(
                            $ProviderPriorityPreference,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Problem Concern Act (Condition) (V2)
            if(count($PortionData['ProblemConcernActCondition']) > 0)
            {
                foreach($PortionData['ProblemConcernActCondition'] as $ProblemConcernActCondition)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        problemConcernActCondition::Insert(
                            $ProblemConcernActCondition,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Act Reference (NEW)
            if(count($PortionData['ActReference']) > 0)
            {
                foreach($PortionData['ActReference'] as $ActReference)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        actReference::Insert(
                            $ActReference,
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
