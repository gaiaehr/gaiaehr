<?php

/**
 * 2.77	Subjective Section
 *
 * The Subjective section describes in a narrative format the patient’s current condition and/or interval changes as
 * reported by the patient or by the patient’s guardian or another informant.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class subjective
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
            'Subjective' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text'
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
                                'root' => '2.16.840.1.113883.10.20.21.2.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '61150-9',
                                'displayName' => 'SUBJECTIVE',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Subjective Data',
                        'text' => self::Narrative($PortionData['Subjective'])
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
