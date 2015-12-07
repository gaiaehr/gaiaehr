<?php

/**
 * 3.105	Text Observation
 *
 * DICOM Template 2000 specifies that Imaging Report Elements of Value Type Text are contained in sections.
 * The Imaging Report Elements are inferred from Basic Diagnostic Imaging Report Observations that consist of image
 * references and measurements (linear, area, volume, and numeric). Text DICOM Imaging Report Elements in this
 * context are mapped to CDA text observations that are section components and are related to the
 * SOP Instance Observations (templateId 2.16.840.1.113883.10.20.6.2.8) or
 * Quantity Measurement Observations (templateId 2.16.840.1.113883.10.20.6.2.14)
 * by the SPRT (Support) act relationship.
 *
 * A Text Observation is required if the findings in the section text are represented as inferred
 * from SOP Instance Observations.
 *
 * Contains:
 * Quantity Measurement Observation
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
 * Class textObservation
 * @package LevelEntry
 */
class textObservation
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
            'TextObservation' => [
                'onsetDate' => 'SHALL contain exactly one [1..1] effectiveTime',
                'resolutionDate' => 'SHALL contain exactly one [1..1] effectiveTime',
                LevelOther\authorParticipation::Structure(),
                reactionObservation::Structure(),
                severityObservation::Structure(),
                sopInstanceObservation::Structure(),
                quantityMeasurementObservation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.12'),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'ED'
                        ],
                        'reference' => [
                            '@attributes' => [
                                'value' => $PortionData['findingsTag']
                            ]
                        ]
                    ]
                ]
            ];

            // SHALL contain at least one [1..*] entryRelationship
            // b.	SHALL contain exactly one [1..1] SOP Instance Observation
            if(count($PortionData['SOPInstanceObservation']) > 0)
            {
                foreach ($PortionData['SOPInstanceObservation'] as $SOPInstanceObservation)
                {
                    $Entry['observation']['entryRelationship'][] = sopInstanceObservation::Insert(
                        $SOPInstanceObservation,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // b.	SHALL contain exactly one [1..1] Quantity Measurement Observation
            if(count($PortionData['QuantityMeasurementObservation']) > 0)
            {
                foreach ($PortionData['QuantityMeasurementObservation'] as $QuantityMeasurementObservation)
                {
                    $Entry['observation']['entryRelationship'][] = quantityMeasurementObservation::Insert(
                        $QuantityMeasurementObservation,
                        $CompleteData
                    );
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
