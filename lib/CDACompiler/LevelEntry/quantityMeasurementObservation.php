<?php

/**
 * 3.90	Quantity Measurement Observation
 *
 * A Quantity Measurement Observation records quantity measurements based on image data such as linear, area,
 * volume, and numeric measurements. The codes in DIRQuantityMeasurementTypeCodes
 * (ValueSet: 2.16.840.1.113883.11.20.9.29) are from the qualifier hierarchy of SNOMED CT and are not valid for
 * observation/code according to the Term Info guidelines. These codes can be used for backwards compatibility,
 * but going forward, codes from the observable entity hierarchy will be requested and used.
 *
 * Contains:
 * SOP Instance Observation
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class advanceDirectiveOrganizer
 * @package LevelEntry
 */
class quantityMeasurementObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['wadoURI']))
            throw new Exception ('SHALL contain a @value that contains a WADO reference as a URI (CONF:9249)');

        if(!isset($PortionData['effectiveTime'])){
            throw new Exception ('SHOULD contain zero or one [0..1] effectiveTime (CONF:9250).');
        }
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData){

    }

    /**
     * @return array
     */
    public static function Structure(){
        return [
            'QuantityMeasurementObservation' => [
                'wadoURI' => 'SHALL contain a @value that contains a WADO reference as a URI (CONF:9249)',
                'effectiveTime' => 'The effectiveTime, if present, SHALL contain exactly one [1..1] @value (CONF:9251)',
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.14'),
                    'code' => [
                        '@attributes' => [
                            'code' => '439984002',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'codeSystemName' => 'SNM3',
                            'displayName' => 'Diameter of structure'
                        ],
                        'originalText' => self::Narrative($PortionData)
                    ],
                    '' => '',
                    'statusCode' => [
                        '@attributes' => [
                            'code' => 'completed'
                        ]
                    ],
                    'effectiveTime' => $PortionData['effectiveTime'],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'PQ',
                            'value' => $PortionData['value'],
                            'unit' => $PortionData['unit']
                        ]
                    ]
                ]
            ];

            // SHALL contain exactly one [1..1] SOP Instance Observation
            // (templateId:2.16.840.1.113883.10.20.6.2.8) (CONF:16083).
            if(count($PortionData['SOPInstanceObservation'] > 0))
                $Entry['observation']['entryRelationship'][] = sopInstanceObservation::Insert(
                    $PortionData['SOPInstanceObservation'],
                    $CompleteData
                );

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
