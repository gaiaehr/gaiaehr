<?php

/**
 * 2.4 Allergies Section (entries required) (V2)
 *
 * This section lists and describes any medication allergies, adverse reactions, idiosyncratic reactions,
 * anaphylaxis/anaphylactoid reactions to food items, and metabolic variations or adverse reactions/allergies
 * to other substances (such as latex, iodine, tape adhesives). At a minimum, it should list currently active
 * and any relevant historical allergies and adverse reactions.
 *
 * Contains:
 * Allergy Concern Act (V2)
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class allergies
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');
        if(count($PortionData['AllergyConcernAct'])<0)
            throw new Exception('SHALL contain exactly one [1..1] Allergy Concern Act (V2) ');
    }

    /**
     * @param $PortionData
     */
    private static function Narrative($PortionData)
    {
        return $PortionData['Narrative'];
    }

    /**
     * @param $PortionData
     * @return array
     */
    public static function Structure($PortionData)
    {
        return [
            'Allergies' => [
                LevelEntry\allergyConcernAct::Structure()
            ]
        ];
    }

    /**
     * @param $CompleteData
     * @return array|Exception
     */
    public static function Insert($PortionData, $CompleteData)
    {
        try
        {
            // Validate first
            self::Validate($PortionData['Allergies']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.6.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '48765-2',
                                'displayName' => 'Allergies, adverse reactions, alerts',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Allergies',
                        'text' => self::Narrative($PortionData['Narrated'])
                    ]
                ]
            ];

            // SHOULD contain zero or more [1..*] entry (CONF:7804) such that it
            // SHALL contain exactly one [1..1] Allergy Concern Act (V2)
            if(count($PortionData['AllergyConcernAct']) > 0)
            {
                foreach ($PortionData['AllergyConcernAct'] as $AllergyConcernAct)
                {
                    $Section['component']['section']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'DRIV'
                        ],
                        LevelEntry\allergyConcernAct::Insert(
                            $AllergyConcernAct,
                            $CompleteData
                        )
                    ];
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
