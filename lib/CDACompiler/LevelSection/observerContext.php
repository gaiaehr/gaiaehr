<?php

/**
 * 2.49	Observer Context
 *
 * The Observer Context is used to override the author specified in the CDA Header.
 * It is valid as a direct child element of a section.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Component;
use Utilities;
use Exception;

class procedures
{
    /**
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
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
                                'root' => '2.16.840.1.113883.10.20.6.2.4'
                            ]
                        ],
                        'id' => Component\id('2.16.840.1.113883.19.5', '121008'),
                        'assignedPerson' => [
                            'name' => Component\name(
                                $Data['ObserverContext']['name']['prefix'],
                                $Data['ObserverContext']['name']['prefixQualifier'],
                                $Data['ObserverContext']['name']['given'],
                                $Data['ObserverContext']['name']['givenQualifier'],
                                $Data['ObserverContext']['name']['family'],
                                $Data['ObserverContext']['name']['familyQualifier'],
                                $Data['Patient']['name']['name'],
                                $Data['ObserverContext']['name']['nameQualifier']
                            )
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
