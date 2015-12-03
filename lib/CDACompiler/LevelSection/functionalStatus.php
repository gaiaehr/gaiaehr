<?php

/**
 * 2.19	Functional Status Section (V2)
 *
 * The Functional Status Section contains observations and assessments of a patient's physical abilities.
 * A patient’s functional status may include information regarding the patient’s general function such as ambulation,
 * ability to perform Activities of Daily Living (ADLs) (e.g., bathing, dressing, feeding, grooming) or
 * Instrumental Activities of Daily Living (IADLs) (e.g., shopping, using a telephone, balancing a check book).
 * Problems that impact function (e.g., dyspnea, dysphagia) can be contained in the section.
 *
 * Contains:
 * Assessment Scale Observation
 * Caregiver Characteristics
 * Functional Status Observation (V2)
 * Functional Status Organizer (V2)
 * Non-Medicinal Supply Activity (V2)
 * Self-Care Activities (ADL and IADL) (NEW)
 * Sensory and Speech Status (NEW)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class functionalStatus
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
            'FunctionalStatus' => [

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
                                'root' => '2.16.840.1.113883.10.20.22.2.14.2'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '47420-5',
                                'displayName' => 'Functional Status',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Functional Status',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // Assessment Scale Observation
            // ...
            // Caregiver Characteristics
            // ...
            // Functional Status Observation (V2)
            // ...
            // Functional Status Organizer (V2)
            // ...
            // Non-Medicinal Supply Activity (V2)
            // ...
            // Self-Care Activities (ADL and IADL) (NEW)
            // ...
            // Sensory and Speech Status (NEW)
            // ...


            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
