<?php

/**
 * 4.7	US Realm Person Name (PN.US.FIELDED)
 *
 * The US Realm Clinical Document Person Name datatype flavor is a set of reusable constraints that
 * can be used for Persons.
 *
 * Contains:
 *
 */

namespace LevelOther;

use Component;
use Utilities;
use Exception;

/**
 * Class usRealmPersonNamePNUSFIELDED
 * @package LevelEntry
 */
class usRealmPersonNamePNUSFIELDED
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
            'prefix' => 'SHALL contain exactly one [1..1] name',
            'prefixQualifier' => 'SHALL contain exactly one [1..1] name',
            'given' => 'SHALL contain exactly one [1..1] name',
            'givenQualifier' => 'SHALL contain exactly one [1..1] name',
            'family' => 'SHALL contain exactly one [1..1] name',
            'familyQualifier' => 'SHALL contain exactly one [1..1] name',
            'name' => 'SHALL contain exactly one [1..1] name',
            'nameQualifier' => 'SHALL contain exactly one [1..1] name'
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|Exception
     */
    public static function Insert($PortionData, $CompleteData)
    {
        try {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'name' => Component::name(
                    $PortionData['prefix'],
                    $PortionData['prefixQualifier'],
                    $PortionData['given'],
                    $PortionData['givenQualifier'],
                    $PortionData['family'],
                    $PortionData['familyQualifier'],
                    $PortionData['name'],
                    $PortionData['nameQualifier']
                )
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
