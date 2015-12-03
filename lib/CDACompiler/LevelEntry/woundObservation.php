<?php
/**
 * Class Wound Observation
 * 3.112 - Wound Observation
 *
 * This template represents acquired or surgical wounds commonly found in the long term care population.
 * It is not intended to encompass all wound types.  The template includes the general type of wound
 * (e.g. pressure ulcers, surgical incisions, deep tissue injury wounds) and can include wound measurements
 * and wound characteristics.
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class WoundObservation
 * @package LevelEntry
 */
class woundObservation {

    /**
     * @param $Data
     */
    public function Validate($Data)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData){

    }

    public static function Structure(){
        return [
            'WoundObservation' => [

            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|\Exception|Exception
     */
    public function insert($PortionData, $CompleteData)
    {
        try{
            // Compose the segment
            $Entry = array(
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => [
                        '@attributes' => [
                            'root' => '2.16.840.1.113883.10.20.22.4.114'
                        ]
                    ],
                    'id' => [
                        '@attributes' => [
                            'root' => Utilities::UUIDv4()
                        ]
                    ],
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystemName' => 'ActCode',
                            'codeSystem' => Utilities::CodingSystemId('ActCode')
                        ]
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => 'completed'
                        ]
                    ],
                    'effectiveTime' => [
                        'low' => [
                            '@attributes' => [
                                'value' => '' // TODO: Here goes the date of the wound
                            ]
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => '425144005',
                            'codeSystem' => Utilities::CodingSystemId('ICD-10'), // TODO: Input the coding system to use
                            'codeSystemName' => 'ICD-10', // TODO: Input the coding system to use
                            'displayName' => 'Minor open wound' // TODO: Input the context of the Wound
                        ]
                    ],
                    'targetSiteCode' => [
                        '@attributes' => [
                            'code' => '182295001',
                            'codeSystem' => Utilities::CodingSystemId('SNOMED-CT'),
                            'codeSystemName' => 'SNOMED-CT',
                            'displayName' => 'anterior aspect of knee'
                        ]
                    ]
                ]
            );
            return $Entry;
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }
}
