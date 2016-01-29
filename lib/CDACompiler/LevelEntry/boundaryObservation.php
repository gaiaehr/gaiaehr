<?php

/**
 * 3.12	Boundary Observation
 *
 * A Boundary Observation contains a list of integer values for the referenced frames of a DICOM multiframe
 * image SOP instance. It identifies the frame numbers within the referenced SOP instance to which the reference
 * applies. The CDA Boundary Observation numbers frames using the same convention as DICOM, with the first
 * frame in the referenced object being Frame 1. A Boundary Observation must be used if a referenced DICOM SOP
 * instance is a multiframe image and the reference does not apply to all frames.
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
 * Class advanceDirectiveOrganizer
 * @package LevelEntry
 */
class boundaryObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['Values']) < 0)
            throw new Exception ('SHALL contain at least one [1..*] value with @xsi:type="INT" (CONF:9285)');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    public static function Structure(){
        return [
            'BoundaryObservation' => [
                0 => [
                    'value' => 'SHALL contain at least one [1..*] value with @xsi:type="INT" (CONF:9285)'
                ]
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.11'),
                    'code' => [
                        '@attributes' => [
                            'code' => '113036',
                            'codeSystem' => '1.2.840.10008.2.16.4',
                            'displayName' => 'Frames for Display'
                        ]
                    ]
                ]
            ];

            // SHALL contain at least one [1..*] value with @xsi:type="INT"
            foreach ($PortionData['value'] as $Value)
            {
                $Entry['observation']['value'][] = [
                    '@attributes' => [
                        'xsi:type' => 'INT',
                        'value' => $Value['value']
                    ]
                ];
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
