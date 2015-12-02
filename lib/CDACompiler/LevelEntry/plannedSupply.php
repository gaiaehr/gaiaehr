<?php

/**
 * 3.71	Planned Supply (V2)
 *
 * This template represents both medicinal and non-medicinal supplies ordered, requested or intended for the
 * patient. The importance of the supply order or request to the patient and provider may be indicated in the
 * Patient Priority Preference and Provider Priority Preference. The author/time indicates the time when
 * the planned supply was documented.
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
 * Class plannedSupply
 * @package LevelEntry
 */
class plannedSupply
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
            'supply' => [
                'moodCode' => 'SHALL be selected from ValueSet Plan of Care moodCode (SubstanceAdministration/Supply)',
                'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime',
                'PatientPriorityPreference' => patientPriorityPreference::Structure(),
                'ProviderPriorityPreference' => providerPriorityPreference::Structure(),
                'Author' => LevelOther\authorParticipation::Structure()
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
                        'classCode' => 'SPLY'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.41.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'displayName' => $PortionData['displayName']
                    ],
                    'statusCode' => Component::statusCode('active')
                ]
            ];

            // SHOULD contain zero or one [0..1] effectiveTime
            if(isset($PortionData['effectiveTime']))
            {
                $Entry['supply']['effectiveTime'] = Component::time($PortionData['effectiveTime']);
            }

            // SHOULD contain zero or more [0..1] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                $Entry['supply']['author'][] = LevelOther\authorParticipation::Insert(
                    $PortionData['Author'][0],
                    $CompleteData
                );
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
