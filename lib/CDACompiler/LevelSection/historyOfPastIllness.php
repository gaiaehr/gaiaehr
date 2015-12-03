<?php

/**
 * 2.24	History of Past Illness Section (V2)
 *
 * This section contains a record of the patient’s past complaints, problems, and diagnoses.
 * It contains data from the patient’s past up to the patient’s current complaint or reason for seeking medical care.
 *
 * Contains:
 * Problem Observation (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class historyOfPastIllness
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
            'HistoryOfPastIllness' => [

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
                                'root' => '2.16.840.1.113883.10.20.22.2.20.2',
                                'extension' => $PortionData['HistoryPastIllness']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11348-0',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'HISTORY OF PAST ILLNESS'
                            ]
                        ],
                        'title' => 'Past Medical History',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // Problem Observation (V2) [1..1]
            $Section['component']['section']['entry'][] = [
                'observation' => LevelEntry\problemObservation::Insert($PortionData, $CompleteData)
            ];

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
