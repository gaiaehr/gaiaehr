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

class observerContext
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
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
            'observerContext' => [

            ]
        ];
    }

    /**
     * @param $PortionData
     * @return array|Exception
     */
    public static function Insert($PortionData)
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
                                'root' => '2.16.840.1.113883.10.20.6.2.4'
                            ]
                        ],
                        'id' => Component::id('2.16.840.1.113883.19.5', '121008'),
                        'assignedPerson' => [
                            'name' => Component::name(
                                $PortionData['ObserverContext']['name']['prefix'],
                                $PortionData['ObserverContext']['name']['prefixQualifier'],
                                $PortionData['ObserverContext']['name']['given'],
                                $PortionData['ObserverContext']['name']['givenQualifier'],
                                $PortionData['ObserverContext']['name']['family'],
                                $PortionData['ObserverContext']['name']['familyQualifier'],
                                $PortionData['Patient']['name']['name'],
                                $PortionData['ObserverContext']['name']['nameQualifier']
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
