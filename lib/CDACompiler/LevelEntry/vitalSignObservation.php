<?php
/**
 * 5.65 Vital Sign Observation
 * This template provides a mechanism for grouping vital signs (e.g. grouping systolic blood
 * pressure and diastolic blood pressure).
 *
 *
 * Contains:
 * Author Participation (NEW)
 * Vital Sign Observation (V2)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class VitalSignObservation
 * @package LevelEntry
 */
class vitalSignObservation {

    public function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHOULD be selected from ValueSet Vital Sign Result Value Set');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Vital Sign Result Value Set');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHOULD be selected from ValueSet Vital Sign Result Value Set');
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHOULD be selected from ValueSet Vital Sign Result Value Set');
        if(!isset($PortionData['values']))
            throw new Exception('SHALL contain exactly one [1..1] @unit, which SHALL be selected from CodeSystem UCUM');
        if(!isset($PortionData['unit']))
            throw new Exception('SHALL contain exactly one [1..1] @unit, which SHALL be selected from CodeSystem UCUM');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData){

    }

    public static function Structure(){
        return [
            'VitalSignObservation' => [
                'code' => 'SHOULD be selected from ValueSet Vital Sign Result Value Set',
                'codeSystemName' => 'SHOULD be selected from ValueSet Vital Sign Result Value Set',
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'displayName' => 'SHOULD be selected from ValueSet Vital Sign Result Value Set',
                'values' => 'SHALL contain exactly one [1..1] @unit, which SHALL be selected from CodeSystem UCUM',
                'unit' => 'SHALL contain exactly one [1..1] @unit, which SHALL be selected from CodeSystem UCUM'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|Exception
     */
    public function insert($PortionData, $CompleteData)
    {
        try{
            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.85.2'),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'displayName' => $PortionData['displayName'],
                            'codeSystemName' => $PortionData['codeSystemName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'PQ',
                            'value' => $PortionData['values'],
                            'unit' => $PortionData['unit']
                        ]
                    ]
                ]
            ];

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
        catch(Exception $Error)
        {
            return $Error;
        }
    }
}
