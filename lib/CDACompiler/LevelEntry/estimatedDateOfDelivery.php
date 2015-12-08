<?php

/**
 * 3.30	Estimated Date of Delivery
 *
 * This clinical statement represents the anticipated date when a woman will give birth.
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
 * Class encounterDiagnosis
 * @package LevelEntry
 */
class estimatedDateOfDelivery
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['anticipatedDate']))
            throw new Exception('SHALL contain exactly one [1..1] value with @xsi:type="TS" (CONF:450)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'EstimatedDateOfDelivery' => [
                'anticipatedDate' => 'SHALL contain exactly one [1..1] value with @xsi:type="TS"'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.15.3.1'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '11778-8',
                            'displayName' => 'Estimated date of delivery',
                            'codeSystem' => '2.16.840.1.113883.6.1',
                            'codeSystemName' => 'LOINC'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'TS'
                        ],
                        '@value' => $PortionData['anticipatedDate']

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
