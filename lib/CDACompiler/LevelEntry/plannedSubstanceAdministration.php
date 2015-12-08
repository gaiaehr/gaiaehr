<?php

/**
 * 3.70	Planned Substance Administration (V2)
 *
 * The Planned Substance Administration describes substance administrations that will occur.
 * The priority of the  substance administration activity to the patient and provider is communicated through
 * Patient Priority Preference and Provider Priority Preference. The effective time indicates the time when
 * the substance is intended to be administered.
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
 * Class plannedSubstanceAdministration
 * @package LevelEntry
 */
class plannedSubstanceAdministration
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
            throw new Exception('6.	SHALL contain exactly one [1..1] effectiveTime');
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
            'PlannedSubstanceAdministration' => [
                'moodCode' => 'SHALL be selected from ValueSet Plan of Care moodCode (Act/Encounter/Procedure)',
                'code' => 'SHALL contain exactly one [1..1] code',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code',
                'displayName' => 'SHALL contain exactly one [1..1] code',
                'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime',
                'Narrated' => 'SubstanceAdministration / Supply',
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
                'substanceAdministration' => [
                    '@attributes' => [
                        'moodCode' => $PortionData['moodCode'],
                        'classCode' => 'SBADM'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.42.2'),
                    'text' => self::Narrative($PortionData),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'displayName' => $PortionData['displayName']
                    ],
                    'statusCode' => Component::statusCode('active'),
                    'effectiveTime' => $PortionData['effectiveTime']
                ]
            ];

            // MAY contain zero or more [0..*] performer
            if(count($PortionData['Performer']) > 0)
            {
                foreach($PortionData['Performer'] as $Performer)
                {
                    $Entry['procedure']['performer'][] = LevelDocument\performer::Insert(
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
                    $Entry['procedure']['participant'][] = LevelDocument\participant::Insert(
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
                    $Entry['procedure']['participant'][] = serviceDeliveryLocation::Insert(
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
                    $Entry['procedure']['entryRelationship'][] = [
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
                    $Entry['procedure']['entryRelationship'][] = [
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
