<?php

/**
 * 3.82	Procedure Activity Observation (V2)
 *
 * The common notion of procedure is broader than that specified by the HL7 Version 3
 * Reference Information Model (RIM). Therefore procedure templates can be represented with various
 * RIM classes: act (e.g., dressing change), observation (e.g., EEG), procedure (e.g. splenectomy).
 *
 * This template represents procedures that result in new information about the patient that cannot be
 * classified as a procedure according to the HL7 RIM. Examples of these procedures are diagnostic
 * imaging procedures, EEGs, and EKGs.
 *
 * Contains:
 * Indication (V2)
 * Instruction (V2)
 * Medication Activity (V2)
 * Service Delivery Location
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class procedureActivityObservation
 * @package LevelEntry
 */
class procedureActivityObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['statusCode']))
            throw new Exception('SHALL contain exactly one [1..1] statusCode, which SHALL be selected from ValueSet ProcedureAct statusCode');
        if(!isset($PortionData['activityCode']))
            throw new Exception('This code in a procedure activity act SHOULD be selected from LOINC');
        if(!isset($PortionData['activityCodeSystemName']))
            throw new Exception('This code in a procedure activity act SHOULD be selected from LOINC');
        if(!isset($PortionData['activityDisplayName']))
            throw new Exception('This code in a procedure activity act SHOULD be selected from LOINC');
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
            'ProcedureActivityObservation' => [
                'statusCode' => 'SHALL contain exactly one [1..1] statusCode, which SHALL be selected from ValueSet ProcedureAct statusCode',
                'effectiveTime' => 'This effectiveTime MAY contain zero or one [0..1] high Note: The effectiveTime/high (a.k.a. "resolution date") asserts when the condition became biologically resolved.',
                'activityCode' => 'This code in a procedure activity act SHOULD be selected from LOINC',
                'activityCodeSystemName' => 'This code in a procedure activity act SHOULD be selected from LOINC',
                'activityDisplayName' => 'This code in a procedure activity act SHOULD be selected from LOINC',
                'Narrated' => 'SHALL point to its corresponding narrative (using the approach defined in CDA Release 2, section 4.3.5.1)',
                'priorityCode' => 'SHALL be selected from ValueSet Act Priority Value Set',
                'priorityCodeSystemName' => 'SHALL be selected from ValueSet Act Priority Value Set',
                'priorityDisplayName' => 'SHALL be selected from ValueSet Act Priority Value Set',
                'targetSiteCode' => [
                    0 => [
                        'targetSiteCode' => 'SHALL be selected from ValueSet Body Site Value Set',
                        'targetSiteCodeName' => 'SHALL be selected from ValueSet Body Site Value Set',
                        'targetSiteDisplayName' => 'SHALL be selected from ValueSet Body Site Value Set'
                    ]
                ],
                LevelDocument\performer::Structure(),
                LevelDocument\participant::Structure(),
                instruction::Structure(),
                indication::Structure(),
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
                        'moodCode' => 'EVN',
                        'classCode' => 'OBS'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.13.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => $PortionData['activityCode'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['activityCodeSystemName']),
                        'displayName' => $PortionData['activityDisplayName'],
                        'codeSystemName' => $PortionData['activityCodeSystemName']
                    ],
                    'statusCode' => Component::statusCode($PortionData['statusCode']),
                    'effectiveTime' => Component::effectiveTime($PortionData['effectiveTime']),
                    'priorityCode' => [
                        '@attributes' => [
                            'code' => $PortionData['priorityCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['priorityCodeSystemName']),
                            'codeSystemName' => $PortionData['priorityCodeSystemName'],
                            'displayName' => $PortionData['priorityDisplayName']
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD'
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] targetSiteCode
            // SHALL be selected from ValueSet Body Site Value Set
            if(count($PortionData['targetSiteCode']) > 0)
            {
                foreach($PortionData['targetSiteCode'] as $targetSiteCode)
                {
                    $Entry['observation']['targetSiteCode'][] = [
                        'code' => $targetSiteCode['targetSiteCode'],
                        'codeSystem' => Utilities::CodingSystemId($targetSiteCode['targetSiteCodeName']),
                        'codeSystemName' => $targetSiteCode['targetSiteCodeName'],
                        'displayName' => $targetSiteCode['targetSiteDisplayName'],
                    ];
                }
            }

            // SHOULD contain zero or one [0..1] text
            if(isset($PortionData['Narrated']))
            {
                $Entry['observation']['code']['originalText']['reference'] = [
                    '@attributes' => [
                        'value' => self::Narrative($PortionData['Narrated'])
                    ]
                ];
            }

            // SHOULD contain zero or more [0..*] performer
            if(count($PortionData['Performer']) > 0)
            {
                foreach ($PortionData['Performer'] as $Performer)
                {
                    $Entry['observation']['performer'][] = LevelDocument\performer::Insert(
                        $Performer,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] participant
            if(count($PortionData['Participant']) > 0)
            {
                foreach ($PortionData['Participant'] as $Participant)
                {
                    $Entry['observation']['participant'][] = LevelDocument\participant::Insert(
                        $Participant,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['Instruction']) > 0)
            {
                foreach($PortionData['Instruction'] as $Instruction)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        instruction::Insert(
                            $Instruction,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Indication (V2)
            if(count($PortionData['Indication']) > 0)
            {
                foreach($PortionData['Indication'] as $Indication)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        indication::Insert(
                            $Indication,
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
                            'typeCode' => 'COMP'
                        ],
                        medicationActivity::Insert(
                            $MedicationActivity,
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
