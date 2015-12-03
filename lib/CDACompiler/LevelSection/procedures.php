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
            'Procedures' => [

            ]
        ];
    }

    /**
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try
        {
            // Validate first
            self::Validate($Data);

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
                        'text' => self::Narrative($Data)
                    ]
                ]
            ];

            // Procedure Activity Act (V2)
            // ...
            // Procedure Activity Observation (V2)
            // ...
            // Procedure Activity Procedure (V2)
            // ...


            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
