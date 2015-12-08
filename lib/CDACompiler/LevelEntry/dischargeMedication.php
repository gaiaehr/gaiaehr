<?php

/**
 * 3.25	Discharge Medication (V2)
 *
 * This template represents medications that the patient is intended to take (or stop) after discharge.
 *
 * Contains:
 * Medication Activity (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class dischargeMedication
 * @package LevelEntry
 */
class dischargeMedication
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['MedicationActivity']) < 1)
            throw new Exception('b.	SHALL contain exactly one [1..1] Medication Activity (V2) (templateId:2.16.840.1.113883.10.20.22.4.16.2) (CONF:15525)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'DischargeMedication' => [
                medicationActivity::Structure()
            ]

        ];
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
                'act' => [
                    '@attributes' => [
                        'classCode' => 'ACT',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.35.2'),
                    'code' => [
                        '@attributes' => [
                            'code' => '10183-2'
                        ]
                    ]
                ]
            ];

            // SHALL contain exactly one [1..1] Medication Activity (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.16.2) (CONF:15525).
            if (count($PortionData['MedicationActivity']) > 0)
            {
                foreach ($PortionData['MedicationActivity'] as $Activity)
                {
                    $Entry['act']['entryRelationship'][] = medicationActivity::Insert(
                        $Activity,
                        $CompleteData
                    );
                }
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
