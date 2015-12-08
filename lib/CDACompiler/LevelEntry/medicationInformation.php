<?php

/**
 * 3.53	Medication Information (V2)
 *
 * A medication should be recorded as a pre-coordinated ingredient + strength + dose form
 * (e.g., “metoprolol 25mg tablet”, “amoxicillin 400mg/5mL suspension”) where possible. This includes RxNorm codes
 * whose Term Type is SCD (semantic clinical drug), SBD (semantic brand drug), GPCK (generic pack), BPCK (brand pack).
 *
 * NOTE: The dose (doseQuantity) represents how many of the consumables are to be administered at each
 * administration event. As a result, the dose is always relative to the consumable. Thus, a patient consuming
 * a single "metoprolol 25mg tablet" per administration will have a doseQuantity of "1", whereas a patient
 * consuming "metoprolol" will have a dose of "25 mg".
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class advanceDirectiveOrganizer
 * @package LevelEntry
 */
class medicationInformation
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
            'MedicationInformation' => [
                'code' => '',
                'displayName' => '',
                'codeSystemName' => '',
                'manufacturer' => ''
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

            // Fill the structure
            $Entry = [
                'manufacturedProduct' => [
                    '@attributes' => [
                        'classCode' => 'MANU'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.23.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'manufacturedMaterial' => [
                        'code' => [
                            'code' => $PortionData['code'],
                            'displayName' => $PortionData['displayName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName']
                        ]
                    ],
                    'manufacturerOrganization' => [
                        'name' => [
                            'code' => $PortionData['manufacturer']
                        ]
                    ]
                ]
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }


}
