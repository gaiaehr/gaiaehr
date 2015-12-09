<?php

/**
 * 2.2	Advance Directives Section (entries required)  (V2)
 *
 * This section contains data defining the patient’s advance directives and any reference to supporting
 * documentation, including living wills, healthcare proxies, and CPR and resuscitation status.
 * If the referenced documents are available, they can be included in the exchange package.
 *
 * The most recent directives are required, if known, and should be listed in as much detail as possible.
 *
 * This section differentiates between 'advance directives' and 'advance directive documents'.
 * The former is the directions to be followed whereas the latter refers to a legal document
 * containing those directions.

 *
 * Contains:
 * Advance Directive Organizer (NEW)
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class advanceDirectives
{

    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] text');
        if(count($PortionData['AdvanceDirectives'])<0)
            throw new Exception('SHALL contain exactly one [1..1] Advance Directive Organizer (NEW)');
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
    }

    public static function Structure()
    {
        return [
            'AdvanceDirectives' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\advanceDirectiveOrganizer::Structure()
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.2.21'),
                        'code' => [
                            '@attributes' => [
                                'code' => '42348-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Advance Directives',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHALL contain at least one [1..*] entry
            // SHALL contain exactly one [1..1] Advance Directive Organizer (NEW)
            if(count($PortionData['AdvanceDirectives'])>0)
            {
                foreach ($PortionData['AdvanceDirectives'] as $AdvanceDirective) {
                    $Section['component']['section']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'DRIV'
                        ],
                        LevelEntry\advanceDirectiveOrganizer::Insert(
                            $AdvanceDirective,
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
