<?php

/**
 * 2.51	Operative Note Surgical Procedure Section
 *
 * The Operative Note Surgical Procedure section can be used to restate the procedures performed if appropriate
 * for an enterprise workflow.  The procedure(s) performed associated with the Operative Note are formally modeled
 * in the header using serviceEvent.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class operativeNoteSurgicalProcedure
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
            'OperativeNoteSurgicalProcedure' => [
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
                                'root' => '2.16.840.1.113883.10.20.7.14',
                                'extension' => $PortionData['OperativeNoteSurgicalProcedure']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10223-6',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'OPERATIVE NOTE SURGICAL PROCEDURE'
                            ]
                        ],
                        'title' => 'Surgical Procedure',
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
