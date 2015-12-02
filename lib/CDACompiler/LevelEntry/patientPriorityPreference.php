<?php

/**
 * 3.63	Patient Priority Preference (NEW)
 *
 * This template represents patient preferences. Preferences are choices made by patients, independently or
 * together with their caregivers (e.g., family) relative to options for care or treatment (including scheduling,
 * care experience, and meeting of personal health goals) and the sharing and disclosure of health information.
 * This template does not represent guardianship. The patient’s guardian is represented in the CDA header
 * with recordTarget/PatientRole/Patient/Guardian.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class patientPriorityPreference
 * @package LevelEntry
 */
class patientPriorityPreference
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['priorityLevelCode']))
            throw new Exception('SHALL be selected from ValueSet Priority Level');

        if(!isset($PortionData['priorityLevelDisplayName']))
            throw new Exception('SHALL be selected from ValueSet Priority Level');

        if(!isset($PortionData['priorityLevelCodeSystemName']))
            throw new Exception('SHALL be selected from ValueSet Priority Level');
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
            'observation' => [
                'priorityOrderCode' => 'SHOULD be selected from ValueSet Priority Order',
                'priorityOrderDisplayName' => 'SHOULD be selected from ValueSet Priority Order',
                'priorityOrderCodeSystemName' => 'SHOULD be selected from ValueSet Priority Order',
                'priorityLevelCode' => 'SHALL be selected from ValueSet Priority Level',
                'priorityLevelDisplayName' => 'SHALL be selected from ValueSet Priority Level',
                'priorityLevelCodeSystemName' => 'SHALL be selected from ValueSet Priority Level'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.142'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => 'PAT',
                        'codeSystem' => '2.16.840.1.113883.5.8',
                        'codeSystemName' => 'ActReason',
                        'displayName' => 'Patient request'
                    ],
                    'priorityCode' => [
                        'code' => $PortionData['priorityOrderCode'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['priorityOrderCodeSystemName']),
                        'codeSystemName' => $PortionData['priorityOrderCodeSystemName'],
                        'displayName' => $PortionData['priorityOrderDisplayName']
                    ],
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['priorityLevelCode'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['priorityLevelCodeSystemName']),
                        'codeSystemName' => $PortionData['priorityLevelCodeSystemName'],
                        'displayName' => $PortionData['priorityLevelDisplayName']
                    ]
                ]
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
