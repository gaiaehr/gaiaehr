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
            'HistoryOfPresentIllness' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text'
            ]
        ];
    }

    /**
     * @param $PortionData
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
                        'templateId' => Component::templateId('1.3.6.1.4.1.19376.1.5.3.1.3.4.2'),
                        'code' => [
                            '@attributes' => [
                                'code' => '10164-2',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'HISTORY OF PRESENT ILLNESS'
                            ]
                        ],
                        'title' => 'History Of Present Illness',
                        'text' => self::Narrative($PortionData)
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
