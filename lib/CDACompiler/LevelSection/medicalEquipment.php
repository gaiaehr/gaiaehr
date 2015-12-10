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
            'MedicalEquipment' => [
                'Narrated' => 'SHALL contain exactly one [1..1] text',
                LevelEntry\medicalEquipmentOrganizer::Structure(),
                LevelEntry\nonMedicinalSupplyActivity::Structure(),
                LevelEntry\procedureActivityProcedure::Structure()
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

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Medical Equipment Organizer (NEW)
            if(count($PortionData['MedicalEquipmentOrganizer']) > 0)
            {
                foreach ($PortionData['MedicalEquipmentOrganizer'] as $MedicalEquipmentOrganizer)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\medicalEquipmentOrganizer::Insert(
                        $MedicalEquipmentOrganizer,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Non-Medicinal Supply Activity (V2)
            if(count($PortionData['NonMedicinalSupplyActivity']) > 0)
            {
                foreach ($PortionData['NonMedicinalSupplyActivity'] as $NonMedicinalSupplyActivity)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\nonMedicinalSupplyActivity::Insert(
                        $NonMedicinalSupplyActivity,
                        $CompleteData
                    );
                }
            }

            // SHOULD contain zero or more [0..*] entry
            // SHALL contain exactly one [1..1] Procedure Activity Procedure (V2)
            if(count($PortionData['ProcedureActivityProcedure']) > 0)
            {
                foreach ($PortionData['ProcedureActivityProcedure'] as $ProcedureActivityProcedure)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\procedureActivityProcedure::Insert(
                        $ProcedureActivityProcedure,
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
