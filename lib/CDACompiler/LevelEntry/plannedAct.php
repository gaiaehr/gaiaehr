<?php

/**
 * 3.66	Planned Act (V2)
 *
 * This is the generic template for the Planned Act. The activities in this template represent procedures that
 * are not classified as an observation or a procedure according to the HL7 RIM. Examples of these procedures
 * are a dressing change, teaching or feeding a patient or providing comfort measures. The priority of the
 * activity to the patient and provider is communicated through Patient Priority Preference and
 * Provider Priority Preference. The effective time indicates the time when the activity is
 * intended to take place.
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
 * Class plannedAct
 * @package LevelEntry
 */
class plannedAct
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
            'PlannedAct' => [
                'moodCode' => 'SHALL be selected from ValueSet Plan of Care moodCode (Act/Encounter/Procedure)',
                'code' => 'SHALL contain exactly one [1..1] code',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code',
                'displayName' => 'SHALL contain exactly one [1..1] code',
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                LevelDocument\performer::Structure(),
                LevelDocument\participant::Structure(),
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
                'act' => [
                    '@attributes' => [
                        'moodCode' => $PortionData['moodCode'],
                        'classCode' => 'ACT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.39.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'displayName' => $PortionData['displayName']
                    ],
                    'statusCode' => Component::statusCode('new'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
                ]
            ];

            // MAY contain zero or more [0..*] performer
            if(count($PortionData['Performer']) > 0)
            {
                foreach($PortionData['Performer'] as $Performer)
                {
                    $Entry['act']['performer'][] = LevelDocument\performer::Insert(
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
                    $Entry['act']['participant'][] = LevelDocument\participant::Insert(
                        $Performer,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
            if(count($PortionData['PatientPriorityPreference']) > 0)
            {
                foreach($PortionData['PatientPriorityPreference'] as $PatientPriorityPreference)
                {
                    $Entry['act']['entryRelationship'][] = [
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

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Patient Priority Preference (NEW)
            if(count($PortionData['ProviderPriorityPreference']) > 0)
            {
                foreach($PortionData['ProviderPriorityPreference'] as $ProviderPriorityPreference)
                {
                    $Entry['act']['entryRelationship'][] = [
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
