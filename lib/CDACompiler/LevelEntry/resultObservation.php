<?php

/**
 * 3.93	Result Observation (V2)
 *
 * This template represents the results of a lab, radiology, or other study performed on a patient.
 * The result observation includes a statusCode to allow recording the status of an observation.
 * “Pending” results (e.g., a test has been run but results have not been reported yet) should be
 * represented as “active” ActStatus.
 *
 * Contains:
 * Author Participation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class resultObservation
 * @package LevelEntry
 */
class resultObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['status']))
            throw new Exception('SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet Result Status');
        if(!isset($PortionData['value']))
            throw new Exception('SHALL contain exactly one [1..1] value | SHALL be expressed using a valid Unified Code for Units of Measure (UCUM) expression');
        if(!isset($PortionData['unit']))
            throw new Exception('SHALL contain exactly one [1..1] value | SHALL be expressed using a valid Unified Code for Units of Measure (UCUM) expression');
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
            'ResultObservation' => [
                'code' => 'SHALL contain exactly one [1..1] code',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code',
                'displayName' => 'SHALL contain exactly one [1..1] code',
                'status' => 'SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet Result Status',
                'effectiveTime' => 'Represents the biologically relevant time of the measurement (e.g. the time a blood pressure reading is obtained, the time the blood sample was obtained for a chemistry test)',
                'value' => 'SHALL contain exactly one [1..1] value | SHALL be expressed using a valid Unified Code for Units of Measure (UCUM) expression',
                'unit' => 'SHALL contain exactly one [1..1] value | SHALL be expressed using a valid Unified Code for Units of Measure (UCUM) expression',
                'interpretationCode' => [
                    0 => [
                        'code' => 'SHOULD contain zero or more [0..*] interpretationCode',
                        'codeSystem' => 'SHOULD contain zero or more [0..*] interpretationCode'
                    ]
                ],
                LevelOther\authorParticipation::Structure()
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
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.2.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode($PortionData['status']),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'PQ',
                            'value' => $PortionData['value'],
                            'unit' => $PortionData['unit']
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] interpretationCode
            if(count($PortionData['InterpretationCode']) > 0)
            {
                foreach($PortionData['InterpretationCode'] as $InterpretationCode)
                {
                    $Entry['observation']['interpretationCode'][] = [
                        'interpretationCode' => [
                            '@attributes' => [
                                'code' => $InterpretationCode['code'],
                                'codeSystem' => $InterpretationCode['codeSystem']
                            ]
                        ]
                    ];
                }

            }

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

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
