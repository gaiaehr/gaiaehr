<?php

/**
 * 3.102 Study Act
 *
 * A Study Act contains the DICOM study information that defines the characteristics of a referenced medical
 * study performed on a patient. A study is a collection of one or more series of medical images, presentation
 * states, SR documents, overlays, and/or curves that are logically related for the purpose of diagnosing a patient.
 * Each study is associated with exactly one patient. A study may include composite instances that are
 * created by a single modality, multiple modalities, or by multiple devices of the same modality.
 * The study information is modality-independent. Study Act clinical statements are only instantiated in the
 * DICOM Object Catalog section; in other sections, the SOP Instance Observation is included directly.
 *
 * Contains:
 * Series Act
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class studyAct
 * @package LevelEntry
 */
class studyAct
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
                seriesAct::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.6'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '113014',
                            'codeSystem' => '1.2.840.10008.2.16.4',
                            'codeSystemName' => 'DCM',
                            'displayName' => 'Study'
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Series Act
            if(count($PortionData['SeriesAct']) > 0)
            {
                foreach($PortionData['SeriesAct'] as $SeriesAct)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        sopInstanceObservation::Insert(
                            $SeriesAct,
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
