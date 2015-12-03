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
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
        if(!isset($Data['Allergies']))
            throw new Exception('2.4 Allergies Section (entries required) (V2)');
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
            'PlanOfTreatment' => [

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
                                'root' => '2.16.840.1.113883.10.20.22.2.10.1.2'
                            ]
                        ],
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
                ]
            ];

            // Handoff Communication (NEW)
            // ...
            // Instruction (V2)
            // ...
            // Nutrition Recommendations (NEW)
            // ...
            // Planned Act (V2)
            // ...
            // Planned Encounter (V2)
            // ...
            // Planned Observation (V2)
            // ...
            // Planned Procedure (V2)
            // ...
            // Planned Substance Administration (V2)
            // ...
            // Planned Supply (V2)
            // ...


            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
