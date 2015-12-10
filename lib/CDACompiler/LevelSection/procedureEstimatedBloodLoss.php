<?php

/**
 * 2.64	Procedure Estimated Blood Loss Section
 *
 * The Estimated Blood Loss section may be a subsection of another section such as the Procedure Description section.
 * The Estimated Blood Loss section records the approximate amount of blood that the patient lost during the
 * procedure or surgery. It may be an accurate quantitative amount, e.g., 250 milliliters, or it may be
 * descriptive, e.g., “minimal” or “none”.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class procedureEstimatedBloodLoss
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
            'ProcedureEstimatedBloodLoss' => [
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.18.2.9'),
                        'code' => [
                            '@attributes' => [
                                'code' => '59770-8',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Estimated Blood Loss'
                            ]
                        ],
                        'title' => 'Procedure Estimated Blood Loss',
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
