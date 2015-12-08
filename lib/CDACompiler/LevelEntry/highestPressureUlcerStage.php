<?php

/**
 * 3.41	Highest Pressure Ulcer Stage
 *
 * This observation contains a description of the wound tissue of the most severe or highest staged pressure
 * ulcer observed on a patient.
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
class highestPressureUlcerStage
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] value (CONF:14733)');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] value (CONF:14733)');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] value (CONF:14733)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'HighestPressureUlcerStage' => [
                'code' => 'SHALL contain exactly one [1..1] value (CONF:14733)',
                'codeSystemName' => 'SHALL contain exactly one [1..1] value (CONF:14733)',
                'displayName' => 'SHALL contain exactly one [1..1] value (CONF:14733)'
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
                'observation ' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.77'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '420905001',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'displayName' => 'Highest Pressure Ulcer Stage'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
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
