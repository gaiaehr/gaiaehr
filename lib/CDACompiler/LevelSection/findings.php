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
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['Narrated']))
            throw new Exception('SHOULD contain only the direct observations in the report, with topics such as Reason for Study, History, and Impression placed in separate sections.  However, in cases where the source of report content provides a single block of text not separated into these sections, that text SHALL be placed in the Findings section');
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
            'Findings' => [
                'Narrated' => 'SHOULD contain only the direct observations in the report, with topics such as Reason for Study, History, and Impression placed in separate sections.  However, in cases where the source of report content provides a single block of text not separated into these sections, that text SHALL be placed in the Findings section'
            ]

        ];
    }

    /**
     * @param $PortionData
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
