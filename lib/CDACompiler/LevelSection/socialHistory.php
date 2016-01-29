<?php

/**
 * 2.76	Social History Section (V2)
 *
 * This section contains social history data that influences a patient’s physical, psychological or
 * emotional health (e.g. smoking status, pregnancy). Demographic data, such as marital status, race,
 * ethnicity, and religious affiliation, is captured in the header.
 *
 * Contains:
 * Caregiver Characteristics
 * Characteristics of Home Environment (NEW)
 * Cultural and Religious Observation (NEW)
 * Current Smoking Status (V2)
 * Pregnancy Observation
 * Social History Observation (V2)
 * Tobacco Use (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class socialHistory
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
        return $PortionData['Narrative'];
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'SocialHistory' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\socialHistoryObservation::Structure(),
                LevelEntry\pregnancyObservation::Structure(),
                LevelEntry\currentSmokingStatus::Structure(),
                LevelEntry\tobaccoUse::Structure(),
                LevelEntry\caregiverCharacteristics::Structure(),
                LevelEntry\culturalAndReligiousObservation::Structure(),
                LevelEntry\characteristicsOfHomeEnvironment::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.17.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '29762-2',
                                'displayName' => 'Social History',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Social History',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Social History Observation (V2)
            if(count($PortionData['SocialHistoryObservation']) > 0) {
                foreach ($PortionData['SocialHistoryObservation'] as $SocialHistoryObservation) {
                    $Section['component']['section']['entry'][] = LevelEntry\socialHistoryObservation::Insert(
                        $SocialHistoryObservation,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Pregnancy Observation
            if(count($PortionData['PregnancyObservation']) > 0) {
                foreach ($PortionData['PregnancyObservation'] as $PregnancyObservation) {
                    $Section['component']['section']['entry'][] = LevelEntry\pregnancyObservation::Insert(
                        $PregnancyObservation,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Current Smoking Status (V2)
            if(count($PortionData['CurrentSmokingStatus']) > 0) {
                foreach ($PortionData['CurrentSmokingStatus'] as $CurrentSmokingStatus) {
                    $Section['component']['section']['entry'][] = LevelEntry\currentSmokingStatus::Insert(
                        $CurrentSmokingStatus,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Tobacco Use (V2)
            if(count($PortionData['TobaccoUse']) > 0) {
                foreach ($PortionData['TobaccoUse'] as $TobaccoUse) {
                    $Section['component']['section']['entry'][] = LevelEntry\tobaccoUse::Insert(
                        $TobaccoUse,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Caregiver Characteristics
            if(count($PortionData['CaregiverCharacteristics']) > 0) {
                foreach ($PortionData['CaregiverCharacteristics'] as $CaregiverCharacteristics) {
                    $Section['component']['section']['entry'][] = LevelEntry\caregiverCharacteristics::Insert(
                        $CaregiverCharacteristics,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Cultural and Religious Observation (NEW)
            if(count($PortionData['CulturalAndReligiousObservation']) > 0) {
                foreach ($PortionData['CulturalAndReligiousObservation'] as $CulturalAndReligiousObservation) {
                    $Section['component']['section']['entry'][] = LevelEntry\culturalAndReligiousObservation::Insert(
                        $CulturalAndReligiousObservation,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Characteristics of Home Environment (NEW)
            if(count($PortionData['CharacteristicsOfHomeEnvironment']) > 0) {
                foreach ($PortionData['CharacteristicsOfHomeEnvironment'] as $CharacteristicsOfHomeEnvironment) {
                    $Section['component']['section']['entry'][] = LevelEntry\characteristicsOfHomeEnvironment::Insert(
                        $CharacteristicsOfHomeEnvironment,
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
