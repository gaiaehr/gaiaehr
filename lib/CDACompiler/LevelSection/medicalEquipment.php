<?php

/**
 * 2.42	Medical Equipment Section (V2)
 *
 * This section defines a patient's implanted and external health and medical devices and equipment.
 * This section lists any pertinent durable medical equipment (DME) used to help maintain the patient’s health status.
 * All equipment relevant to the diagnosis, care, or treatment of a patient should be included.
 *
 * Any devices in or on a patient (where it is necessary to record the location of the device in or
 * on the patient's body) are represented using the Procedure Activity Procedure template whereas external
 * devices (such as pumps, inhalers, wheelchairs etc.) are represented by the Non-Medicinal Supply Activity template.
 *
 * Any of these devices may also be grouped together within a Medical Equipment Organizer.
 *
 * Contains:
 * Medical Equipment Organizer (NEW)
 * Non-Medicinal Supply Activity (V2)
 * Procedure Activity Procedure (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class medicalEquipment
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        // ...
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'MedicalEquipment' => [

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
                                'root' => '2.16.840.1.113883.10.20.22.2.23.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '46264-8',
                                'displayName' => 'Medical Equipment',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Medical Equipment',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // Medical Equipment Organizer (NEW)
            // ...
            // Non-Medicinal Supply Activity (V2)
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
