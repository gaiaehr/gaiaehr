<?php

/**
 * 3.92	Referenced Frames Observation
 *
 * A Referenced Frames Observation is used if the referenced DICOM SOP instance is a multiframe image and the
 * reference does not apply to all frames. The list of integer values for the referenced frames of a DICOM
 * multiframe image SOP instance is contained in a Boundary Observation nested inside this class.
 *
 * Contains:
 * Boundary Observation
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class referencedFramesObservation
 * @package LevelEntry
 */
class referencedFramesObservation
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
            'ReferencedFramesObservation' => [
                boundaryObservation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.10'),
                    'code' => [
                        '@attributes' => [
                            'code' => '121190',
                            'codeSystem' => '1.2.840.10008.2.16.4',
                            'displayName' => 'Referenced Frames'
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] entryRelationship
            // This entryRelationship SHALL contain exactly one [1..1] Boundary Observation
            if(count($PortionData['BoundaryObservation']) > 0)
            {
                foreach($PortionData['BoundaryObservation'] as $BoundaryObservation)
                {
                    $Entry['observation']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        boundaryObservation::Insert(
                            $BoundaryObservation,
                            $CompleteData
                        )
                    ];
                }
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
