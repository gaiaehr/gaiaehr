<?php

/**
 * 3.62	Outcome Observation (NEW)
 *
 * This template represents the outcome of care resulting from the interventions used to treat the patient.
 *
 * Often thought of as an "actual outcome", the Outcome Observation can be related to goals, progression
 * towards goals, and its associated interventions. For example, an observation outcome of a Pulse Ox reading of
 * 95% is related to the goal of “Maintain Pulse Ox greater than 92” which in turn is related to the health
 * concern of respiratory insufficiency and the problem of pneumonia.
 * The template makes use of the Act Reference (NEW) (templateId:2.16.840.1.113883.10.20.22.4.122) to
 * reference the interventions and goals defined elsewhere in the Care Plan CDA instance.
 *
 * Contains:
 * Act Reference (NEW)
 * Author Participation (NEW)
 * Goal Observation (NEW)
 * Intervention Act (NEW)
 * Progress Toward Goal Observation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class outcomeObservation
 * @package LevelEntry
 */
class outcomeObservation
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
     * @param $Data
     */
    public static function Narrative($PortionData)
    {
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'effectiveTime' => '??',
            'code' => '??',
            'displayName' => '??',
            'codeSystemName' => '??',
            'Author' => LevelOther\authorParticipation::Structure(),
            'GoalObservationEvaluationReference' => actReference::Structure(),
            'GoalObservationReasonReference' => actReference::Structure(),
            'GoalObservation' => goalObservation::Structure(),
            'ProgressTowardGoalObservation' => progressTowardGoalObservation::Structure(),
            'InterventionAct' => interventionAct::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.144'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'displayName' => $PortionData['displayName']
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach($PortionData['Author'] as $Author)
                {
                    $Entry['observation']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Goal Observation (NEW)
            if(count($PortionData['GoalObservation']) > 0)
            {
                foreach($PortionData['GoalObservation'] as $GoalObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'GEVL'
                        ],
                        goalObservation::Insert(
                            $GoalObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // SHOULD contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Progress Toward Goal Observation (NEW)
            if(count($PortionData['ProgressTowardGoalObservation']) > 0)
            {
                foreach($PortionData['ProgressTowardGoalObservation'] as $ProgressTowardGoalObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SPRT'
                        ],
                        progressTowardGoalObservation::Insert(
                            $ProgressTowardGoalObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // SHOULD contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Progress Toward Goal Observation (NEW)
            if(count($PortionData['ProgressTowardGoalObservation']) > 0)
            {
                foreach($PortionData['ProgressTowardGoalObservation'] as $ProgressTowardGoalObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SPRT'
                        ],
                        progressTowardGoalObservation::Insert(
                            $ProgressTowardGoalObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // SHOULD contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Intervention Act (NEW)
            if(count($PortionData['InterventionAct']) > 0)
            {
                foreach($PortionData['InterventionAct'] as $InterventionAct)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        interventionAct::Insert(
                            $InterventionAct,
                            $CompleteData
                        )
                    ];
                }
            }

            /**
             * Where an Outcome Observation needs to reference a Goal Observation already described in the CDA
             * document instance, rather than repeating the full content of the Goal Observation,
             * the Act Reference template may be used to reference this entry.
             */
            // SHOULD contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Intervention Act (NEW)
            if(count($PortionData['GoalObservationEvaluationReference']) > 0)
            {
                foreach($PortionData['GoalObservationEvaluationReference'] as $GoalObservationEvaluationReference)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'GEVL'
                        ],
                        actReference::Insert(
                            $GoalObservationEvaluationReference,
                            $CompleteData
                        )
                    ];
                }
            }

            /**
             * Where an Outcome Observation needs to reference an Intervention Act already described in the CDA
             * document instance, rather than repeating the full content of the Intervention Act, the Act
             * Reference template may be used to reference this entry.
             */
            // SHOULD contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Intervention Act (NEW)
            if(count($PortionData['GoalObservationReasonReference']) > 0)
            {
                foreach($PortionData['GoalObservationReasonReference'] as $GoalObservationReasonReference)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        actReference::Insert(
                            $GoalObservationReasonReference,
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
