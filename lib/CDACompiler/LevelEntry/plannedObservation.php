<?php

/**
 * 3.68	Planned Observation (V2)
 *
 * This template represents a Planned Observation. The importance of the the planned observation to the patient
 * and provider is communicated through Patient Priority Preference and Provider Priority Preference.
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
class plannedObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['moodCode']))
            throw new Exception('SHALL be selected from ValueSet Plan of Care moodCode (Act/Encounter/Procedure)');

        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');
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
            'PlannedObservation' => [
                'moodCode' => 'SHALL be selected from ValueSet Plan of Care moodCode (Act/Encounter/Procedure)',
                'code' => 'SHALL contain exactly one [1..1] code',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code',
                'displayName' => 'SHALL contain exactly one [1..1] code',
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                LevelDocument\performer::Structure(),
                LevelDocument\participant::Structure(),
                patientPriorityPreference::Structure(),
                providerPriorityPreference::Structure(),
                'Interval' => [
                    0 => [
                        'QuantityValue' => 'Quantity Range Value',
                        'QuantityUnit' => 'Quantity Range Unit'
                    ]
                ]
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
                        'moodCode' => $PortionData['moodCode'],
                        'classCode' => 'OBS'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.44.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'displayName' => $PortionData['displayName']
                    ],
                    'statusCode' => Component::statusCode('active'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
                ]
            ];

            // MAY contain zero or more [0..*] value
            // Participants represent those in supporting roles such as caregiver,
            // who participate in the patient's care.
            if(count($PortionData['Interval']) > 0)
            {
                foreach($PortionData['Interval'] as $Interval)
                {
                    $Entry['observation']['value'][] = [
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
                    $Entry['observation']['performer'][] = LevelDocument\performer::Insert(
                        $Performer,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] participant
            if(count($PortionData['Participant']) > 0)
            {
                foreach($PortionData['Participant'] as $Performer)
                {
                    $Entry['observation']['participant'][] = LevelDocument\participant::Insert(
                        $Performer,
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
                    $Entry['observation']['participant'][] = serviceDeliveryLocation::Insert(
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

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
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
