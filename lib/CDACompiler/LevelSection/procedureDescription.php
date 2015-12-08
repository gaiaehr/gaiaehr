<?php

/**
 * 2.62	Procedure Description Section
 *
 * The Procedure Description section records the particulars of the procedure and may include procedure
 * site preparation, surgical site preparation, pertinent details related to sedation/anesthesia,
 * pertinent details related to measurements and markings, procedure times, medications administered,
 * estimated blood loss, specimens removed, implants, instrumentation, sponge counts, tissue manipulation,
 * wound closure, sutures used, vital signs and other monitoring data. Local practice often identifies the
 * level and type of detail required based on the procedure or specialty.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class procedureDescription
{

    /**
     * @param $PortionData
     */
    private static function Validate($PortionData)
    {

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
            'ProcedureDescription' => [

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
                                'root' => '2.16.840.1.113883.10.20.22.2.27',
                                'extension' => $PortionData['ProcedureDescription']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '29554-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Description'
                            ]
                        ],
                        'title' => 'Procedure Description',
                        'text' => self::Narrative($PortionData['ProcedureDescription'])
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
