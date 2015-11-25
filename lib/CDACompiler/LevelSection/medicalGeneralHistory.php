<?php

/**
 * 2.41	Medical (General) History Section
 *
 * The Medical History section describes all aspects of the medical history of the patient even if not pertinent
 * to the current procedure, and may include chief complaint, past medical history, social history, family history,
 * surgical or procedure history, medication history, and other history information. The history may be limited
 * to information pertinent to the current procedure or may be more comprehensive. The history may be reported
 * as a collection of random clinical statements or it may be reported categorically. Categorical report formats
 * may be divided into multiple subsections including Past Medical History, Social History.

 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class medicalGeneralHistory
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
                                'root' => '2.16.840.1.113883.10.20.22.2.39',
                                'extension' => $PortionData['Interventions']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11329-0',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Medical (General) History'
                            ]
                        ],
                        'title' => 'Medical (General) History',
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
