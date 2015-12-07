<?php

/**
 * 3.103	Substance Administered Act (NEW)
 *
 * This template, like the Medication Administered template in QRDA, is used where there is a need to group a
 * number of administrations into a larger act (e.g. to group all of the immunizations that are part of a series).
 * The relationship between this template and component substance administrations can include a sequenceNumber,
 * to indicate the component administration's ordering in the series.
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
 * Class substanceAdministeredAct
 * @package LevelEntry
 */
class substanceAdministeredAct
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {

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
            'SeriesAct' => [
                'effectiveTime' => 'MAY contain zero or one [0..1] effectiveTime'
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
                        'classCode' => 'ACT',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.118'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '416118004',
                            'codeSystem' => '2.16.840.1.113883.6.96'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed')
                ]
            ];

            // MAY contain zero or one [0..1] effectiveTime (CONF:31509)
            if(isset($PortionData['effectiveTime']))
            {
                $Entry['act']['effectiveTime'] = $PortionData['effectiveTime'];
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
