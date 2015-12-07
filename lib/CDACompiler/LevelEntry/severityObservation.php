<?php

/**
 * 3.99	Severity Observation (V2)
 *
 * This clinical statement represents the gravity of the problem, such as allergy or reaction, in terms of its
 * actual or potential impact on the patient. The Severity Observation can be associated with an
 * Allergy - Intolerance Observation, Substance or Device Allergy - Intolerance Observation,
 * Reaction Observation or all. When the Severity Observation is associated directly with an allergy
 * it characterizes the allergy. When the Severity Observation is associated with a Reaction Observation
 * it characterizes a Reaction. A person may manifest many symptoms in a reaction to a single substance,
 * and each reaction to the substance can be represented. However, each reaction observation can have
 * only one severity observation associated with it. For example, someone may have a rash reaction observation
 * as well as an itching reaction observation, but each can have only one level of severity.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class severityObservation
 * @package LevelEntry
 */
class severityObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['severityCode']))
            throw new Exception('SHALL be selected from ValueSet Problem Severity');

        if(!isset($PortionData['severityCodeSystemName']))
            throw new Exception('SHALL be selected from ValueSet Problem Severity');

        if(!isset($PortionData['severityDisplayName']))
            throw new Exception('SHALL be selected from ValueSet Problem Severity');
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
            'SeverityObservation' => [
                'Narrated' => 'SHOULD contain zero or one [0..1] text',
                'severityCode' => 'SHALL be selected from ValueSet Problem Severity',
                'severityCodeSystemName' => 'SHALL be selected from ValueSet Problem Severity',
                'severityDisplayName' => 'SHALL be selected from ValueSet Problem Severity'
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.8.2'),
                    'code' => [
                        '@attributes' => [
                            'code' => 'SEV',
                            'codeSystem' => '2.16.840.1.113883.5.4',
                            'codeSystemName' => 'ActCode',
                            'displayName' => 'Severity Observation'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['severityCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['severityCodeSystemName']),
                            'codeSystemName' => $PortionData['severityCodeSystemName'],
                            'displayName' => $PortionData['severityDisplayName']
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or one [0..1] text
            if(isset($PortionData['Narrated']))
            {
                $Entry['observation'] = [
                    'reference' => [
                        '@attributes' => [
                            'value' => self::Narrative($PortionData['Narrated'])
                        ]
                    ]
                ];
            }

            // MAY contain zero or one [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Series Act
            if(count($PortionData['SeriesAct']) > 0)
            {
                foreach($PortionData['SeriesAct'] as $SeriesAct)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'COMP'
                        ],
                        sopInstanceObservation::Insert(
                            $SeriesAct,
                            $CompleteData
                        )
                    ];
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
