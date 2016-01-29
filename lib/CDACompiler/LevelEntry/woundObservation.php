<?php
/**
 * Class Wound Observation
 * 3.112 - Wound Observation
 *
 * This template represents acquired or surgical wounds commonly found in the long term care population.
 * It is not intended to encompass all wound types.  The template includes the general type of wound
 * (e.g. pressure ulcers, surgical incisions, deep tissue injury wounds) and can include wound measurements
 * and wound characteristics.
 *
 * Contains:
 * Author Participation (NEW)
 * Highest Pressure Ulcer Stage
 * Number of Pressure Ulcers Observation
 * Wound Characteristics (NEW)
 * Wound Measurement Observation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class WoundObservation
 * @package LevelEntry
 */
class woundObservation {

    /**
     * @param $PortionData
     * @throws Exception
     */
    public function Validate($PortionData)
    {

        if(!isset($PortionData['effectiveTme']))
            throw new Exception('??');
        if(!isset($PortionData['woundCode']))
            throw new Exception('SHOULD be selected from ValueSet Wound Type');
        if(!isset($PortionData['woundCodeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Wound Type');
        if(!isset($PortionData['woundDisplayName']))
            throw new Exception('SHOULD be selected from ValueSet Wound Type');
        if(!isset($PortionData['siteCode']))
            throw new Exception('SHOULD be selected from ValueSet Body Site Value Set');
        if(!isset($PortionData['siteCodeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Body Site Value Set');
        if(!isset($PortionData['siteDisplayName']))
            throw new Exception('SHOULD be selected from ValueSet Body Site Value Set');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData){

    }

    public static function Structure(){
        return [
            'WoundObservation' => [
                'effectiveTme' => '',
                'woundCode' => 'SHOULD be selected from ValueSet Wound Type',
                'woundCodeSystemName' => 'SHOULD be selected from ValueSet Wound Type',
                'woundDisplayName' => 'SHOULD be selected from ValueSet Wound Type',
                'siteCode' => 'SHOULD be selected from ValueSet Body Site Value Set',
                'siteCodeSystemName' => 'SHOULD be selected from ValueSet Body Site Value Set',
                'siteDisplayName' => 'SHOULD be selected from ValueSet Body Site Value Set',
                LevelOther\authorParticipation::Structure(),
                woundMeasurementObservation::Structure(),
                woundCharacteristics::Structure(),
                numberOfPressureUlcersObservation::Structure(),
                highestPressureUlcerStage::Struture()
            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|\Exception|Exception
     */
    public function insert($PortionData, $CompleteData)
    {
        try{
            // Compose the segment
            $Entry = array(
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => [
                        '@attributes' => [
                            'root' => '2.16.840.1.113883.10.20.22.4.114'
                        ]
                    ],
                    'id' => Component::id( Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTme']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['woundCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['woundCodeSystemName']),
                            'codeSystemName' => $PortionData['woundCodeSystemName'],
                            'displayName' => $PortionData['woundDisplayName']
                        ]
                    ],
                    'targetSiteCode' => [
                        '@attributes' => [
                            'code' => $PortionData['siteCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['siteCodeSystemName']),
                            'codeSystemName' => $PortionData['siteCodeSystemName'],
                            'displayName' => $PortionData['siteDisplayName']
                        ]
                    ]
                ]
            );

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['observation']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Wound Measurement Observation (NEW)
            if(count($PortionData['WoundMeasurementObservation']) > 0)
            {
                foreach ($PortionData['WoundMeasurementObservation'] as $WoundMeasurementObservation)
                {
                    $Entry['observation']['entryRelationship'][] = woundMeasurementObservation::Insert(
                        $WoundMeasurementObservation,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Wound Characteristics (NEW)
            if(count($PortionData['WoundCharacteristics']) > 0)
            {
                foreach ($PortionData['WoundCharacteristics'] as $WoundCharacteristics)
                {
                    $Entry['observation']['entryRelationship'][] = woundCharacteristics::Insert(
                        $WoundCharacteristics,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Number of Pressure Ulcers Observation
            if(count($PortionData['NumberOfPressureUlcersObservation']) > 0)
            {
                foreach ($PortionData['NumberOfPressureUlcersObservation'] as $NumberOfPressureUlcersObservation)
                {
                    $Entry['observation']['entryRelationship'][] = numberOfPressureUlcersObservation::Insert(
                        $NumberOfPressureUlcersObservation,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] entryRelationship
            // SHALL contain exactly one [1..1] Highest Pressure Ulcer Stage
            if(count($PortionData['HighestPressureUlcerStage']) > 0)
            {
                foreach ($PortionData['HighestPressureUlcerStage'] as $HighestPressureUlcerStage)
                {
                    $Entry['observation']['entryRelationship'][] = highestPressureUlcerStage::Insert(
                        $HighestPressureUlcerStage,
                        $CompleteData
                    );
                }
            }

            return $Entry;
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }
}
