<?php

/**
 * 2.50	Operative Note Fluids Section
 *
 * The Operative Note Fluids section may be used to record fluids administered during the surgical procedure.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class operativeNoteFluids
{

    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['title']))
            throw new Exception('SHALL contain exactly one [1..1] title');
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
            'OperativeNoteFluids' => [
                'title' => 'SHALL contain exactly one [1..1] title',
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.7.12'),
                        'code' => [
                            '@attributes' => [
                                'code' => '10216-0',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'OPERATIVE NOTE FLUIDS'
                            ]
                        ],
                        'title' => $PortionData['title'],
                        'text' => self::Narrative($PortionData['Narrated'])
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
