<?php

/**
 * 2.57	Postoperative Diagnosis Section
 *
 * The Postoperative Diagnosis section records the diagnosis or diagnoses discovered or confirmed during the surgery.
 * Often it is the same as the preoperative diagnosis.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class postoperativeDiagnosis
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['title']))
            throw new Exception('SHALL contain exactly one [1..1] title');
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     * @throws Exception
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'PostoperativeDiagnosis' => [
                'title' => 'SHALL contain exactly one [1..1] title',
                'Narrated' => 'SHALL contain exactly one [1..1] text'
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

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.2.35'),
                        'code' => [
                            '@attributes' => [
                                'code' => '10218-6',
                                'displayName' => 'POSTOPERATIVE DIAGNOSIS',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => $PortionData['title'],
                        'text' => self::Narrative($PortionData['Narrated'])
                    ]
                ]
            ];

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
