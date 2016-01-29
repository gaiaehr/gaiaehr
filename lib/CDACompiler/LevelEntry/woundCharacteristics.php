<?php

/**
 * 3.109	Wound Characteristics (NEW)
 *
 * This template represents characteristics of a wound (e.g. integrity of suture line, odor, erythema)
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
 * Class woundCharacteristics
 * @package LevelEntry
 */
class woundCharacteristics {

    /**
     * @param $Data
     * @throws Exception
     */
    public static function Validate($Data)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');
        if(!isset($PortionData['code']))
            throw new Exception('SHALL be selected from ValueSet Wound Charactersitic');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL be selected from ValueSet Wound Charactersitic');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL be selected from ValueSet Wound Charactersitic');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     * @throws Exception
     */
    public static function Narrative($PortionData)
    {
    }

    public static function Structure(){
        return [
            'WoundCharacteristics' => [
                'effectiveTime' => 'MAY contain zero or one [0..1] effectiveTime',
                'code' => 'SHALL be selected from ValueSet Wound Charactersitic',
                'codeSystemName' => 'SHALL be selected from ValueSet Wound Charactersitic',
                'displayName' => 'SHALL be selected from ValueSet Wound Charactersitic'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|\Exception|Exception
     */
    public static function insert($PortionData, $CompleteData)
    {
        try{
            // Validate first
            self::Validate($PortionData);

            // Compose the segment
            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.134'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
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

            return $Entry;
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }

}
