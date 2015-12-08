<?php

/**
 * 3.54	Medication Supply Order (V2)
 *
 * This template records the intent to supply a patient with medications.
 *
 * Contains:
 * Immunization Medication Information (V2)
 * Instruction (V2)
 * Medication Information (V2)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class medicationSupplyOrder
 * @package LevelEntry
 */
class medicationSupplyOrder
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'MedicationSupplyOrder' => [
                'repeatNumber' => 'SHOULD contain zero or one [0..1] repeatNumber',
                'quantity' => 'SHOULD contain zero or one [0..1] quantity',
                'effectiveTime_low' => 'SHOULD contain zero or one [0..1] effectiveTime',
                immunizationMedicationInformation::Structure(),
                medicationInformation::Structure(),
                LevelOther\authorParticipation::Structure(),
                instruction::Structure()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
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
                'supply' => [
                    '@attributes' => [
                        'classCode' => 'SPLY',
                        'moodCode' => 'INT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.17.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'statusCode' => Component::statusCode('completed'),
                    'repeatNumber' => [
                        '@attributes' => [
                            'value' => $PortionData['repeatNumber']
                        ]
                    ],
                    'quantity' => [
                        '@attributes' => [
                            'value' => $PortionData['quantity']
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] author
            if(count($PortionData['Author']) > 0)
            {
                $Entry['supply']['author'] = LevelOther\authorParticipation::Insert(
                    $PortionData['Author'][0],
                    $CompleteData
                );
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['Instruction']) > 0){
                $Entry['supply']['entryRelationship'] = [
                    '@attributes' => [
                        'typeCode' => 'SUBJ',
                        'moodCode' => 'INT'
                    ],
                    'act'=> instruction::Insert(
                        $PortionData['Instruction'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or one [0..1] product
            // SHALL contain exactly one [1..1] Medication Information (V2)
            if(count($PortionData['ProductMedicationInformation']) > 0)
            {
                $Entry['supply']['product'][] = medicationInformation::Insert(
                    $PortionData['ProductMedicationInformation'][0],
                    $CompleteData
                );
            }

            // MAY contain zero or one [0..1] product
            // SHALL contain exactly one [1..1] Immunization Medication Information (V2)
            if(count($PortionData['ProductImmunizationMedicationInformation']) > 0)
            {
                $Entry['supply']['product'][] = immunizationMedicationInformation::Insert(
                    $PortionData['ProductImmunizationMedicationInformation'][0],
                    $CompleteData
                );
            }

            // SHOULD contain zero or one [0..1] effectiveTime
            // SHALL contain exactly one [1..1] high
            if(isset($PortionData['effectiveTime_low']))
            {
                $Entry['supply']['effectiveTime'] = [
                    '@attributes' => [
                        'xsi:type' => 'IVL_TS'
                    ],
                    'low' => [
                        '@attributes' => [
                            'value' => $PortionData['effectiveTime_low']
                        ]
                    ],
                    'high' => [
                        '@attributes' => [
                            'nullFlavor' => 'UNK'
                        ]
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
