<?php

/**
 * 2.70	Procedures Section (entries required) (V2)
 *
 * This section describes all interventional, surgical, diagnostic, or therapeutic procedures or treatments
 * pertinent to the patient historically at the time the document is generated. The section should include
 * notable procedures, but can contain all procedures for the period of time being summarized. The common notion
 * of "procedure" is broader than that specified by the HL7 Version 3 Reference Information Model (RIM),
 * therefore this section contains procedure templates represented with three RIM classes: Act. Observation,
 * and Procedure. Procedure act is for procedures that alter the physical condition of a patient (e.g., splenectomy).
 * Observation act is for procedures that result in new information about a patient but do not cause physical
 * alteration (e.g., EEG). Act is for all other types of procedures (e.g., dressing change).
 *
 * Contains:
 * Procedure Activity Act (V2)
 * Procedure Activity Observation (V2)
 * Procedure Activity Procedure (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class procedures
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
            'Procedures' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\patientReferralAct::Structure()
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
                                'root' => '2.16.840.1.113883.10.20.22.2.7.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '47519-4',
                                'displayName' => 'Procedures',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Procedures',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Patient Referral Act (NEW)
            if(count($PortionData['PatientReferralAct']) > 0) {
                foreach ($PortionData['PatientReferralAct'] as $PatientReferralAct) {
                    $Section['component']['section']['entry'][] = LevelEntry\patientReferralAct::Insert(
                        $PatientReferralAct,
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
