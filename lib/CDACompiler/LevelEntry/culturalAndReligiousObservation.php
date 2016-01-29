<?php

/**
 * 3.22	Cultural and Religious Observation (NEW)
 *
 * This template represents a patient’s spiritual, religious, and cultural belief practices, such as a kosher
 * diet or fasting ritual. religiousAffiliationCode in the document header captures only the
 * patient’s religious affiliation.
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
class culturalAndReligiousObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception ('SHALL contain exactly one [1..1] value (CONF:28442)');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {
        return $PortionData;
    }

    /**
     * @return array
     */
    public static function Structure(){
        return [
            'CulturalAndReligiousObservation' => [
                'Narrated' => 'SHALL contain exactly one [1..1] value (CONF:28442).'
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
        try {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.111'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '406198009',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'displayName' => 'personal belief pattern'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'ST'
                        ],
                        '@value' => self::Narrative($PortionData['Narrated'])
                    ]
                ]
            ];

            return $Entry;
        } catch (Exception $Error) {
            return $Error;
        }
    }

}
