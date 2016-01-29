<?php

/**
 * 2.21	Goals Section (NEW)
 *
 * This template represents patient Goals.  A goal is a defined outcome or condition to be achieved in the process
 * of patient care. Goals include patient-defined goals (e.g., alleviation of health concerns, positive outcomes
 * from interventions, longevity, function, symptom management, comfort) and clinician-specific goals to achieve
 * desired and agreed upon outcomes.
 *
 * Contains:
 * Goal Observation (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class goal
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
            'Goal' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\goalObservation::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.60'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '61146-7',
                                'displayName' => 'Goals',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Goals',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Goal Observation (NEW)
            if(count($PortionData['GoalObservation']) > 0)
            {
                foreach ($PortionData['GoalObservation'] as $GoalObservation)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\goalObservation::Insert(
                        $GoalObservation,
                        $CompleteData
                    );
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
