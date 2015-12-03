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
     * @return array
     */
    public static function Structure()
    {
        return [
            'PhysicalFindingsOfSkin' => [

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
                                'root' => '2.16.840.1.113883.10.20.22.2.63',
                                'extension' => $PortionData['PhysicalFindingsOfSkin']['date']
                            ]
                        ],
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

            // Wound Observation (NEW)
            foreach($PortionData['PhysicalFindingsOfSkin']['WoundObservations'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'organizer' => LevelEntry\woundObservation::Insert($Observation, $CompleteData)
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
