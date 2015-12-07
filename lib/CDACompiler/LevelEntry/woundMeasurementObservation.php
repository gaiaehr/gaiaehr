<?php

/**
 * 3.111	Wound Measurement Observation (NEW)
 *
 * This template represents the Wound Measurement Observations of wound width, depth and length.
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
 * Class woundMeasurementObservation
 * @package LevelEntry
 */
class woundMeasurementObservation {

    /**
     * @param $PortionData
     * @throws Exception
     */
    public static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code (ValueSet: Wound Measurements');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code (ValueSet: Wound Measurements');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code (ValueSet: Wound Measurements');
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');
        if(!isset($PortionData['value']))
            throw new Exception('SHALL contain exactly one [1..1] value / Value Set: Wound Measurements');
        if(!isset($PortionData['unit']))
            throw new Exception('SHALL contain exactly one [1..1] value / Value Set: Wound Measurements');
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
            'WoundMeasurementObservation' => [
                'code' => 'SHALL contain exactly one [1..1] code (ValueSet: Wound Measurements',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code (ValueSet: Wound Measurements',
                'displayName' => 'SHALL contain exactly one [1..1] code (ValueSet: Wound Measurements',
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'value' => 'SHALL contain exactly one [1..1] value / Value Set: Wound Measurements',
                'unit' => 'SHALL contain exactly one [1..1] value / Value Set: Wound Measurements'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|\Exception|Exception
     */
    public static function Insert($PortionData, $CompleteData)
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.133'),
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
                            'xsi:type' => 'PQ',
                            'value' => $PortionData['value'],
                            'unit' => $PortionData['unit']
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
