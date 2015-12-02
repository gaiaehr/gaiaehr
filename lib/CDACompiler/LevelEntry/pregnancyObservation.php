<?php

/**
 * 3.68	Planned Observation (V2)
 *
 * This template represents a Planned Observation. The importance of the the planned observation to the patient
 * and provider is communicated through Patient Priority Preference and Provider Priority Preference.
 *
 * Contains:
 * Estimated Date of Delivery
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class plannedObservation
 * @package LevelEntry
 */
class plannedObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['moodCode']))
            throw new Exception('SHALL be selected from ValueSet Plan of Care moodCode (Act/Encounter/Procedure)');

        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code');

        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');
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
            'observation' => [
                'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime (CONF:2018)',
                'code' => 'This value SHALL contain exactly one [1..1] @code="77386006" Pregnant (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96) (CONF:26460)',
                'codeSystem' => 'This value SHALL contain exactly one [1..1] @code="77386006" Pregnant (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96) (CONF:26460)',
                'displayName' => 'This value SHALL contain exactly one [1..1] @code="77386006" Pregnant (CodeSystem: SNOMED CT 2.16.840.1.113883.6.96) (CONF:26460)',
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'EstimatedDateOfDelivery' => estimatedDateOfDelivery::Structure()
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
        try {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'moodCode' => 'EVN',
                        'classCode' => 'OBS'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.15.3.8'),
                    'id' => [
                        'extension' => '',
                        'root' => '2.16.840.1.113883.19'
                    ],
                    'code' => [
                        'code' => 'ASSERTION',
                        'codeSystem' => '2.16.840.1.113883.5.4'
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => [
                        'low' => [
                            '@attributes' => [
                                'value' => $PortionData['effectiveTime']
                            ]
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'displayName' => $PortionData['displayName'],
                            'codeSystem' => $PortionData['codeSystem']
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] entryRelationship (CONF:458)
            // SHALL contain exactly one [1..1] Estimated Date of Delivery
            if(count($PortionData['EstimatedDateOfDelivery']) > 0)
            {
                $Entry['observation']['entryRelationship '][] = estimatedDateOfDelivery::Insert(
                    $PortionData['EstimatedDateOfDelivery'],
                    $CompleteData
                );
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
