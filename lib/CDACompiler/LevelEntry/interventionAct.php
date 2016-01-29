<?php

/**
 * 3.49	Intervention Act (NEW)
 *
 * This template represents an Intervention Act, and is a wrapper for intervention-type activities considered
 * to be parts of the same intervention (eg. an activity such as "elevate head of bed" combined with
 * "provide humidified O2 per nasal cannula" might be the interventions planned for a health concern of
 * "respiratory insufficiency" in order to attempt to achieve a goal of "pulse oximetry greater than 92%").
 * These intervention activities may be newly described or derived from a variety of sources within an EHR.
 * Interventions are actions taken to address Health Concerns and increase the likelihood of achieving
 * the patient’s or providers’ Goals.
 *
 * An Intervention Act should contain a reference to a Goal Observation representing the reason for the intervention.
 *
 * Intervention Acts can be related to each other, eg. an Intervention Act with moodCode of INT could be
 * related to a series of Intervention Acts with moodCode of EVN each having an effectiveTime containing
 * the time of the intervention.
 *
 * Contains:
 * Act Reference (NEW)
 * Advance Directive Observation (V2)
 * Author Participation (NEW)
 * Encounter Activity (V2)
 * Goal Observation (NEW)
 * Immunization Activity (V2)
 * Instruction (V2)
 * Medication Activity (V2)
 * Non-Medicinal Supply Activity (V2)
 * Nutrition Recommendations (NEW)
 * Planned Act (V2)
 * Planned Encounter (V2)
 * Planned Observation (V2)
 * Planned Procedure (V2)
 * Planned Substance Administration (V2)
 * Planned Supply (V2)
 * Procedure Activity Act (V2)
 * Procedure Activity Observation (V2)
 * Procedure Activity Procedure (V2)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class interventionAct
 * @package LevelEntry
 */
class interventionAct
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['moodCode']))
            throw new Exception('SHALL be selected from ValueSet Intervention moodCode');

        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'InterventionAct' => [
                'moodCode' => 'SHALL be selected from ValueSet Intervention moodCode',
                'code' => 'SHALL contain exactly one [1..1] code',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code',
                'displayName' => 'SHALL contain exactly one [1..1] code',
                LevelOther\authorParticipation::Structure(),
                advanceDirectiveObservation::Structure(),
                immunizationActivity::Structure(),
                medicationActivity::Structure(),
                procedureActivityAct::Structure(),
                goalObservation::Structure(),
                procedureActivityObservation::Structure(),
                encounterActivity::Structure(),
                instruction::Structure(),
                nonMedicinalSupplyActivity::Structure(),
                plannedAct::Structure(),
                plannedEncounterAct::Structure(),
                plannedObservation::Structure(),
                plannedProcedure::Structure(),
                plannedSubstanceAdministration::Structure(),
                plannedSupply::Structure(),
                nutritionRecommendations::Structure(),
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
                        'moodCode' => $PortionData['moodCode']
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.131'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('active')
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
            // SHALL contain exactly one [1..1] Advance Directive Observation (V2)
            if(count($PortionData['AdvanceDirectiveObservation']) > 0)
            {
                foreach($PortionData['AdvanceDirectiveObservation'] as $AdvanceDirectiveObservation)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        advanceDirectiveObservation::Insert(
                            $AdvanceDirectiveObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Medication Activity (V2)
            if(count($PortionData['MedicationActivity']) > 0)
            {
                foreach($PortionData['MedicationActivity'] as $MedicationActivity)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        medicationActivity::Insert(
                            $MedicationActivity,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Medication Activity (V2)
            if(count($PortionData['ProcedureActivityAct']) > 0)
            {
                foreach($PortionData['ProcedureActivityAct'] as $ProcedureActivityAct)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        procedureActivityAct::Insert(
                            $ProcedureActivityAct,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Goal Observation (NEW)
            if(count($PortionData['GoalObservation']) > 0)
            {
                foreach($PortionData['GoalObservationAct'] as $GoalObservation)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        goalObservation::Insert(
                            $GoalObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Procedure Activity Observation (V2)
            if(count($PortionData['ProcedureActivityObservation']) > 0)
            {
                foreach($PortionData['ProcedureActivityObservation'] as $ProcedureActivityObservation)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        procedureActivityObservation::Insert(
                            $ProcedureActivityObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Procedure Activity Procedure (V2)
            if(count($PortionData['ProcedureActivityProcedure']) > 0)
            {
                foreach($PortionData['ProcedureActivityProcedure'] as $ProcedureActivityProcedure)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        procedureActivityProcedure::Insert(
                            $ProcedureActivityProcedure,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Encounter Activity (V2)
            if(count($PortionData['EncounterActivity']) > 0)
            {
                foreach($PortionData['EncounterActivity'] as $EncounterActivity)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        encounterActivity::Insert(
                            $EncounterActivity,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['Instruction']) > 0)
            {
                foreach($PortionData['Instruction'] as $Instruction)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        instruction::Insert(
                            $Instruction,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['NonMedicinalSupplyActivity']) > 0)
            {
                foreach($PortionData['NonMedicinalSupplyActivity'] as $NonMedicinalSupplyActivity)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        nonMedicinalSupplyActivity::Insert(
                            $NonMedicinalSupplyActivity,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Planned Act (V2)
            if(count($PortionData['PlannedAct']) > 0)
            {
                foreach($PortionData['PlannedAct'] as $PlannedAct)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        plannedAct::Insert(
                            $PlannedAct,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Planned Encounter (V2)
            if(count($PortionData['PlannedEncounter']) > 0)
            {
                foreach($PortionData['PlannedEncounter'] as $PlannedEncounter)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        plannedEncounter::Insert(
                            $PlannedEncounter,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Planned Encounter (V2)
            if(count($PortionData['PlannedObservation']) > 0)
            {
                foreach($PortionData['PlannedObservation'] as $PlannedObservation)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        plannedObservation::Insert(
                            $PlannedObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Planned Procedure (V2)
            if(count($PortionData['PlannedProcedure']) > 0)
            {
                foreach($PortionData['PlannedProcedure'] as $PlannedProcedure)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        plannedProcedure::Insert(
                            $PlannedProcedure,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Planned Procedure (V2)
            if(count($PortionData['PlannedSubstanceAdministration']) > 0)
            {
                foreach($PortionData['PlannedSubstanceAdministration'] as $PlannedSubstanceAdministration)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        plannedSubstanceAdministration::Insert(
                            $PlannedSubstanceAdministration,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Planned Supply (V2)
            if(count($PortionData['PlannedSupply']) > 0)
            {
                foreach($PortionData['PlannedSupply'] as $PlannedSupply)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        plannedSupply::Insert(
                            $PlannedSupply,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Nutrition Recommendations (NEW)
            if(count($PortionData['NutritionRecommendations']) > 0)
            {
                foreach($PortionData['NutritionRecommendations'] as $NutritionRecommendations)
                {
                    $Entry['act']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        nutritionRecommendations::Insert(
                            $NutritionRecommendations,
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
                    $Entry['act']['entryRelationship']['entry'][] = [
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
