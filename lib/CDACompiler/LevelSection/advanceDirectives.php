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
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
        if(count($Data['AdvanceDirectiveOrganizer']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] Advance Directive Organizer (NEW)');
    }

    /**
     * @param $Data
     * @return mixed
     */
    public static function Narrative($Data)
    {
        return $Data['Narrated'];
    }

    public static function Structure()
    {
        return [
            'AdvanceDirectives' => [
                advanceDirectiveOrganizer::Structure()
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
            self::Validate($Data);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.21',
                                'extension' => $Data['AdvanceDirectives']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '42348-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Advance Directives',
                        'text' => self::Narrative($Data['AdvanceDirectives'])
                    ]
                ]
            ];

            // SHALL contain at least one [1..*] entry
            // SHALL contain exactly one [1..1] Advance Directive Organizer (NEW)
            foreach($Data['AdvanceDirectives'] as $AdvanceDirective) {
                $Section['component']['section']['entry'][] = [
                    '@attributes' => [
                        'typeCode' => 'DRIV'
                    ],
                    'organizer' => LevelEntry\advanceDirectiveOrganizer::Insert($AdvanceDirective, $Data)
                ];
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
