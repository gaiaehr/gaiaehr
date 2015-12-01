<?php

/**
 * 2.45	Medications Section (entries required) (V2)
 *
 * The Medications Section contains a patient's current medications and pertinent medication history.
 * At a minimum, the currently active medications are listed. An entire medication history is an option.
 * The section can describe a patient's prescription and dispense history and information about
 * intended drug monitoring.
 *
 * This section requires either an entry indicating the subject is not known to be on any medications or
 * entries summarizing the subject's medications.
 *
 * Contains:
 * Health Status Observation (V2)
 * Problem Concern Act (Condition) (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class medications
{
    /**
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
        // ...
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
            self::Validate($Data);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            0 => [
                                '@attributes' => [
                                    'root' => '2.16.840.1.113883.10.20.22.2.1.1.2'
                                ]
                            ],
                            1 => [
                                '@attributes' => [
                                    'root' => '2.16.840.1.113883.10.20.22.2.1.2'
                                ]
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10160-0',
                                'displayName' => 'History of Medication Use',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'History of Medication Use',
                        'text' => self::Narrative($Data['Medications'])
                    ]
                ]
            ];

            // Health Status Observation (V2)
            // ...
            // Problem Concern Act (Condition) (V2)
            // ...


            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
