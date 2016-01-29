<?php

/**
 * 3.100	Social History Observation (V2)
 *
 * This template represents a patient's occupations, lifestyle, and environmental health risk factors.
 * Demographic data (e.g. marital status, race, ethnicity, religious affiliation) is captured in the header.
 *
 * Contains:
 * Author Participation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class severityObservation
 * @package LevelEntry
 */
class severityObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['severityCode']))
            throw new Exception('SHALL be selected from ValueSet Problem Severity');

        if(!isset($PortionData['severityCodeSystemName']))
            throw new Exception('SHALL be selected from ValueSet Problem Severity');

        if(!isset($PortionData['severityDisplayName']))
            throw new Exception('SHALL be selected from ValueSet Problem Severity');
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
    public static function Structure()
    {
        return [
            'SeriesAct' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'socialCode' => 'SHOULD be selected from ValueSet Social History Type Value Set',
                'socialCodeSystemName' => 'SHOULD be selected from ValueSet Social History Type Value Set',
                'socialDisplayName' => 'SHOULD be selected from ValueSet Social History Type Value Set',
                'measureValue' => 'SHOULD contain zero or one [0..1] value',
                'measureUnit' => 'SHOULD contain zero or one [0..1] value'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.38.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['socialCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['socialCodeSystemName']),
                            'codeSystemName' => $PortionData['socialCodeSystemName'],
                            'displayName' => $PortionData['socialDisplayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'PQ',
                            'value' => $PortionData['measureValue'],
                            'unit' => $PortionData['measureUnit']
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['observation']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
