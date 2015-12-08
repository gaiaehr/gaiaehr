<?php

/**
 * 3.5	Age Observation
 *
 * This Age Observation represents the subject's age at onset of an event or observation. The age of a relative
 * in a Family History Observation at the time of that observation could also be inferred by comparing
 * RelatedSubject/subject/birthTime with Observation/effectiveTime. However, a common scenario is that a patient
 * will know the age of a relative when the relative had a certain condition or when the relative died, but will
 * not know the actual year (e.g., "grandpa died of a heart attack at the age of 50"). Often times, neither precise
 * dates nor ages are known (e.g. "cousin died of congenital heart disease as an infant").
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
 * Class ageObservation
 * @package LevelEntry
 */
class ageObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['age']))
            throw new Exception('contain exactly one [1..1] age');
        if(!isset($PortionData['unit']))
            throw new Exception('contain exactly one [1..1] unit');
    }

    /**
     * @return array
     */
    private static function Structure()
    {
        $Structure = [
            'AgeObservation' => [
                'age' => '',
                'unit' => ''
            ]
        ];
        return $Structure;
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.31'),
                    'code' => [
                        'code' => '445518008',
                        'codeSystem' => Utilities::CodingSystemId('SNOMED-CT'),
                        'codeSystemName' => 'SNOMED-CT',
                        'displayName' => 'Age At Onset'
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => 'completed'
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'PQ',
                            'value' => $PortionData['age'],
                            'unit' => $PortionData['unit']
                        ]
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
