<?php

/**
 * 2.79	Surgical Drains Section
 *
 * The Surgical Drains section may be used to record drains placed during the surgical procedure. Optionally,
 * surgical drain placement may be represented with a text element in the Procedure Description Section.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class surgicalDrains
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
            'SurgicalDrains' => [
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
                                'root' => '2.16.840.1.113883.10.20.7.13'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11537-8',
                                'displayName' => 'Surgical Drains',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Surgical Drains',
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
