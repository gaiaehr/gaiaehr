<?php

/**
 * 3.94	Result Organizer (V2)
 *
 * This template provides a mechanism for grouping result observations. It contains information applicable to all
 * of the contained result observations. The Result Organizer code categorizes the contained results into one of
 * several commonly accepted values (e.g., “Hematology”, “Chemistry”, “Nuclear Medicine”).
 *
 * If any Result Observation within the organizer has a statusCode of ‘active’, the Result Organizer must also
 * have as statusCode of 'active'.
 *
 * Contains:
 * Author Participation (NEW)
 * Result Observation (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class resultOrganizer
 * @package LevelEntry
 */
class resultOrganizer
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['status']))
            throw new Exception('SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet Result Status');
        if(count($PortionData['status']) < 1)
            throw new Exception('a.	SHALL contain exactly one [1..1] Result Observation (V2) ');
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
            'ResultOrganizer' => [
                'code' => 'SHALL contain exactly one [1..1] code',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code',
                'displayName' => 'SHALL contain exactly one [1..1] code',
                'status' => 'SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet Result Status',
                'effectiveTime' => 'The effectiveTime is an interval that spans the effectiveTimes of the contained result observations. Because all contained result observations have a required time stamp, it is not required that this effectiveTime be populated',
                LevelOther\authorParticipation::Structure(),
                resultObservation::Structure()
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
                'organizer' => [
                    '@attributes' => [
                        'classCode' => 'BATTERY',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.1.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode($PortionData['status']),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'PQ',
                            'value' => $PortionData['value'],
                            'unit' => $PortionData['unit']
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['organizer']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] component
            // SHALL contain exactly one [1..1] Result Observation (V2)
            if(count($PortionData['ResultObservation']) > 0)
            {
                foreach($PortionData['ResultObservation'] as $ResultObservation)
                {
                    $Entry['organizer']['component'][] = [
                        resultObservation::Insert(
                            $ResultObservation,
                            $CompleteData
                        )
                    ];
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
