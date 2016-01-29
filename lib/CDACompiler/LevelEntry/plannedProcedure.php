<?php

/**
 * 3.69	Planned Procedure (V2)
 *
 * The Planned Procedure represents planned alterations of the physical condition. Examples of such procedures
 * are tracheostomy, knee replacements, and  craniectomy. The priority of the procedure to the patient and
 * provider is communicated through Patient Priority Preference and Provider Priority Preference.
 * The effective time indicates the time when the procedure is intended to take place.
 *
 * Contains:
 * Patient Priority Preference (NEW)
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
 * Class plannedObservation
 * @package LevelEntry
 */
class plannedProcedure
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['moodCode']))
            throw new Exception('SHALL be selected from ValueSet Plan of Care moodCode (Act/Encounter/Procedure)');
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
            'PlannedProcedure' => [
                'moodCode' => 'SHALL be selected from ValueSet Plan of Care moodCode (SubstanceAdministration/Supply)',
                'repeatNumber' => 'SHALL contain exactly one [0..1] repeatNumber',
                'quantity' => 'SHALL contain exactly one [0..1] quantity',
                'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime',
                LevelOther\authorParticipation::Structure(),
                LevelDocument\performer::Structure(),
                patientPriorityPreference::Structure(),
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
                'supply' => [
                    '@attributes' => [
                        'moodCode' => $PortionData['moodCode'],
                        'classCode' => 'OBS'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.43.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'statusCode' => Component::statusCode('completed')
                ]
            ];

            // SHOULD contain zero or one [0..1] repeatNumber
            if(isset($PortionData['repeatNumber']))
            {
                $Entry['supply']['repeatNumber'] =[
                    '@attributes' => [
                        'value' => $PortionData['repeatNumber']
                    ]
                ];
            }

            // SHOULD contain zero or one [0..1] quantity
            if(isset($PortionData['quantity']))
            {
                $Entry['supply']['quantity'] =[
                    '@attributes' => [
                        'value' => $PortionData['quantity']
                    ]
                ];
            }

            // SHOULD contain zero or one [0..1] effectiveTime
            if(isset($PortionData['effectiveTime']))
            {
                $Entry['supply']['effectiveTime'] = Component::time($PortionData['effectiveTime']);
            }

            // MAY contain zero or more [0..*] value
            // Participants represent those in supporting roles such as caregiver,
            // who participate in the patient's care.
            if(count($PortionData['Interval']) > 0)
            {
                foreach($PortionData['Interval'] as $Interval)
                {
                    $Entry['supply']['value'][] = [
                        '@attributes' => [
                            'xsi:type' => 'IVL_PQ'
                        ],
                        'low' => [
                            '@attributes' => [
                                'value' => $Interval['QuantityValue'],
                                'unit' => $Interval['QuantityUnit']
                            ]
                        ]
                    ];
                }
            }

            // MAY contain zero or more [0..*] performer
            if(count($PortionData['Performer']) > 0)
            {
                foreach($PortionData['Performer'] as $Performer)
                {
                    $Entry['supply']['performer'][] = LevelDocument\performer::Insert(
                        $Performer,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] participant
            if(count($PortionData['Participant']) > 0)
            {
                foreach($PortionData['Participant'] as $Participant)
                {
                    $Entry['supply']['participant'][] = LevelDocument\participant::Insert(
                        $Participant,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Service Delivery Location
            if(count($PortionData['ServiceDeliveryLocation']) > 0)
            {
                foreach($PortionData['ServiceDeliveryLocation'] as $ServiceDeliveryLocation)
                {
                    $Entry['supply']['participant'][] = serviceDeliveryLocation::Insert(
                        $ServiceDeliveryLocation,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
            if(count($PortionData['PatientPriorityPreference']) > 0)
            {
                foreach($PortionData['ProviderPriorityPreference'] as $ProviderPriorityPreference)
                {
                    $Entry['supply']['entryRelationship'][] = [
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

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
            if(count($PortionData['ProviderPriorityPreference']) > 0)
            {
                foreach($PortionData['ProviderPriorityPreference'] as $ProviderPriorityPreference)
                {
                    $Entry['supply']['entryRelationship'][] = [
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
