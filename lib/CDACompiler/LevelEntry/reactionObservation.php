<?php

/**
 * 3.91	Reaction Observation (V2)
 *
 * This clinical statement represents an undesired symptom, finding, etc., due to an administered or
 * exposed substance. A reaction can be defined with respect to its severity, and can have been treated
 * by one or more interventions.
 *
 * Contains:
 * Medication Activity (V2)
 * Procedure Activity Procedure (V2)
 * Severity Observation (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class reactionObservation
 * @package LevelEntry
 */
class reactionObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['problemCode']))
            throw new Exception('SHALL be selected from ValueSet Problem Value Set');
        if(!isset($PortionData['problemCodeSystemName']))
            throw new Exception('SHALL be selected from ValueSet Problem Value Set');
        if(!isset($PortionData['problemDisplayName']))
            throw new Exception('SHALL be selected from ValueSet Problem Value Set');
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
            'ReactionObservation' => [
                'from_effectiveTime' => 'The effectiveTime, if present, SHOULD contain zero or one [0..1] low',
                'to_effectiveTime' => 'The effectiveTime, if present, SHOULD contain zero or one [0..1] high',
                'problemCode' => 'SHALL be selected from ValueSet Problem Value Set',
                'problemCodeSystemName' => 'SHALL be selected from ValueSet Problem Value Set',
                'problemDisplayName' => 'SHALL be selected from ValueSet Problem Value Set',
                severityObservation::Structure(),
                procedureActivityProcedure::Structure(),
                medicationActivity::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.9.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['problemCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['problemCodeSystemName']),
                            'displayName' => $PortionData['problemDisplayName'],
                            'codeSystemName' => $PortionData['problemCodeSystemName']
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or one [0..1] effectiveTime (CONF:7332).
            // The effectiveTime, if present, SHOULD contain zero or one [0..1] low (CONF:7333)
            // The effectiveTime, if present, SHOULD contain zero or one [0..1] high (CONF:7334).
            if(isset($PortionData['from_effectiveTime']))
            {
                $fromEntry['observation']['effectiveTime'] = [
                        'low' => [
                            '@attributes' => [
                                'value' => $PortionData['from_effectiveTime']
                            ]
                        ]
                ];
            }
            else
            {
                $toEntry['observation']['effectiveTime'] = [];
            }
            if(isset($PortionData['to_effectiveTime']))
            {
                $toEntry['observation']['effectiveTime'] = [
                    'high' => [
                        '@attributes' => [
                            'value' => $PortionData['to_effectiveTime']
                        ]
                    ]
                ];
            }
            else
            {
                $toEntry['observation']['effectiveTime'] = [];
            }
            $Entry['observation']['effectiveTime'] = array_merge_recursive($fromEntry, $toEntry);

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Procedure Activity Procedure (V2)
            if(count($PortionData['ProcedureActivityProcedure']) > 0)
            {
                foreach($PortionData['ProcedureActivityProcedure'] as $ProcedureActivityProcedure)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        procedureActivityProcedure::Insert(
                            $ProcedureActivityProcedure,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Medication Activity (V2)
            if(count($PortionData['MedicationActivity']) > 0)
            {
                foreach($PortionData['MedicationActivity'] as $MedicationActivity)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        medicationActivity::Insert(
                            $MedicationActivity,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Severity Observation (V2)
            if(count($PortionData['SeverityObservation']) > 0)
            {
                foreach($PortionData['SeverityObservation'] as $SeverityObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        severityObservation::Insert(
                            $SeverityObservation,
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
