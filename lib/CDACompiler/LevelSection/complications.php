<?php

/**
 * 2.11	Complications Section (V2)
 *
 * This section contains problems that occurred during or around the time of a procedure.
 * The complications may be known risks or unanticipated problems.
 *
 * Contains:
 * Problem Observation (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class complications
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
            'Complications' => [
                'Narrated' => '',
                LevelEntry\problemObservation::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.37.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10830-8',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Complications'
                            ]
                        ],
                        'title' => 'Complications',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // Compile Problem Observation (V2) [1..1]
            $Section['component']['section']['entry'][] = LevelEntry\problemObservation::Insert(
                $PortionData,
                $CompleteData
            );

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
