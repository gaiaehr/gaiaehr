<?php

/**
 * 2.17	Fetus Subject Context
 *
 * For reports on mothers and their fetus(es), information on a mother is mapped to recordTarget, PatientRole,
 * and Patient. Information on the fetus is mapped to subject, relatedSubject, and SubjectPerson at the CDA
 * section level. Both context information on the mother and fetus must be included in the document if
 * observations on fetus(es) are contained in the document.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class fetusSubjectContext
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['name']))
            throw new Exception('SHALL contain exactly one [1..1] name');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'FetusSubjectContext' => [
                'name' => 'SHALL contain exactly one [1..1] name'
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
                                'root' => '2.16.840.1.113883.10.20.6.2.3'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '121026',
                                'displayName' => 'Fetus',
                                'codeSystem' => '1.2.840.10008.2.16.4'
                            ]
                        ],
                        'subject' => [
                            'name' => $PortionData['name']
                        ]
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
