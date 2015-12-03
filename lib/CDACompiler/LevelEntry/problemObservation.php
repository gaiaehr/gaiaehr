<?php

/**
 * 3.79	Problem Observation (V2)
 *
 * This template reflects a discrete observation about a patient's problem. Because it is a discrete observation,
 * it will have a statusCode of "completed". The effectiveTime, also referred to as the “biologically relevant time”
 * is the time at which the observation holds for the patient. For a provider seeing a patient in the clinic today,
 * observing a history of heart attack that occurred five years ago, the effectiveTime is five years ago.
 *
 * The effectiveTime of the Problem Observation is the definitive indication of whether or not the underlying
 * condition is resolved. If the problem is known to be resolved, then an effectiveTime/high would be present.
 * If the date of resolution is not known, then effectiveTime/high will be present with a nullFlavor of "UNK".
 *
 * Contains:
 * Age Observation
 * Author Participation (NEW)
 * Patient Priority Preference (NEW)
 * Prognosis Observation (NEW)
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
class problemObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['onsetDate']))
            throw new Exception('This effectiveTime SHALL contain exactly one [1..1] low Note: The effectiveTime/low (a.k.a. "onset date") asserts when the condition became biologically active');
        if(!isset($PortionData['code']))
            throw new Exception('SHOULD be selected from ValueSet Problem Value Set');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Problem Value Set');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHOULD be selected from ValueSet Problem Value Set');
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
            'ProblemObservation' => [
                'onsetDate' => 'This effectiveTime SHALL contain exactly one [1..1] low Note: The effectiveTime/low (a.k.a. "onset date") asserts when the condition became biologically active',
                'resolvedDate' => 'This effectiveTime MAY contain zero or one [0..1] high Note: The effectiveTime/high (a.k.a. "resolution date") asserts when the condition became biologically resolved.',
                'code' => 'SHOULD be selected from ValueSet Problem Value Set',
                'codeSystemName' => 'SHALL contain exactly one [1..1] value with @xsi:type="CD", where the code SHOULD be selected from ValueSet Problem Value Set',
                'displayName' => 'SHALL contain exactly one [1..1] value with @xsi:type="CD", where the code SHOULD be selected from ValueSet Problem Value Set',
                'Narrated' => 'SHOULD contain zero or one [0..1] text',
                LevelOther\authorParticipation::Structure(),
                ageObservation::Structure(),
                prognosisObservation::Structure(),
                patientPriorityPreference::Structure(),
                providerPriorityPreference::Structure(),
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.4.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => '64572001',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'displayName' => 'Condition'
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => [
                        'low' => [
                            '@attributes' => [
                                'value' => $PortionData['onsetDate']
                            ]
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or one [0..1] text
            if(isset($PortionData['Narrated']))
            {
                $Entry['observation']['text'] = self::Narrative($PortionData['Narrated']);
            }

            // This effectiveTime MAY contain zero or one [0..1] high (CONF:9033).
            // Note: The effectiveTime/high asserts when the concern was completed
            // (e.g. when the clinician deemed there is no longer any need to track the underlying condition)
            if(isset($PortionData['resolvedDate']))
            {
                $Entry['observation']['effectiveTime']['high'] = [
                    '@attributes' => [
                        'value' => $PortionData['resolvedDate']
                    ]
                ];
            }

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

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Age Observation
            if(count($PortionData['AgeObservation']) > 0)
            {
                foreach($PortionData['AgeObservation'] as $AgeObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        ageObservation::Insert(
                            $AgeObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Prognosis Observation (NEW)
            if(count($PortionData['PrognosisObservation']) > 0)
            {
                foreach($PortionData['PrognosisObservation'] as $PrognosisObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        prognosisObservation::Insert(
                            $PrognosisObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
            if(count($PortionData['PatientPriorityPreference']) > 0)
            {
                foreach($PortionData['PatientPriorityPreference'] as $PatientPriorityPreference)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        patientPriorityPreference::Insert(
                            $PatientPriorityPreference,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Prognosis Observation (NEW)
            if(count($PortionData['ProviderPriorityPreference']) > 0)
            {
                foreach($PortionData['ProviderPriorityPreference'] as $ProviderPriorityPreference)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
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
