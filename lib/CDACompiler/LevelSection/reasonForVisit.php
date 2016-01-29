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
            'ReasonForVisit' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return array|Exception
     */
    public static function Insert($PortionData)
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
