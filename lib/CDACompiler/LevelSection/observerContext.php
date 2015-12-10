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
        if(!isset($PortionData['name']))
            throw new Exception('Either assignedPerson or assignedAuthoringDevice SHALL be present');
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
                'name' => [
                    'prefix' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                    'prefixQualifier' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                    'given' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                    'givenQualifier' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                    'family' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                    'familyQualifier' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                    'name' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                    'nameQualifier' => 'Either assignedPerson or assignedAuthoringDevice SHALL be present',
                ]
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.4'),
                        'id' => Component::id('2.16.840.1.113883.19.5', '121008')
                    ]
                ]
            ];

            // Either assignedPerson or assignedAuthoringDevice SHALL be present
            if(isset($PortionData['name']))
            {
                $Section['component']['section']['assignedPerson'] = [
                    'name' => Component::name(
                        $PortionData['name']['prefix'],
                        $PortionData['name']['prefixQualifier'],
                        $PortionData['name']['given'],
                        $PortionData['name']['givenQualifier'],
                        $PortionData['name']['family'],
                        $PortionData['name']['familyQualifier'],
                        $PortionData['name']['name'],
                        $PortionData['name']['nameQualifier']
                    )
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
