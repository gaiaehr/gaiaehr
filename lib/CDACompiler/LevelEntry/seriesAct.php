<?php

/**
 * 3.97	Series Act
 *
 * A Series Act contains the DICOM series information for referenced DICOM composite objects. The series
 * information defines the attributes that are used to group composite instances into distinct logical sets.
 * Each series is associated with exactly one study. Series Act clinical statements are only instantiated in
 * the DICOM Object Catalog section inside a Study Act, and thus do not require a separate templateId; in
 * other sections, the SOP Instance Observation is included directly.
 *
 * Contains:
 * SOP Instance Observation
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class seriesAct
 * @package LevelEntry
 */
class seriesAct
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
                sopInstanceObservation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.63'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '113015',
                            'codeSystem' => '1.2.840.10008.2.16.4',
                            'codeSystemName' => 'DCM',
                            'displayName' => 'Series'
                        ],
                        'qualifier' => [
                            'name' => [
                                '@attributes' => [
                                    'code' => '121139',
                                    'codeSystem' => '1.2.840.10008.2.16.4',
                                    'codeSystemName' => 'DCM',
                                    'displayName' => 'Modality'
                                ]
                            ],
                            'value' => [
                                '@attributes' => [
                                    'code' => 'CR',
                                    'codeSystem' => '1.2.840.10008.2.16.4',
                                    'codeSystemName' => 'DCM',
                                    'displayName' => 'Computed Radiography'
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] SOP Instance Observation
            if(count($PortionData['SOPInstanceObservation']) > 0)
            {
                foreach($PortionData['SOPInstanceObservation'] as $SOPInstanceObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        sopInstanceObservation::Insert(
                            $SOPInstanceObservation,
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
