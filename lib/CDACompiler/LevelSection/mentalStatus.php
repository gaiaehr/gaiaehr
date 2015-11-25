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
     * @param $Data
     */
    private static function Validate($Data)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data)
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

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.14',
                                'extension' => $PortionData['MentalStatus']['date']
                            ]
                        ],
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

            // Assessment Scale Observation [0..*]
            foreach($PortionData['MentalStatus']['AssessmentScaleObservations'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'observation' => LevelEntry\assessmentScaleObservation::Insert($Observation, $CompleteData)
                ];
            }

            // Caregiver Characteristics [0..*]
            foreach($PortionData['MentalStatus']['CareGiverCharacteristicsObservations'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'observation' => LevelEntry\medicationsActivity::Insert($Observation, $CompleteData)
                ];
            }

            // Cognitive Abilities Observation (NEW) [0..*]
            foreach($PortionData['MentalStatus']['CognitiveAbilitiesObservation'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'observation' => LevelEntry\cognitiveAbilitiesObservation::Insert($Observation, $CompleteData)
                ];
            }

            // Cognitive Status Observation (V2) [0..*]
            foreach($PortionData['MentalStatus']['CognitiveStatusObservation'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'observation' => LevelEntry\cognitiveStatusObservation::Insert($Observation, $CompleteData)
                ];
            }

            // Cognitive Status Organizer (V2) [0..*]
            foreach($PortionData['MentalStatus']['CognitiveStatusOrganizer'] as $Organizer) {
                $Section['component']['section']['entry'][] = [
                    'organizer' => LevelEntry\cognitiveStatusOrganizer::Insert($Organizer, $CompleteData)
                ];
            }

            // Mental Status Observation (NEW)
            foreach($PortionData['MentalStatus']['MentalStatusObservation'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'observation' => LevelEntry\mentalStatusObservation::Insert($Observation, $CompleteData)
                ];
            }

            // Non-Medicinal Supply Activity (V2)
            foreach($PortionData['MentalStatus']['NonMedicinalSupply'] as $Activity) {
                $Section['component']['section']['entry'][] = [
                    'act' => LevelEntry\nonMedicinalSupply::Insert($Activity, $CompleteData)
                ];
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
