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
            'ProcedureDescription' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text'
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.2.27'),
                        'code' => [
                            '@attributes' => [
                                'code' => '29554-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Procedure Description'
                            ]
                        ],
                        'title' => 'Procedure Description',
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
