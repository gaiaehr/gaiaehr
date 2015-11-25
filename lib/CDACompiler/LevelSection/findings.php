<?php

/**
 * 2.18	Findings Section (DIR)
 *
 * The Findings section contains the main narrative body of the report. While not an absolute requirement f
 * or transformed DICOM SR reports, it is suggested that Diagnostic Imaging Reports authored in CDA follow
 * Term Info guidelines  for the codes in the various observations and procedures recorded in this section.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class findings
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
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try
        {
            // Validate first
            self::Validate($Data['Findings']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.6.1.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '121070',
                                'displayName' => 'Findings',
                                'codeSystem' => '1.2.840.10008.2.16.4',
                                'codeSystemName' => 'DCM'
                            ]
                        ],
                        'title' => 'Findings',
                        'text' => self::Narrative($Data['Findings'])
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
