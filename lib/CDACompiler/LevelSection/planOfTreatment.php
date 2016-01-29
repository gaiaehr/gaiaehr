<?php

/**
 * 2.55	Plan of Treatment Section (V2)
 *
 * The Plan of Treatment section contains data that defines pending orders, interventions, encounters, services,
 * and procedures for the patient. It is limited to prospective, unfulfilled, or incomplete orders and requests
 * only, which are indicated by the @moodCode of the entries within this section. All active, incomplete, or
 * pending orders, appointments, referrals, procedures, services, or any other pending event of clinical
 * significance to the current care of the patient should be listed unless constrained due to privacy issues.
 *
 * The plan may also contain information about ongoing care of the patient, clinical reminders, patient’s values,
 * beliefs, preferences, care expectations and overarching goals of care. Clinical reminders are placed here to
 * provide prompts for disease prevention and management, patient safety, and health-care quality improvements,
 * including widely accepted performance measures. Values may include the importance of quality of life over
 * longevity. These values are taken into account when prioritizing all problems and their treatments.
 * Beliefs may include comfort with dying or the refusal of blood transfusions because of
 * the patient’s religious convictions.  Preferences may include liquid medicines over tablets, or treatment via
 * secure email instead of in person. Care expectations could range from only being treated by female clinicians,
 * to expecting all calls to be returned within 24 hours. Overarching goals described in this section are not
 * tied to a specific condition, problem, health concern, or intervention. Examples of overarching goals could
 * be to minimize pain or dependence on others, or to walk a daughter down the aisle for her marriage.
 * The plan may also indicate that patient education will be provided.

 *
 * Contains:
 * Handoff Communication (NEW)
 * Instruction (V2)
 * Nutrition Recommendations (NEW)
 * Planned Act (V2)
 * Planned Encounter (V2)
 * Planned Observation (V2)
 * Planned Procedure (V2)
 * Planned Substance Administration (V2)
 * Planned Supply (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class planOfTreatment
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
            'PlanOfTreatment' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\plannedObservation::Structure(),
                LevelEntry\plannedEncounter::Structure(),
                LevelEntry\plannedAct::Structure(),
                LevelEntry\plannedProcedure::Structure(),
                LevelEntry\plannedSubstanceAdministration::Structure(),
                LevelEntry\plannedSupply::Structure(),
                LevelEntry\instruction::Structure(),
                LevelEntry\handoffCommunication::Structure(),
                LevelEntry\nutritionRecommendations::Structure()

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
                    'section' => Component::templateId('2.16.840.1.113883.10.20.22.2.10.1.2'),
                        'code' => [
                            '@attributes' => [
                                'code' => '18776-5',
                                'displayName' => 'Treatment Plan',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                    'title' => 'Treatment Plan',
                    'text' => self::Narrative($PortionData)
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Observation (V2)
            if(count($PortionData['PlannedObservation']) > 0) {
                foreach ($PortionData['PlannedObservation'] as $PlannedObservation) {
                    $Section['component']['section']['entry'][] = LevelEntry\plannedObservation::Insert(
                        $PlannedObservation,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Encounter (V2)
            if(count($PortionData['PlannedEncounter']) > 0) {
                foreach ($PortionData['PlannedEncounter'] as $PlannedEncounter) {
                    $Section['component']['section']['entry'][] = LevelEntry\plannedEncounter::Insert(
                        $PlannedEncounter,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Act (V2)
            if(count($PortionData['PlannedAct']) > 0) {
                foreach ($PortionData['PlannedAct'] as $PlannedAct) {
                    $Section['component']['section']['entry'][] = LevelEntry\plannedAct::Insert(
                        $PlannedAct,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Procedure (V2)
            if(count($PortionData['PlannedProcedure']) > 0) {
                foreach ($PortionData['PlannedProcedure'] as $PlannedProcedure) {
                    $Section['component']['section']['entry'][] = LevelEntry\plannedProcedure::Insert(
                        $PlannedProcedure,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Substance Administration (V2)
            if(count($PortionData['PlannedSubstanceAdministration']) > 0) {
                foreach ($PortionData['PlannedSubstanceAdministration'] as $PlannedSubstanceAdministration) {
                    $Section['component']['section']['entry'][] = LevelEntry\plannedSubstanceAdministration::Insert(
                        $PlannedSubstanceAdministration,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Supply (V2)
            if(count($PortionData['PlannedSupply']) > 0) {
                foreach ($PortionData['PlannedSupply'] as $PlannedSupply) {
                    $Section['component']['section']['entry'][] = LevelEntry\plannedSupply::Insert(
                        $PlannedSupply,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['Instruction']) > 0) {
                foreach ($PortionData['Instruction'] as $Instruction) {
                    $Section['component']['section']['entry'][] = LevelEntry\instruction::Insert(
                        $Instruction,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Handoff Communication (NEW)
            if(count($PortionData['HandoffCommunication']) > 0) {
                foreach ($PortionData['HandoffCommunication'] as $HandoffCommunication) {
                    $Section['component']['section']['entry'][] = LevelEntry\handoffCommunication::Insert(
                        $HandoffCommunication,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Nutrition Recommendations (NEW)
            if(count($PortionData['NutritionRecommendations']) > 0) {
                foreach ($PortionData['NutritionRecommendations'] as $NutritionRecommendations) {
                    $Section['component']['section']['entry'][] = LevelEntry\nutritionRecommendations::Insert(
                        $NutritionRecommendations,
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
