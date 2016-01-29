<?php

/**
 * 3.85	Product Instance
 *
 * This clinical statement represents a particular device that was placed in a patient or used as part of a
 * procedure or other act. This provides a record of the identifier and other details about the given product
 * that was used. For example, it is important to have a record that indicates not just that a hip prostheses
 * was placed in a patient but that it was a particular hip prostheses number with a unique identifier.
 *
 * The FDA Amendments Act specifies the creation of a Unique Device Identification (UDI) System that requires
 * the label of devices to bear a unique identifier that will standardize device identification and identify
 * the device through distribution and use.
 *
 * The UDI should be sent in the participantRole/id.
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
 * Class productInstance
 * @package LevelEntry
 */
class productInstance
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['productCode']))
            throw new Exception('This playingDevice SHOULD contain zero or one [0..1] code');
        if(!isset($PortionData['productCodeSystemName']))
            throw new Exception('This playingDevice SHOULD contain zero or one [0..1] code');
        if(!isset($PortionData['productDisplayName']))
            throw new Exception('This playingDevice SHOULD contain zero or one [0..1] code');
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
            'ProductInstance' => [
                'productCode' => 'This playingDevice SHOULD contain zero or one [0..1] code',
                'productCodeSystemName' => 'This playingDevice SHOULD contain zero or one [0..1] code',
                'productDisplayName' => 'This playingDevice SHOULD contain zero or one [0..1] code'
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
                'participantRole' => [
                    '@attributes' => [
                        'classCode' => 'MANU'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.37'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'playingDevice' => [
                        'code' => [
                            'code' => $PortionData['productCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['productCodeSystemName']),
                            'displayName' => $PortionData['productDisplayName'],
                            'codeSystemName' => $PortionData['productCodeSystemName']
                        ]
                    ],
                    'scopingEntity' => [
                        'id' => Component::id(Utilities::UUIDv4())
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
