<?php

/**
 * 3.20	Comment Activity
 *
 * Comments are free text data that cannot otherwise be recorded using data elements already defined by this
 * specification. They are not to be used to record information that can be recorded elsewhere. For example,
 * a free text description of the severity of an allergic reaction would not be recorded in a comment.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class advanceDirectiveOrganizer
 * @package LevelEntry
 */
class characteristicsOfHomeEnvironment
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['author']) < 1){
            throw new Exception ('SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet
            Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC (CONF:28823).');
        }
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
    public static function Structure(){
        return [
            'author' => [
                'date' => 'The author, if present, SHALL contain exactly one [1..1] time (CONF:9434)',
                'NPI' => 'This assignedAuthor SHALL contain exactly one [1..1] id (CONF:9436)',
                'address' => [
                    'use' => 'This assignedAuthor SHALL contain exactly one [1..1] addr (CONF:9437)',
                    'streetAddressLine' => 'This assignedAuthor SHALL contain exactly one [1..1] addr (CONF:9437)',
                    'city' => 'This assignedAuthor SHALL contain exactly one [1..1] addr (CONF:9437)',
                    'state' => 'This assignedAuthor SHALL contain exactly one [1..1] addr (CONF:9437)',
                    'postalCode' => 'This assignedAuthor SHALL contain exactly one [1..1] addr (CONF:9437)',
                    'country' => 'This assignedAuthor SHALL contain exactly one [1..1] addr (CONF:9437)'
                ],
                'name' => [
                    'prefix' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                    'prefixQualifier' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                    'given' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                    'givenQualifier' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                    'family' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                    'familyQualifier' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                    'name' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                    'nameQualifier' => 'An assignedPerson/name SHALL be a conformant US Realm Person Name (PN.US.FIELDED) (2.16.840.1.113883.10.20.22.5.1.1) (CONF:9439)',
                ]
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

            $Entry = [
                '@attributes' => [
                    'classCode' => 'ACT',
                    'moodCode' => 'EVN'
                ],
                'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.64'),
                'code' => [
                    '@attributes' => [
                        'code' => '48767-8',
                        'codeSystem' => '2.16.840.1.113883.6.1',
                        'codeSystemName' => 'LOINC',
                        'displayName' => 'Annotation Comment'
                    ]
                ],
                'text' => self::Narrative($PortionData)
            ];

            // MAY contain zero or one [0..1] author (CONF:9433).
            if(count($PortionData['author']) > 0)
                $Entry['author'][] = self::author($PortionData['author']);

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

    /**
     * @param $PortionData
     * @return array
     */
    function author($PortionData)
    {
        return [
            'time' => Component::effectiveTime($PortionData['date']),
            'assignedAuthor' => [
                'id' => Component::id('2.16.840.1.113883.19.5', $PortionData['NPI']),
                'addr' => Component::addr(
                    $PortionData['address']['use'],
                    $PortionData['address']['streetAddressLine'],
                    $PortionData['address']['city'],
                    $PortionData['address']['state'],
                    $PortionData['address']['postalCode'],
                    $PortionData['address']['country']
                ),
                'telecom' => Component::telecom(
                    $PortionData['use'],
                    $PortionData['telecom']
                ),
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
                ]
            ]
        ];
    }

}
