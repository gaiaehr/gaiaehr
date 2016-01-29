<?php

/**
 * 2.71	Reason for Referral Section (V2)
 *
 * This section contains the reason(s) for a patient’s referral by a provider to a consulting provider. An optional
 * Chief Complaint section may capture the patient’s description of the reason for the consultation.
 *
 * Contains:
 * Patient Referral Act (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class reasonForReferral
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
            'ReasonForReferral' => [
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
                                'root' => '1.3.6.1.4.1.19376.1.5.3.1.3.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '42349-1',
                                'displayName' => 'Reason for Referral',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Reason for Referral',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // MAY contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Patient Referral Act (NEW)
            if(count($PortionData['ReasonForReferral']['Observations'])>1) {
                foreach ($PortionData['ReasonForReferral']['Observations'] as $Observation) {
                    $Section['component']['section']['entry'][] = LevelEntry\patientReferralAct::Insert(
                        $Observation,
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
