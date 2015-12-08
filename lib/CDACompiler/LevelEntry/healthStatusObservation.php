<?php

/**
 * 3.40	Health Status Observation (V2)
 *
 * This template represents  information about the overall health status of the patient. To represent the impact of a
 * specific problem or concern related to the patient's expected health outcome use the Prognosis Observation
 * Template 2.16.840.1.113883.10.20.22.4.113.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class healthStatusObservation
 * @package LevelEntry
 */
class healthStatusObservation
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL be selected from ValueSet HealthStatus (V2)');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL be selected from ValueSet HealthStatus (V2)');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL be selected from ValueSet HealthStatus (V2)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'HealthStatusObservation' => [
                'code' => 'SHALL be selected from ValueSet HealthStatus (V2)',
                'codeSystemName' => 'SHALL be selected from ValueSet HealthStatus (V2)',
                'displayName' => 'SHALL be selected from ValueSet HealthStatus (V2)'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|Exception
     */
    public static function Insert($PortionData, $CompleteData)
    {
        try
        {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.5'),
                    'code' => [
                        '@attributes' => [
                            'code' => '11323-3',
                            'displayName' => 'handoff communication (procedure)',
                            'codeSystem' => '2.16.840.1.113883.6.1',
                            'codeSystemName' => 'LOINC',
                            'displayName' => 'Health status'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'text' => [
                        'reference' => [
                            '@attributes' => [
                                'value' => self::Narrative($PortionData)
                            ]
                        ]
                    ],
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
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
