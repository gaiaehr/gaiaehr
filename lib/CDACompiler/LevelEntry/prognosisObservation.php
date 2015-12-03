<?php

/**
 * 3.86	Prognosis Observation (NEW)
 *
 * This template represents the patient’s prognosis, which must be associated with a problem observation.
 * It may serve as an alert to scope intervention plans.
 *
 * The effectiveTime represents the clinically relevant time of the observation. The observation/value is
 * not constrained and can represent the expected life duration in PQ, an anticipated course of the
 * disease in text, or coded term.
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
 * Class prognosisObservation
 * @package LevelEntry
 */
class prognosisObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] value');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] value');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] value');
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
            'PrognosisObservation' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'code' => 'SHALL contain exactly one [1..1] value',
                'codeSystemName' => 'SHALL contain exactly one [1..1] value',
                'displayName' => 'SHALL contain exactly one [1..1] value'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.113'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => '170967006',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'displayName' => 'prognosis/outlook',
                        'codeSystemName' => 'SNOMED-CT'
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'displayName' => $PortionData['displayName'],
                            'codeSystemName' => $PortionData['codeSystemName']
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
