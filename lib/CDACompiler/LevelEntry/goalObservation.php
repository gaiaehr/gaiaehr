<?php

/**
 * 3.37	Goal Observation (NEW)
 *
 * This template represents a patient care goal.  A Goal Observation template may have related components that are
 * acts, encounters, observations, procedures, substance administrations or supplies.
 *
 * A goal may be a patient or provider goal.  If the author is set to the recordTarget (patient), this is a
 * patient goal.  If the author is set to a provider, this is a provider goal. If both patient and provider are
 * set as authors, this is a negotiated goal.
 *
 * A goal usually has a related health concern and/or risk.
 *
 * A goal can have components consisting of other goals (milestones), these milestones are related to the
 * overall goal through entryRelationships.
 *
 * Contains:
 * Act Reference (NEW)
 * Author Participation (NEW)
 * Health Concern Act (NEW)
 * Patient Priority Preference (NEW)
 * Planned Act (V2)
 * Planned Encounter (V2)
 * Planned Observation (V2)
 * Planned Procedure (V2)
 * Planned Substance Administration (V2)
 * Planned Supply (V2)
 * Provider Priority Preference (NEW)
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
class goalObservation
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
            (templateId:2.16.840.1.113883.10.20.22.4.119) (CONF:30995)');

        if(!isset($PortionData['goalCode']) &&
            !isset($PortionData['goalDisplayName']) &&
            !isset($PortionData['goalCodeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code (CONF:30784)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'goalCode' => 'SHALL contain exactly one [1..1] code (CONF:30784)',

            'goalDisplayName' => 'SHALL contain exactly one [1..1] code (CONF:30784)',

            'goalCodeSystemName' => 'SHALL contain exactly one [1..1] code (CONF:30784)',

            'effectiveTime' => 'effectiveTime',

            'Authors' => LevelOther\authorParticipation::Structure(),

            'HealthConcernAct' => healthConcernAct::Structure(),

            'PlannedEncounter' => plannedEncounter::Structure(),

            'PlannedObservation' => plannedObservation::Structure(),

            'PlannedProcedure' => plannedProcedure::Structure(),

            'PlannedSubstanceAdministration' => plannedSubstanceAdministration::Structure(),

            'PlannedSupply' => plannedSupply::Structure(),

            'PlannedAct' => plannedAct::Structure(),

            'PatientPriorityPreference' => patientPriorityPreference::Structure(),

            'ProviderPriorityPreference' => providerPriorityPreference::Structure(),

            'ActReference' => actReference::Structure()
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
                '@attributes' => [
                    'classCode' => 'OBS',
                    'moodCode' => 'GOL'
                ],
                'templateId' => [
                    // Observation Plan (V2) templateId
                    0 => Component::templateId('2.16.840.1.113883.10.20.22.4.44.2'),
                    // Goal Observation templateId
                    1 => Component::templateId('2.16.840.1.113883.10.20.22.4.44.2')
                ],
                'id' => Component::id( Utilities::UUIDv4() ),
                'code' => [
                    '@attributes' => [
                        'code' => $PortionData['goalCode'],
                        'displayName' => $PortionData['goalDisplayName'],
                        'codeSystem' => Utilities::CodingSystemId( $PortionData['goalCodeSystemName'] ),
                        'codeSystemName' => $PortionData['goalCodeSystemName']
                    ]
                ],
                'statusCode' => Component::statusCode('active'),
                'effectiveTime' => Component::time($PortionData['effectiveTime'])
            ];

            // SHALL contain at least one [1..*] Author Participation (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.119) (CONF:30995)
            if(count($PortionData['Authors']) > 0)
            {
                foreach($PortionData['Authors'] as $Author)
                {
                    $Entry['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entryRelationship (CONF:30701)
            // SHALL contain exactly one [1..1] Health Concern Act (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.132) (CONF:30703)
            if(count($PortionData['HealthConcernAct']) > 0)
            {
                foreach($PortionData['HealthConcernAct'] as $HealthConcernAct)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'act' => healthConcernAct::Insert(
                            $HealthConcernAct,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:30704)
            // SHALL contain exactly one [1..1] Planned Encounter (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.40.2) (CONF:30706)
            if(count($PortionData['PlannedEncounter']) > 0)
            {
                foreach($PortionData['PlannedEncounter'] as $PlannedEncounter)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'entry' => [
                            'encounter' => plannedEncounter::Insert(
                                $PlannedEncounter,
                                $CompleteData
                            )
                        ]
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:30707)
            // SHALL contain exactly one [1..1] Planned Observation (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.44.2) (CONF:30709)
            if(count($PortionData['PlannedObservation']) > 0)
            {
                foreach($PortionData['PlannedObservation'] as $PlannedObservation)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'entry' => [
                            'encounter' => plannedObservation::Insert(
                                $PlannedObservation,
                                $CompleteData
                            )
                        ]
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:30710)
            // SHALL contain exactly one [1..1] Planned Procedure (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.41.2) (CONF:30712)
            if(count($PortionData['PlannedProcedure']) > 0)
            {
                foreach($PortionData['PlannedProcedure'] as $PlannedProcedure)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'entry' => [
                            'encounter' => plannedProcedure::Insert(
                                $PlannedObservation,
                                $CompleteData
                            )
                        ]
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:30713)
            // SHALL contain exactly one [1..1] Planned Substance Administration (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.42.2) (CONF:30715)
            if(count($PortionData['PlannedSubstanceAdministration']) > 0)
            {
                foreach($PortionData['PlannedSubstanceAdministration'] as $PlannedSubstanceAdministration)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'entry' => [
                            'encounter' => plannedSubstanceAdministration::Insert(
                                $PlannedSubstanceAdministration,
                                $CompleteData
                            )
                        ]
                    ];
                }
            }


            // MAY contain zero or more [0..*] entryRelationship (CONF:30716)
            // SHALL contain exactly one [1..1] Planned Supply (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.43.2) (CONF:30718)
            if(count($PortionData['PlannedSupply']) > 0)
            {
                foreach($PortionData['PlannedSupply'] as $PlannedSupply)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'entry' => [
                            'encounter' => plannedSupply::Insert(
                                $PlannedSupply,
                                $CompleteData
                            )
                        ]
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:30770)
            // SHALL contain exactly one [1..1] Planned Act (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.39.2) (CONF:30772)
            if(count($PortionData['PlannedAct']) > 0)
            {
                foreach($PortionData['PlannedAct'] as $PlannedAct)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        'entry' => [
                            'encounter' => plannedAct::Insert(
                                $PlannedAct,
                                $CompleteData
                            )
                        ]
                    ];
                }
            }

            // SHOULD contain zero or one [0..1] entryRelationship (CONF:30785)
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.142) (CONF:30787)
            if(count($PortionData['PatientPriorityPreference']) > 0)
            {
                foreach($PortionData['PatientPriorityPreference'] as $PatientPriorityPreference)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        'observation' => patientPriorityPreference::Insert(
                            $PatientPriorityPreference,
                            $CompleteData
                        )
                    ];
                }
            }

            // SHOULD contain zero or more [0..*] entryRelationship (CONF:30788)
            // SHALL contain exactly one [1..1] Provider Priority Preference (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.143) (CONF:30790)
            if(count($PortionData['ProviderPriorityPreference']) > 0)
            {
                foreach($PortionData['ProviderPriorityPreference'] as $ProviderPriorityPreference)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        'observation' => providerPriorityPreference::Insert(
                            $ProviderPriorityPreference,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:31559)
            // SHALL contain exactly one [1..1] Act Reference (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.122) (CONF:31588)
            if(count($PortionData['ActReference']) > 0)
            {
                foreach($PortionData['ActReference'] as $ActReference)
                {
                    $Entry['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        'observation' => actReference::Insert(
                            $ActReference,
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
