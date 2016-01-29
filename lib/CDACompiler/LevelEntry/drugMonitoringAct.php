<?php

/**
 * 3.26	Drug Monitoring Act (NEW)
 *
 * This template represents the person responsible for monitoring the medication. The prescriber of the medication
 * is not necessarily the same person as the one designated to monitor the drug.
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
 * Class drugMonitoringAct
 * @package LevelEntry
 */
class drugMonitoringAct
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime_from']) &&
            !isset($PortionData['effectiveTime_to']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:31922)');

        if(!isset($PortionData['name']['given']) &&
            !isset($PortionData['name']['family']))
            throw new Exception('SHALL contain exactly one [1..1] name (CONF:28669).');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'DrugMonitoringAct' => [
                'effectiveTime_from' => 'SHALL contain exactly one [1..1] effectiveTime (CONF:31922)',
                'effectiveTime_to' => 'SHALL contain exactly one [1..1] effectiveTime (CONF:31922)',
                'name' => [
                    'prefix' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)',
                    'prefixQualifier' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)',
                    'given' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)',
                    'givenQualifier' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)',
                    'family' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)',
                    'familyQualifier' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)',
                    'name' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)',
                    'prefixQualifier' => 'This participantRole SHALL contain exactly one [1..1] playingEntity (CONF:28667)'
                ]
            ]
        ];
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

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
                        'moodCode' => 'INT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.123'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => '395170001',
                            'displayName' => 'medication monitoring(regine/therapy',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'codeSystemName' => 'SNOMED-CT'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => [
                        '@attributes' => [
                            'xsi:type' => 'IVL TS'
                        ],
                        'low' => [
                            '@attributes' => [
                                'value' => $PortionData['effectiveTime_from']
                            ]
                        ],
                        'high' => [
                            '@attributes' => [
                                'value' => $PortionData['effectiveTime_to']
                            ]
                        ]
                    ],
                    'participant' => [
                        '@attributes' => [
                            'classCode' => 'ASSIGNED'
                        ],
                        'id' => Component::id( Utilities::UUIDv4() ),
                        'playingEntity' => [
                            '@attributes' => [
                                'classCode' => 'ASSIGNED'
                            ],
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
