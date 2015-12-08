<?php

/**
 * 3.52	Medication Dispense (V2)
 *
 * This template records the act of supplying medications (i.e., dispensing).
 *
 * Contains:
 * Immunization Medication Information (V2)
 * Medication Information (V2)
 * Medication Supply Order (V2)
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
class medicationDispense
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        // SHALL contain exactly one [1..1] statusCode, which SHALL be selected from ValueSet Medication Fill Status
        if(!isset($PortionData['statusCode']))
            throw new Exception('SHALL contain exactly one [1..1] statusCode, which SHALL be selected from ValueSet Medication Fill Status');
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'MedicationDispense' => [
                'statusCode' => 'SHALL contain exactly one [1..1] statusCode, which SHALL be selected from ValueSet Medication Fill Status',
                'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime',
                'repeatNumber' => 'SHOULD contain zero or one [0..1] repeatNumber',
                'quantity' => 'SHOULD contain zero or one [0..1] quantity',
                medicationInformation::Structure(),
                immunizationMedicationInformation::Structure(),
                medicationSupplyOrderInformation::Structure()
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
                'supply' => [
                    '@attributes' => [
                        'classCode' => 'SPLY',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.18.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'statusCode' => [
                        '@attributes' => [
                            'code' => $PortionData['statusCode']
                        ]
                    ],
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'repeatNumber' => $PortionData['repeatNumber'],
                    'quantity' => [
                        '@attributes' => [
                            'value' => $PortionData['quantity']
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or one [0..1] quantity
            // SHALL contain exactly one [1..1] Medication Information (V2)
            if(count($PortionData['MedicationInformation']) > 0){
                $Entry['supply']['product'] = medicationInformation::Insert(
                    $PortionData['MedicationInformation'][0],
                    $CompleteData
                );
            }

            // SHOULD contain zero or one [0..1] quantity
            // SHALL contain exactly one [1..1] Immunization Medication Information (V2)
            if(count($PortionData['ImmunizationMedicationInformation']) > 0){
                $Entry['supply']['product'] = immunizationMedicationInformation::Insert(
                    $PortionData['ImmunizationMedicationInformation'][0],
                    $CompleteData
                );
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Medication Supply Order (V2)
            if(count($PortionData['MedicationSupplyOrder']) > 0)
            {
                foreach($PortionData['MedicationSupplyOrder'] as $MedicationSupplyOrder)
                {
                    $Entry['supply']['entryRelationship']['entry'][] = [
                        '@attributes' => [
                            'typeCode' => 'REFR'
                        ],
                        medicationSupplyOrder::Insert(
                            $MedicationSupplyOrder,
                            $CompleteData
                        )
                    ];
                }
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

    }

}
