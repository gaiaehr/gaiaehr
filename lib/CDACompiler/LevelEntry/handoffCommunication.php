<?php

/**
 * 3.38	Handoff Communication (NEW)
 *
 * This template represents provider hand-off communication. The 'hand-off' process involves senders, those
 * transmitting the patient's information and releasing the care of that patient to the next clinician, and
 * receivers, those who accept the patient information and care of that patient.
 *
 * Contains:
 * Author Participation (NEW)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class functionalStatusOrganizer
 * @package LevelEntry
 */
class handoffCommunication
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['Authors']) < 1)
            throw new Exception('SHALL contain at least one [1..*] Author Participation (NEW)
            (templateId:2.16.840.1.113883.10.20.22.4.119) (CONF:31672)');

        if(!isset($PortionData['participant']['taxonomyCode']) &&
            !isset($PortionData['participant']['taxonomyCodeSystemName']) &&
            !isset($PortionData['participant']['taxonomyDisplayName']))
            throw new Exception('SHOULD be selected from ValueSet Healthcare Provider Taxonomy (HIPAA)
            2.16.840.1.114222.4.11.1066 (CONF:31676)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'HandoffCommunication' => [
                'participant' => [
                    'taxonomyCode' => 'SHOULD be selected from ValueSet Healthcare Provider Taxonomy (HIPAA)',
                    'taxonomyCodeSystemName' => 'SHOULD be selected from ValueSet Healthcare Provider Taxonomy (HIPAA)',
                    'taxonomyDisplayName' => 'SHOULD be selected from ValueSet Healthcare Provider Taxonomy (HIPAA)',
                ],
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime (CONF:31670)',
                LevelDocument\author::Structure()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.141'),
                    'code' => [
                        '@attributes' => [
                            'code' => '432138007',
                            'displayName' => 'handoff communication (procedure)',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'codeSystemName' => 'SNOMED CT'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'author' => LevelDocument\author::Insert($PortionData['Author']),
                    'participant' => [
                        '@attributes' => [
                            'typeCode' => 'IRCP'
                        ],
                        'participantRole' => [
                            'code' => $PortionData['participant']['taxonomyCode'],
                            'codeSystem' => Utilities::CodingSystemId( $PortionData['participant']['taxonomyCodeSystemName'] ),
                            'codeSystemName' => $PortionData['participant']['taxonomyCodeSystemName'],
                            'displayName' => $PortionData['participant']['taxonomyDisplayName']
                        ]
                    ]
                ]
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
