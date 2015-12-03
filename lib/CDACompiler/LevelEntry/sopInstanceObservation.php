<?php

/**
 * 3.101	SOP Instance Observation
 *
 * A SOP Instance Observation contains the DICOM Service Object Pair (SOP) Instance information for referenced
 * DICOM composite objects. The SOP Instance act class is used to reference both image and non-image DICOM instances.
 * The text attribute contains the DICOM WADO reference.
 *
 * Contains:
 * Purpose of Reference Observation
 * Referenced Frames Observation
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
class sopInstanceObservation
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

    public static function Structure(){
        return [
            'SopInstanceObservation' => [
                'wadoURI' => 'SHALL contain a @value that contains a WADO reference as a URI (CONF:9249).',
                'effectiveTime' => 'The effectiveTime, if present, SHALL contain exactly one [1..1] @value (CONF:9251).'
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
                        'classCode' => 'DGIMG',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.8'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => '1.2.840.10008.5.1.4.1.1.1',
                            'codeSystem' => '1.2.840.10008.2.6.1',
                            'codeSystemName' => 'DCMUID',
                            'displayName' => 'Computed Radiography Image Storage'
                        ]
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => 'completed'
                        ]
                    ],
                    'text' => [
                        '@attributes' => [
                            'mediaType' => 'application/dicom'
                        ],
                        'reference' => [
                            '@attributes' => [
                                'value' => $PortionData['wadoURI']
                            ]
                        ]
                    ],
                    'effectiveTime' => $PortionData['effectiveTime']
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
