<?php

/**
 * 3.95	Self-Care Activities (ADL and IADL) (NEW)
 *
 * This template represents an adult patient's daily self-care ability. These activities are called Activities
 * of Daily Living (ADL) and Instrumental Activities of Daily Living (IADL).  ADLs involve caring for and moving
 * of the body (e.g. dressing, bathing, eating). IADLs support an independent life style
 * (e.g. cooking, managing medications, driving, shopping).
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class selfCareActivitiesADLAndIADL
 * @package LevelEntry
 */
class selfCareActivitiesADLAndIADL
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type');
        if(!isset($PortionData['abilityCode']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type');
        if(!isset($PortionData['abilityCodeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type');
        if(!isset($PortionData['abilityDisplayName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type');
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
            'SelfCareActivitiesADLAndIADL' => [
                'code' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type',
                'displayName' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet ADL Result Type',
                'effectiveTime' => '??',
                'abilityCode' => 'SHALL be selected from ValueSet Ability Value Set',
                'abilityCodeSystemName' => 'SHALL be selected from ValueSet Ability Value Set',
                'abilityDisplayName' => 'SHALL be selected from ValueSet Ability Value Set'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.128'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['abilityCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['abilityCodeSystemName']),
                            'codeSystemName' => $PortionData['abilityCodeSystemName'],
                            'displayName' => $PortionData['abilityDisplayName']
                        ]
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
