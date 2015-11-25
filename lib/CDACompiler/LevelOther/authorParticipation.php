<?php

/**
 * 4.1 Author Participation (NEW)
 *
 * This template represents the Author Participation (including the author timestamp).
 * CDA R2 requires that Author and Author timestamp be asserted in the document header.
 * From there, authorship propagates to contained sections and contained entries, unless explicitly overridden.
 *
 */

namespace LevelOther;

use Component;
use Utilities;
use Exception;

/**
 * Class authorParticipation
 * @package LevelEntry
 */
class authorParticipation
{

    /**
     * @param $Data
     */
    private static function Validate($Data)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

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

            $Entry = [
                '@attributes' => [
                    'typeCode' => 'AUT'
                ],
                'time' => Component::time($PortionData['datetime']),
                'assignedAuthor' => [
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'code' => $PortionData['taxonomy'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'displayName' => $PortionData['taxonomyName']
                    ],
                    'assignedPerson' => [
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
                    ],
                    'representedOrganization' => [
                        'id' => Component::id('2.16.840.1.113883.19.5'),
                        'name' => $PortionData['Clinic']['name']
                    ]
                ]
            ];

            // Compile advanceDirectiveObservation
            // [1..*]
            foreach ($PortionData['observations'] as $Observation)
            {
                $Entry['component'][] = advanceDirectiveObservation::Insert($Observation);
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
