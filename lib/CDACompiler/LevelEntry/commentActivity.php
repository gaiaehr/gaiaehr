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

use LevelDocument;
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
            'CharacteristicsOfHomeEnvironment' => [
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
                'act' => [
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
                ]
            ];

            // MAY contain zero or one [0..1] author (CONF:9433).
            if(count($PortionData['Author']) > 0)
                $Entry['act']['author'][] = LevelDocument\author::Insert($PortionData['Author'][0]);

            return $Entry;

        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
