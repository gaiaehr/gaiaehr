<?php

/**
 * 3.50	Medical Equipment Organizer (NEW)
 *
 * This clinical statement represents a set of current or historical medical devices/equipment in use or ordered.
 * It may contain information applicable to all of the contained devices/equipment over time.
 *
 * Any devices in or on a patient (where it is necessary to record the location of the device in or on the
 * patient's body) are represented using the Procedure Activity Procedure template whereas external devices
 * (such as pumps, inhalers, wheelchairs etc.) are represented by the Non-Medicinal Supply Activity template.
 *
 * Contains:
 * Non-Medicinal Supply Activity (V2)
 * Procedure Activity Procedure (V2)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class medicalEquipmentOrganizer
 * @package LevelEntry
 */
class medicalEquipmentOrganizer
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHOULD contain zero or one [1..1] @code (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96)');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHOULD contain zero or one [1..1] @code (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96)');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHOULD contain zero or one [1..1] @code (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'MedicalEquipmentOrganizer' => [
                'code' => 'This code SHOULD contain zero or one [0..1] @code (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96)',
                'codeSystemName' => 'This code SHOULD contain zero or one [0..1] @code (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96)',
                'displayName' => 'This code SHOULD contain zero or one [0..1] @code (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96)',
                nonMedicinalSupplyActivity::Structure(),
                procedureActivityProcedure::Structure()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {

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

            $Entry = [
                'organizer' => [
                    '@attributes' => [
                        'classCode' => 'CLUSTER',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.135'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('active')
                ]
            ];

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Non-Medicinal Supply Activity (V2)
            if(count($PortionData['NonMedicinalSupplyActivity']) > 0)
            {
                foreach($PortionData['NonMedicinalSupplyActivity'] as $NonMedicinalSupplyActivity)
                {
                    $Entry['organizer']['component'][] = nonMedicinalSupplyActivity::Insert(
                        $NonMedicinalSupplyActivity,
                        $CompleteData
                    );
                }
            }

            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Procedure Activity Procedure (V2)
            if(count($PortionData['ProcedureActivityProcedure']) > 0)
            {
                foreach($PortionData['ProcedureActivityProcedure'] as $ProcedureActivityProcedure)
                {
                    $Entry['organizer']['component'][] = procedureActivityProcedure::Insert(
                        $ProcedureActivityProcedure,
                        $CompleteData
                    );
                }
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
