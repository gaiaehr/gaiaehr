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
            'Complications' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
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

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Problem Observation (V2)
            if(count($PortionData['ProblemObservation']) > 0)
            {
                foreach ($PortionData['ProblemObservation'] as $ProblemObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\problemObservation::Insert(
                        $ProblemObservation,
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
