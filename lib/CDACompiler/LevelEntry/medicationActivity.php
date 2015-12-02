<?php

/**
 * 3.51	Medication Activity (V2)
 *
 * A medication activity describes substance administrations that have actually occurred
 * (e.g. pills ingested or injections given) or are intended to occur
 * (e.g. "take 2 tablets twice a day for the next 10 days"). Medication activities in "INT" mood are reflections of
 * what a clinician intends a patient to be taking. For example, a clinician may intend for a  patient to be
 * administered Lisinopril  20 mg PO for blood pressure control.  However, what was actually administered was
 * Lisinopril 10 mg.  In the latter case, the Medication activities in the "EVN" mood would reflect actual use.
 *
 * At a minimum, a medication activity shall include an effectiveTime indicating the duration of the administration.
 * Ambulatory medication lists generally provide a summary of use for a given medication over time - a medication
 * activity in event mood with the duration reflecting when the medication started and stopped. Ongoing medications
 * will not have a stop date (or a stop date with a suitable NULL value). Ambulatory medication lists will
 * generally also have a frequency (e.g. a medication is being taken twice a day). Inpatient medications generally
 * record each administration as a separate act.
 *
 * The dose (doseQuantity) represents how many of the consumables are to be administered at each administration event.
 * As a result, the dose is always relative to the consumable and the interval of administration.
 * Thus, a patient consuming a single "metoprolol 25mg tablet" per administration will have a
 * doseQuantity of "1", whereas a patient consuming "metoprolol" will have a dose of "25 mg".
 *
 * Contains:
 * Author Participation (NEW)
 * Drug Monitoring Act (NEW)
 * Drug Vehicle
 * Indication (V2)
 * Instruction (V2)
 * Medication Dispense (V2)
 * Medication Information (V2)
 * Medication Supply Order (V2)
 * Precondition for Substance Administration (V2)
 * Reaction Observation (V2)
 * Substance Administered Act (NEW)
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
class medicationActivity
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        // This consumable SHALL contain exactly one [1..1] Medication Information (V2)
        // (templateId:2.16.840.1.113883.10.20.22.4.23.2) (CONF:16085).
        if(!isset($PortionData['medicationInformation']))
        {
            throw new Exception('This consumable SHALL contain exactly one [1..1] Medication Information (V2)');
        }
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'statusCode' =>
                'contain exactly one [1..1] statusCode, which SHALL be selected from ValueSet ActStatus (CONF:7507)',
            'effectiveTime_medication_frequency' =>
                'SHOULD contain zero or one [0..1] effectiveTime (CONF:7513) such that it Note: This effectiveTime
                represents the medication frequency (e.g. administration times per day)',
            'effectiveTime_medication_frequency_unit' =>
                'SHOULD contain zero or one [0..1] effectiveTime (CONF:7513) such that it
                Note: This effectiveTime represents the medication frequency (e.g. administration times per day)',
            'effectiveTime_medication_start' =>
                'SHALL contain exactly one [1..1] effectiveTime (CONF:7508) such that it
                Note: This effectiveTime represents the medication duration (i.e. the time the medication was started and stopped).',
            'effectiveTime_medication_end' =>
                'SHALL contain exactly one [1..1] effectiveTime (CONF:7508) such that it
                Note: This effectiveTime represents the medication duration (i.e. the time the medication was started and stopped).',
            'routeCode' => 'MAY contain zero or one [0..1] routeCode, which SHALL be selected from ValueSet
             Medication Route FDA Value Set',
            'routeCodeSystemName' => 'Route Code System Name',
            'routeDisplayName' => 'Route Display Name',
            'doseQuantity' => 'SHOULD contain zero or one [0..1] doseQuantity (CONF:7516).',
            'medicationInformation' => medicationInformation::Structure()
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
                'substanceAdministration' => [
                    '@attributes' => [
                        'classCode' => 'SBADM',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.16.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'statusCode' => [
                        '@attributes' => [
                            'code' => $PortionData['statusCode']
                        ]
                    ],
                    'effectiveTime' => [
                        0 => Component::time(
                            $PortionData['effectiveTime_medication_start'],
                            $PortionData['effectiveTime_medication_end']
                        ),
                        1 => [
                            '@attributes' => [
                                'xsi:type' => 'PIVL_TS',
                                'institutionSpecified' => 'true',
                                'operator' =>  'A'
                            ],
                            'period' => [
                                '@attributes' => [
                                    // Note: This effectiveTime represents the medication frequency
                                    // (e.g. administration times per day).
                                    'value' => $PortionData['effectiveTime_medication_frequency'],
                                    'unit' => $PortionData['effectiveTime_medication_frequency_unit']
                                ]
                            ]
                        ]
                    ],
                    'routeCode' => [
                        '@attributes' => [
                            'code' => $PortionData['routeCode'],
                            'codeSystem' => Utilities::CodingSystemId( $PortionData['routeCodeSystemName'] ),
                            'codeSystemName' => $PortionData['routeCodeSystemName'],
                            'displayName' => $PortionData['routeDisplayName']
                        ]
                    ],
                    'doseQuantity' => [
                        '@attributes' => [
                            'value' => $PortionData['doseQuantity']
                        ]
                    ],
                    'consumable' => medicationInformation::Insert($PortionData['medicationInformation'], $CompleteData)
                ]
            ];

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
