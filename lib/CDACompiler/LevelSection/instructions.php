<?php

/**
 * 2.39	Instructions Section (V2)
 *
 * The Instructions section records instructions given to a patient. List patient decision aids here.
 *
 * Contains:
 * Instruction (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class instructions
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
            'Instructions' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\instruction::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.21.2.45.2',
                                'extension' => $PortionData['Instructions']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '69730-0',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'INSTRUCTIONS'
                            ]
                        ],
                        'title' => 'Instructions',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['Instruction']) > 0)
            {
                foreach ($PortionData['Instruction'] as $Instruction)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\instruction::Insert(
                        $Instruction,
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
