<?php

/**
 * 3.27	Drug Vehicle
 *
 * This template represents the vehicle (e.g. saline, dextrose) for administering a medication.
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
 * Class drugVehicle
 * @package LevelEntry
 */
class drugVehicle
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']) &&
            !isset($PortionData['displayName']) &&
            !isset($PortionData['codeSystemName']))
            throw new Exception('This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)');

        if(!isset($PortionData['Narrated']))
            throw new Exception('This playingEntity/name MAY be used for the vehicle name in text, such as Normal Saline (CONF:10087)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'DrugVehicle' => [
                'code' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
                'displayName' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
                'codeSystemName' => 'This playingEntity SHALL contain exactly one [1..1] code (CONF:7493)',
                'Narrated' => 'This playingEntity/name MAY be used for the vehicle name in text, such as Normal Saline (CONF:10087)'
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
                'participantRole' => [
                    '@attributes' => [
                        'classCode' => 'MANU'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.24'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => '412307009',
                            'displayName' => 'drug vehicle',
                            'codeSystem' => '2.16.840.1.113883.6.96'
                        ]
                    ],
                    'playingEntity' => [
                        '@attributes' => [
                            'classCode' => 'MMAT'
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => $PortionData['code'],
                                'displayName' => $PortionData['displayName'],
                                'codeSystem' => Utilities::CodingSystemId( $PortionData['codeSystemName'] ),
                                'codeSystemName' => $PortionData['codeSystemName']
                            ]
                        ],
                        'name' => self::Narrative($PortionData['Narrated'])
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
