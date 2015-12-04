<?php

/**
 * 3.88	Provider Priority Preference (NEW)
 *
 * This template represents a provider priority preference. Provider priority preferences are choices made by
 * care providers relative to options for care or treatment and the prioritization of concerns and problems
 *
 * Contains:
 * Author Participation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class providerPriorityPreference
 * @package LevelEntry
 */
class providerPriorityPreference
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['priorityLevelCode']))
            throw new Exception('SHALL be selected from ValueSet Priority Level');
        if(!isset($PortionData['priorityLevelCodeSystemName']))
            throw new Exception('SHALL be selected from ValueSet Priority Level');
        if(!isset($PortionData['priorityLevelDisplayName']) < 1)
            throw new Exception('SHALL be selected from ValueSet Priority Level');
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
            'ProviderPriorityPreference' => [
                'priorityOrderCode' => 'SHALL be selected from ValueSet Goal Achievement',
                'priorityOrderCodeSystemName' => 'SHALL be selected from ValueSet Goal Achievement',
                'priorityOrderDisplayName' => 'SHALL be selected from ValueSet Goal Achievement',
                'priorityLevelCode' => 'SHALL be selected from ValueSet Priority Level',
                'priorityLevelCodeSystemName' => 'SHALL be selected from ValueSet Priority Level',
                'priorityLevelDisplayName' => 'SHALL be selected from ValueSet Priority Level',
                LevelOther\authorParticipation::Structure()
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
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.143'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '103323008',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'codeSystemName' => 'ActReason',
                            'displayname' => 'Provider preference'
                        ]
                    ],
                    'priorityCode' => [
                        '@attributes' => [
                            'code' => $PortionData['priorityOrderCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['priorityOrderCodeSystemName']),
                            'codeSystemName' => $PortionData['priorityOrderCodeSystemName'],
                            'displayName' => $PortionData['priorityOrderDisplayName']
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['priorityLevelCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['priorityLevelCodeSystemName']),
                            'displayName' => $PortionData['priorityLevelDisplayName'],
                            'codeSystemName' => $PortionData['priorityLevelCodeSystemName']
                        ]
                    ]
                ]
            ];

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

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
