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
 * Class functionalStatusOrganizer
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
            'effectiveTime' => 'MAY contain zero or one [0..1] effectiveTime',

            'Authors' => LevelOther\authorParticipation::Structure(),

            'ProblemObservation' => LevelOther\problemObservation::Structure(),

            'AllergyIntoleranceObservation' => allergyIntoleranceObservation::Structure(),

            'AssessmentScaleObservation' => assessmentScaleObservation::Structure(),

            'CognitiveStatusObservation' => cognitiveStatusObservation::Structure(),

            'SelfCareActivitiesADLAndIADL' => selfCareActivitiesADLAndIADL::Structure(),

            'CognitiveAbilitiesObservation' => cognitiveAbilitiesObservation::Structure(),

            'CurrentSmokingStatus' => currentSmokingStatus::Structure(),

            'EncounterDiagnosis' => encounterDiagnosis::Structure(),

            'FamilyHistoryOrganizer' => familyHistoryOrganizer::Structure(),

            'FunctionalStatusObservation' => functionalStatusObservation::Structure(),

            'HospitalAdmissionDiagnosis' => hospitalAdmissionDiagnosis::Structure(),

            'MentalStatusObservation' => mentalStatusObservation::Structure(),

            'NutritionAssessment' => nutritionAssessment::Structure(),

            'PostprocedureDiagnosis' => postprocedureDiagnosis::Structure(),

            'PregnancyObservation' => pregnancyObservation::Structure(),

            'PreoperativeDiagnosis' => preoperativeDiagnosis::Structure(),

            'ReactionObservation' => reactionObservation::Structure(),

            'ResultObservation' => resultObservation::Structure(),

            'SensoryAndSpeechStatus' => sensoryAndSpeechStatus::Structure(),

            'SocialHistoryObservation' => socialHistoryObservation::Structure(),

            'SubstanceOrDeviceAllergyIntoleranceObservation' => substanceOrDeviceAllergyIntoleranceObservation::Structure(),

            'TobaccoUse' => tobaccoUse::Structure(),

            'VitalSignObservation' => vitalSignObservation::Structure(),

            'WoundObservation' => woundObservation::Structure(),

            'CaregiverCharacteristics' => caregiverCharacteristics::Structure(),

            'CulturalAndReligiousObservation' => culturalAndReligiousObservation::Structure(),

            'CharacteristicsOfHomeEnvironment' => characteristicsOfHomeEnvironment::Structure(),

            'NutritionalStatusObservation' => nutritionalStatusObservation::Structure(),

            'ResultOrganizer' => resultOrganizer::Structure(),

            'ProviderPriorityPreference' => providerPriorityPreference::Structure(),

            'ProblemConcernActCondition' => problemConcernActCondition::Structure(),

            'ActReference' => actReference::Structure()

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
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Authors']) > 0)
            {
                foreach($PortionData['Authors'] as $Author)
                {
                    $Entry['author'][] = LevelOther\authorParticipation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => problemObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => allergyIntoleranceObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => assessmentScaleObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => selfCareActivitiesADLAndIADL::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => cognitiveAbilitiesObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => cognitiveStatusObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => encounterDiagnosis::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => familyHistoryOrganizer::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => functionalStatusObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => hospitalAdmissionDiagnosis::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => mentalStatusObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => nutritionAssessment::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => postprocedureDiagnosis::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => pregnancyObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => preoperativeDiagnosis::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => reactionObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => resultObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => sensoryAndSpeechStatus::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => socialHistoryObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => substanceOrDeviceAllergyIntoleranceObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => tobaccoUse::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => vitalSignObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => woundObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
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

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Cultural and Religious Observation (NEW)
            if(count($PortionData['CulturalAndReligiousObservation']) > 0)
            {
                foreach($PortionData['CulturalAndReligiousObservation'] as $CulturalAndReligiousObservation)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => culturalAndReligiousObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => characteristicsOfHomeEnvironment::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => nutritionalStatusObservation::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => resultOrganizer::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => patientPriorityPreference::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => providerPriorityPreference::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => problemConcernActCondition::Insert(
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
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => actReference::Insert(
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
