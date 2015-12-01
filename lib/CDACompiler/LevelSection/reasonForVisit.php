<?php

/**
 * 2.72	Reason for Visit Section
 *
 * This section records the patient’s reason for the patient's visit (as documented by the provider).
 * Local policy determines whether Reason for Visit and Chief Complaint are in separate or combined sections.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class reasonForVisit
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
            self::Validate($Data['ReasonForVisit']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.12'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '29299-5',
                                'displayName' => 'Reason For Visit',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Reason For Visit',
                        'text' => self::Narrative($Data['ReasonForVisit'])
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
