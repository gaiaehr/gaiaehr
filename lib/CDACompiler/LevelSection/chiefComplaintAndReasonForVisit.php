<?php

/**
 * 2.8	Chief Complaint and Reason for Visit Section
 *
 * This section records the patient's chief complaint (the patient’s own description) and/or the reason for the
 * patient's visit (the provider’s description of the reason for visit).  Local policy determines whether the
 * information is divided into two sections or recorded in one section serving both purposes.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class chiefComplaintAndReasonForVisit
{

    /**
     * @param $Data
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
                                'root' => '2.16.840.1.113883.10.20.22.2.13',
                                'extension' => $PortionData['AdvanceDirectives']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '46239-0',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'CHIEF COMPLAINT AND REASON FOR VISIT'
                            ]
                        ],
                        'title' => 'Chief Complaint And Reason For Visit',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
