<?php

/**
 * 3.15	Code Observations
 *
 * DICOM Template 2000 specifies that Imaging Report Elements of Value Type Code are contained in sections.
 * The Imaging Report Elements are inferred from Basic Diagnostic Imaging Report Observations that consist of
 * image references and measurements (linear, area, volume, and numeric). Coded DICOM Imaging Report Elements
 * in this context are mapped to CDA-coded observations that are section components and are related to the SOP
 * Instance Observations (templateId 2.16.840.1.113883.10.20.6.2.8) or Quantity Measurement Observations
 * (templateId 2.16.840.1.113883.10.20.6.2.14) by the SPRT (Support) act relationship.
 *
 * Contains:
 * Quantity Measurement Observation
 * SOP Instance Observation
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
class characteristicsOfHomeEnvironment
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']) &&
            !isset($PortionData['systemCodeName']) &&
            !isset($PortionData['displayName'])){
            throw new Exception ('SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet
            Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC (CONF:28823).');
        }
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    public static function Structure(){
        return [
            'CharacteristicsOfHomeEnvironment' => [
                'code' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC',
                'systemCodeName' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC',
                'displayName' => 'SHALL contain exactly one [1..1] value, which SHOULD be selected from ValueSet Residence and Accommodation Type 2.16.840.1.113883.11.20.9.49 DYNAMIC',
                sopInstanceObservation::Structure(),
                quantityMeasurementObservation::Structure()
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

            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.13'),
                    'code' => [
                        '@attributes' => [
                            'code' => '18782-3',
                            'codeSystem' => '2.16.840.1.113883.6.1',
                            'codeSystemName' => 'LOINC',
                            'displayName' => 'Study observation'
                        ]
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => 'completed'
                        ]
                    ],
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'codeSystem' => Utilities::CodingSystemId( $PortionData['systemCodeName'] ),
                        'codeSystemName' => $PortionData['systemCodeName'],
                        'displayName' => $PortionData['displayName']
                    ]
                ]
            ];

            // SHALL contain exactly one [1..1] SOP Instance Observation
            // (templateId:2.16.840.1.113883.10.20.6.2.8) (CONF:16083).
            if(count($PortionData['SOPInstanceObservation'] > 0))
                $Entry['observation']['entryRelationship'][] = sopInstanceObservation::Insert(
                    $PortionData['SOPInstanceObservation'],
                    $CompleteData
                );

            // SHALL contain exactly one [1..1] Quantity Measurement Observation
            // (templateId:2.16.840.1.113883.10.20.6.2.14) (CONF:16084).
            if(count($PortionData['QuantityMeasurementObservation'] > 0))
                $Entry['observation']['entryRelationship'][] = quantityMeasurementObservation::Insert(
                    $PortionData['QuantityMeasurementObservation'],
                    $CompleteData
                );

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
