<?php

/**
 * 2.6	Assessment and Plan Section (V2)
 *
 * This section represents the clinician’s conclusions and working assumptions that will guide treatment
 * of the patient. The Assessment and Plan sections may be combined or separated to meet local policy requirements.
 *
 * Contains:
 * Planned Act (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class assessmentAndPlan
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
            'AssessmentAndPlan' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\plannedAct::Structure()
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
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.9.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '51847-2',
                                'displayName' => 'Assessment And Plan',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Assessment And Plan',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Planned Act (V2)
            if(count($PortionData['PlannedAct']) > 0)
            {
                foreach ($PortionData['PlannedAct'] as $PlannedAct)
                {
                    $Section['component']['section']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'DRIV'
                        ],
                        LevelEntry\plannedAct::Insert(
                            $PlannedAct,
                            $CompleteData
                        )
                    ];
                }
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
