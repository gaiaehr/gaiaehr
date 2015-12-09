<?php

/**
 * 2.15	Encounters Section (entries required) (V2)
 *
 * This section lists and describes any healthcare encounters pertinent to the patient’s current health status or
 * historical health history. An encounter is an interaction, regardless of the setting, between a patient and a
 * practitioner who is vested with primary responsibility for diagnosing, evaluating, or treating the patient’s
 * condition. It may include visits, appointments, as well as non-face-to-face interactions. It is also a contact
 * between a patient and a practitioner who has primary responsibility (exercising independent judgment)
 * for assessing and treating the patient at a given contact. This section may contain all encounters for the
 * time period being summarized, but should include notable encounters.
 *
 * Contains:
 * Encounter Activity (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class encounters
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');
        if(count($PortionData['EncounterActivity']) < 0)
            throw new Exception('SHALL contain exactly one [1..1] Encounter Activity (V2)');
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
            'Encounters' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\encounterActivity::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.22.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '46240-8',
                                'displayName' => 'History of Encounters',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'History of Encounters',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Encounter Activity (V2)
            if(count($PortionData['EncounterActivity']) > 0)
            {
                foreach ($PortionData['EncounterActivity'] as $EncounterActivity)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\encounterActivity::Insert(
                        $EncounterActivity,
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
