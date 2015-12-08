<?php

/**
 * 3.44	Immunization Activity (V2)
 *
 * An Immunization Activity describes immunization substance administrations that have actually occurred or are
 * intended to occur. Immunization Activities in "INT" mood are reflections of immunizations a clinician intends a
 * patient to receive. Immunization Activities in "EVN" mood reflect immunizations actually received.
 *
 * An Immunization Activity is very similar to a Medication Activity with some key differentiators.
 * The drug code system is constrained to CVX codes. Administration timing is less complex. Patient refusal reasons
 * should be captured. All vaccines administered should be fully documented in the patient's permanent medical
 * record. Healthcare providers who administer vaccines covered by the National Childhood Vaccine Injury Act are
 * required to ensure that the permanent medical record of the recipient indicates:
 *
 * 1) Date of administration
 * 2) Vaccine manufacturer
 * 3) Vaccine lot number
 * 4) Name and title of the person who administered the vaccine and the address of the clinic or facility where
 *    the permanent record will reside
 * 5) Vaccine information statement (VIS)
 *    a. date printed on the VIS
 *    b. date VIS given to patient or parent/guardian.
 *
 * This information should be included in an Immunization Activity when available.
 * (reference: http://www.cdc.gov/vaccines/pubs/pinkbook/downloads/appendices/D/vacc_admin.pdf)
 *
 * Contains:
 * Author Participation (NEW)
 * Drug Vehicle
 * Immunization Medication Information (V2)
 * Immunization Refusal Reason
 * Indication (V2)
 * Instruction (V2)
 * Medication Dispense (V2)
 * Medication Supply Order (V2)
 * Precondition for Substance Administration (V2)
 * Reaction Observation (V2)
 * Substance Administered Act (NEW)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class immunizationActivity
 * @package LevelEntry
 */
class immunizationActivity
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:8834)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'ImmunizationActivity' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime (CONF:8834)',
                'routeCode' => 'MAY contain zero or one [0..1] routeCode, which SHALL be selected from ValueSet Medication Route FDA Value Set',
                'routeCodeSystemName' => 'MAY contain zero or one [0..1] routeCode, which SHALL be selected from ValueSet Medication Route FDA Value Set',
                'routeDisplayName' => 'MAY contain zero or one [0..1] routeCode, which SHALL be selected from ValueSet Medication Route FDA Value Set',
                'doseQuantityValue' => 'SHOULD contain zero or one [0..1] doseQuantity (CONF:8841)',
                'doseQuantityUnit' => 'SHOULD contain zero or one [0..1] doseQuantity (CONF:8841)',
                immunizationMedicationInformation::Structure(),
                LevelDocument\performer::Structure(),
                indication::Structure(),
                instruction::Structure(),
                medicationSupplyOrder::Structure(),
                medicationDispense::Structure(),
                immunizationRefusalReason::Structure(),
                immunizationRefusalReason::Structure(),
                substanceAdministeredAct::Structure()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
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
                'substanceAdministration' => [
                    '@attributes' => [
                        'classCode' => 'SBADM',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.52.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'routeCode' => [
                        '@attributes' => [
                            'code' => $PortionData['routeCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['routeCodeSystemName']),
                            'codeSystemName' => $PortionData['routeCodeSystemName'],
                            'displayName' => $PortionData['routeDisplayName']
                        ]
                    ],
                    'doseQuantity' => [
                        '@attributes' => [
                            'value' => $PortionData['doseQuantityValue'],
                            'unit' => $PortionData['doseQuantityUnit']
                        ]
                    ],
                    'consumable' => [
                        'manufacturedProduct' => immunizationMedicationInformation::Insert($PortionData, $CompleteData),
                    ],
                    'performer' => LevelDocument\performer::Insert($PortionData)
                ]
            ];

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Indication (V2)
            if(count($PortionData['Indication']) > 0)
            {
                foreach($PortionData['Indication'] as $Indication)
                {
                    $Entry['substanceAdministration']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        indication::Insert(
                            $Indication,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['Instruction']) > 0)
            {
                $Entry['substanceAdministration']['entryRelationship'][] = [
                    '@attributes' => [
                        'typeCode' => 'SUBJ'
                    ],
                    instruction::Insert(
                        $PortionData['Instruction'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or more [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Medication Supply Order (V2)
            if(count($PortionData['MedicationSupplyOrder']) > 0)
            {
                $Entry['substanceAdministration']['entryRelationship'][] = [
                    '@attributes' => [
                        'typeCode' => 'REFR'
                    ],
                    medicationSupplyOrder::Insert(
                        $PortionData['MedicationSupplyOrder'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or more [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Medication Dispense (V2)
            if(count($PortionData['MedicationDispense']) > 0)
            {
                $Entry['substanceAdministration']['supply'][] = [
                    '@attributes' => [
                        'typeCode' => 'REFR'
                    ],
                    medicationDispense::Insert(
                        $PortionData['MedicationDispense'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or more [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Reaction Observation (V2)
            if(count($PortionData['ReactionObservation']) > 0)
            {
                $Entry['substanceAdministration']['supply'][] = [
                    '@attributes' => [
                        'typeCode' => 'CAUS'
                    ],
                    reactionObservation::Insert(
                        $PortionData['ReactionObservation'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or more [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Immunization Refusal Reason
            if(count($PortionData['ImmunizationRefusalReason']) > 0)
            {
                $Entry['substanceAdministration']['supply'][] = [
                    '@attributes' => [
                        'typeCode' => 'RSON'
                    ],
                    immunizationRefusalReason::Insert(
                        $PortionData['ImmunizationRefusalReason'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Substance Administered Act (NEW)
            if(count($PortionData['SubstanceAdministeredAct']) > 0)
            {
                foreach($PortionData['SubstanceAdministeredAct'] as $SubstanceAdministeredAct)
                {
                    $Entry['substanceAdministration']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP',
                            'inversionInd' => 'true'
                        ],
                        substanceAdministeredAct::Insert(
                            $SubstanceAdministeredAct,
                            $CompleteData
                        )
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Substance Administered Act (NEW)
            if(count($PortionData['PreconditionForSubstanceAdministration']) > 0)
            {
                foreach($PortionData['PreconditionForSubstanceAdministration'] as $PreconditionForSubstanceAdministration)
                {
                    $Entry['substanceAdministration']['precondition'][] = [
                        '@attributes' => [
                            'typeCode' => 'PRCN'
                        ],
                        preconditionForSubstanceAdministration::Insert(
                            $PreconditionForSubstanceAdministration,
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

}
