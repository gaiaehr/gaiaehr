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
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'ReasonForReferral' => [

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
                        'text' => self::Narrative($PortionData['ReasonForReferral'])
                    ]
                ]
            ];

            // Patient Referral Act (NEW)
            foreach($PortionData['ReasonForReferral']['Observations'] as $Observation) {
                $Section['component']['section']['entry'][] = [
                    'organizer' => LevelEntry\patientReferralAct::Insert($Observation, $CompleteData)
                ];
            }


            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
