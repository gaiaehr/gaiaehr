<?php

/**
 * 3.78	Problem Concern Act (Condition) (V2)
 *
 * This template reflects an ongoing concern on behalf of the provider that placed the concern on a patient’s
 * problem list. So long as the underlying condition is of concern to the provider (i.e. so long as the condition,
 * whether active or resolved, is of ongoing concern and interest to the provider), the statusCode is “active”.
 * Only when the underlying condition is no longer of concern is the statusCode set to “completed”.
 * The effectiveTime reflects the time that the underlying condition was felt to be a concern – it may or may
 * not correspond to the effectiveTime of the condition (e.g. even five years later, the clinician may remain
 * concerned about a prior heart attack).
 *
 * The statusCode of the Problem Concern Act (Condition) is the definitive indication of the status of the
 * concern, whereas the effectiveTime of the nested Problem Observation is the definitive indication of
 * whether or not the underlying condition is resolved.
 *
 * The effectiveTime/low of the Problem Concern Act (Condition) asserts when the concern became active.
 * This equates to the time the concern was authored in the patient's chart. The effectiveTime/high asserts when
 * the concern was completed (e.g. when the clinician deemed there is no longer any need to track the
 * underlying condition).
 *
 * A Problem Concern Act (Condition) can contain many Problem Observations
 * (templateId 2.16.840.1.113883.10.20.22.4.4.2). Each Problem Observation is a discrete observation of a
 * condition, and therefore will have a statusCode of “completed”. The many Problem Observations nested under a
 * Problem Concern Act (Condition) reflect the change in the clinical understanding of a condition over time.
 * For instance, a Concern may initially contain a Problem Observation of “chest pain”:
 *
 * - Problem Concern 1
 * --- Problem Observation: Chest Pain
 *
 * Later, a new Problem Observation of “esophagitis” will be added, reflecting a better understanding of the
 * nature of the chest pain. The later problem observation will have a more recent author time stamp.
 *
 * - Problem Concern 1
 * --- Problem Observation (author/time Jan 3, 2012): Chest Pain
 * --- Problem Observation (author/time Jan 6, 2012): Esophagitis
 *
 * Many systems display the nested Problem Observation with the most recent author time stamp, and provide a
 * mechanism for viewing prior observations.
 *
 * Contains:
 * Author Participation (NEW)
 * Problem Observation (V2)
 * Provider Priority Preference (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class problemConcernActCondition
 * @package LevelEntry
 */
class problemConcernActCondition
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['statusCode']))
            throw new Exception('This statusCode SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet ProblemAct statusCode');
        if(!isset($PortionData['start_effectiveTime']))
            throw new Exception('This effectiveTime SHALL contain exactly one [1..1] low (CONF:9032). Note: The effectiveTime/low asserts when the concern became active. This equates to the time the concern was authored in the patient\'s chart');
        if(count($PortionData['ProblemObservation']) < 1)
            throw new Exception('b.	SHALL contain exactly one [1..1] Problem Observation (V2) ');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
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
            'ProblemConcernActCondition' => [
                'statusCode' => 'This statusCode SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet ProblemAct statusCode',
                'start_effectiveTime' => 'This effectiveTime SHALL contain exactly one [1..1] low (CONF:9032). Note: The effectiveTime/low asserts when the concern became active. This equates to the time the concern was authored in the patient\'s chart.',
                'end_effectiveTime' => 'This effectiveTime MAY contain zero or one [0..1] high (CONF:9033). Note: The effectiveTime/high asserts when the concern was completed (e.g. when the clinician deemed there is no longer any need to track the underlying condition)',
                problemObservation::Structure(),
                LevelOther\authorParticipation::Structure(),
                providerPriorityPreference::Structure()
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
                'act' => [
                    '@attributes' => [
                        'moodCode' => 'EVN',
                        'classCode' => 'ACT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.3.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => 'CONC',
                        'codeSystem' => '2.16.840.1.113883.5.6',
                        'displayName' => 'Concern'
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => $PortionData['statusCode']
                        ]
                    ],
                    'effectiveTime' => [
                        'low' => [
                            '@attributes' => [
                                'value' => $PortionData['start_effectiveTime']
                            ]
                        ]
                    ]
                ]
            ];

            // This effectiveTime MAY contain zero or one [0..1] high (CONF:9033).
            // Note: The effectiveTime/high asserts when the concern was completed
            // (e.g. when the clinician deemed there is no longer any need to track the underlying condition)
            if(isset($PortionData['end_effectiveTime']))
            {
                $Entry['act']['effectiveTime']['high'] = [
                    '@attributes' => [
                        'value' => $PortionData['end_effectiveTime']
                    ]
                ];
            }

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['act']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship (CONF:10093)
            // SHALL contain exactly one [1..1] Problem Observation (V2)
            if(count($PortionData['ProblemObservation']) > 0)
            {
                foreach($PortionData['ProblemObservation'] as $ProblemObservation)
                {
                    $Entry['supply']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        problemObservation::Insert(
                            $ProblemObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // SHALL contain at least one [1..*] entryRelationship (CONF:10093)
            // b.	SHALL contain exactly one [1..1] Provider Priority Preference (NEW)
            if(count($PortionData['ProviderPriorityPreference']) > 0)
            {
                foreach($PortionData['ProblemObservation'] as $ProviderPriorityPreference)
                {
                    $Entry['supply']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        providerPriorityPreference::Insert(
                            $ProviderPriorityPreference,
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
