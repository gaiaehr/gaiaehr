<?php

/**
 * 2.25	History of Present Illness Section
 *
 * The History of Present Illness section describes the history related to the reason for the encounter.
 * It contains the historical details leading up to and pertaining to the patient’s current complaint or
 * reason for seeking medical care.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class historyOfPresentIllness
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
            'HistoryOfPresentIllness' => [

            ]
        ];
    }

    /**
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try
        {
            // Validate first
            self::Validate($Data['HistoryOfPresentIllness']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '1.3.6.1.4.1.19376.1.5.3.1.3.4.2',
                                'extension' => $Data['HistoryOfPresentIllness']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10164-2',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'HISTORY OF PRESENT ILLNESS'
                            ]
                        ],
                        'title' => 'History Of Present Illness',
                        'text' => self::Narrative($Data['HistoryOfPresentIllness'])
                    ]
                ]
            ];

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
