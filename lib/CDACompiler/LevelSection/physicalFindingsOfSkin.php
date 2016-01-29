<?php

/**
 * 2.54	Physical Findings of Skin Section (NEW)
 *
 * The Skin Physical Exam section includes direct observations made by the clinician. This section includes
 * only observations made by the examining clinician using inspection and palpation; it does not include
 * laboratory or imaging findings. The examination may be reported as a collection of random clinical statements
 * or it may be reported categorically.
 *
 * Contains:
 * Wound Observation (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class physicalFindingsOfSkin
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
            'PhysicalFindingsOfSkin' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\woundClassObservation::Structure()
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.2.63'),
                        'code' => [
                            '@attributes' => [
                                'code' => '8709-8',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Physical findings of Skin'
                            ]
                        ],
                        'title' => 'Physical findings of Skin',
                        'text' => self::Narrative($PortionData['PhysicalFindingsOfSkin'])
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Wound Class Observation
            if(count($PortionData['WoundsClassObservation']) > 0) {
                foreach ($PortionData['WoundsClassObservation'] as $WoundsClassObservation) {
                    $Section['component']['section']['entry'][] = LevelEntry\woundClassObservation::Insert(
                        $WoundsClassObservation,
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
