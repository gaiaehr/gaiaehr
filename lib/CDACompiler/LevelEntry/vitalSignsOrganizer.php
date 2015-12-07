<?php

/**
 * 5.66 Vital Signs Organizer
 *
 * The Vital Signs Organizer groups vital signs, which is similar to the Result Organizer,
 * but with further constraints.
 *
 * An appropriate nullFlavor can be used when a single result observation is contained in
 * the organizer, and organizer/code or organizer/id is unknown.
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
 * Class VitalSignsOrganizer
 * @package LevelEntry
 */
class vitalSignsOrganizer {

    /**
     * @param $Data
     * @throws Exception
     */
    public static function Validate($PortionData)
    {
        if(count($PortionData['VitalSignObservation']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] Vital Sign Observation (V2)');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     * @throws Exception
     */
    public static function Narrative($PortionData)
    {

    }

    public static function Structure(){
        return [
            'VitalSignsOrganizer' => [
                'effectiveTime' => 'MAY contain zero or one [0..1] effectiveTime',
                LevelOther\authorParticipation::Structure(),
                vitalSignObservation::Structure()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|\Exception|Exception
     */
    public static function insert($PortionData, $CompleteData)
    {
        try{
            // Validate first
            self::Validate($PortionData);

            // Compose the segment
            $Entry = [
                'organizer' => [
                    '@attributes' => [
                        'classCode' => 'CLUSTER',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.26.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '46680005',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'codeSystemName' => 'SNOMED CT',
                            'displayName' => 'Vital signs'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
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

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Vital Sign Observation (V2)
            if(count($PortionData['VitalSignObservation']) > 0)
            {
                foreach ($PortionData['VitalSignObservation'] as $VitalSignObservation)
                {
                    $Entry['organizer']['entryRelationship'][] = vitalSignObservation::Insert(
                        $VitalSignObservation,
                        $CompleteData
                    );
                }
            }

            return $Entry;
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }

}
