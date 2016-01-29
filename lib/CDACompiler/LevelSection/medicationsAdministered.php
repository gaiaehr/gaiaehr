<?php

/**
 * 2.43	Medications Administered Section (V2)
 *
 * The Medications Administered Section contains medications and fluids administered during a procedure.
 * The section may also contain the procedure's encounter or other activity, excluding anesthetic medications.
 * This section is not intended for ongoing medications and medication history.

 *
 * Contains:
 * Medication Activity (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class medicationsAdministered
{
    /***
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
            'MedicationsAdministered' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\medicationActivity::Structure()
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
                        'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.2.38.2'),
                        'code' => [
                            '@attributes' => [
                                'code' => '29549-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Medications Administered'
                            ]
                        ],
                        'title' => 'Medications Administered',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Medication Activity (V2)
            if(count($PortionData['MedicationActivity']) > 0)
            {
                foreach ($PortionData['MedicationActivity'] as $MedicationActivity)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\medicationActivity::Insert(
                        $MedicationActivity,
                        $CompleteData
                    );
                }
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
