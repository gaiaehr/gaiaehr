<?php

/**
 * 2.40	Interventions Section (V2)
 *
 * This template represents Interventions.  Interventions are actions taken to maximize the prospects of achieving
 * the patient’s or provider’s goals of care, including the removal of barriers to success. Interventions can be
 * planned, ordered, historical, etc.
 *
 * Interventions include actions that may be ongoing (e.g. maintenance medications that the patient is taking,
 * or monitoring the patient’s health status or the status of an intervention).
 *
 * Instructions are a subset of interventions and may include self-care instructions. Instructions are
 * information or directions to the patient and other providers including how to care for the individual’s
 * condition, what to do at home, when to call for help, any additional appointments, testing, and changes
 * to the medication list or medication instructions, clinical guidelines and a summary of best practice.

 *
 * Contains:
 * Intervention Act (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class interventions
{

    /**
     * @param $PortionData
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
            'Interventions' => [
                'Narrated' => '',
                LevelEntry\interventionAct::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.21.2.3.2',
                                'extension' => $PortionData['Interventions']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '62387-6',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Interventions Provided'
                            ]
                        ],
                        'title' => 'Interventions Provided',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Intervention Act (NEW)
            if(count($PortionData['InterventionAct']) > 0)
            {
                foreach ($PortionData['InterventionAct'] as $InterventionAct)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\interventionAct::Insert(
                        $InterventionAct,
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
