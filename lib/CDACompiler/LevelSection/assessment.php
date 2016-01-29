<?php

/**
 * 2.7	Assessment Section
 *
 * The Assessment section (also referred to as “impression” or “diagnoses” outside of the context of CDA)
 * represents the clinician's conclusions and working assumptions that will guide treatment of the patient.
 * The assessment may be a list of specific disease entities or a narrative block.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class assessment
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHALL contain exactly one [1..1] text');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
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
            'Assessment' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text'
            ]
        ];
    }

    /**
     * @param $CompleteData
     * @return array|Exception
     */
    public static function Insert($PortionData, $CompleteData)
    {
        try
        {
            // Validate first
            self::Validate($PortionData['Assessment']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.8'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '51848-0',
                                'displayName' => 'Assessments',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Assessments',
                        'text' => self::Narrative($PortionData)
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
