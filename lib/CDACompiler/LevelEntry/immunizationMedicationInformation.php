<?php

/**
 * 3.45	Immunization Medication Information (V2)
 *
 * The Immunization Medication Information represents product information about the immunization substance.
 * The vaccine manufacturer and vaccine lot number are typically recorded in the medical record and should be
 * included if known.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class immunizationMedicationInformation
 * @package LevelEntry
 */
class immunizationMedicationInformation
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from
            ValueSet CVX Vaccines Administered - Vaccine Set');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from
            ValueSet CVX Vaccines Administered - Vaccine Set');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from
            ValueSet CVX Vaccines Administered - Vaccine Set');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'ImmunizationMedicationInformation' => [
                'code' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet CVX Vaccines Administered - Vaccine Set',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet CVX Vaccines Administered - Vaccine Set',
                'displayName' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet CVX Vaccines Administered - Vaccine Set',
                'lotNumberText' => '',
                'manufacturerOrganization' => 'SHOULD contain zero or one [0..1] manufacturerOrganization (CONF:9012)'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
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
                'manufacturedProduct' => [
                    '@attributes' => [
                        'classCode' => 'MANU'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.54.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'manufacturedMaterial' => [
                        'code' => [
                            'code' => $PortionData['code'],
                            'displayName' => $PortionData['displayName'],
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName'])
                        ]
                    ],
                    'lotNumberText' => $PortionData['lotNumberText'],
                    'manufacturerOrganization' => [
                        'name' => $PortionData['manufacturerOrganization']
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
