<?php

/**
 * 3.59	Nutrition Assessment (NEW)
 *
 * This template represents the patient's nutrition abilities and habits including intake,
 * diet requirements or diet followed.
 *
 * Contains:
 * Author Participation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class nutritionAssessment
 * @package LevelEntry
 */
class nutritionAssessment
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');

        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Assessment');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Assessment');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Assessment');
    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
            'code' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Assessment',
            'displayName' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Assessment',
            'codeSystemName' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Nutrition Assessment',
            'Author' => LevelOther\authorParticipation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.138'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'code' => '230125005',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'codeSystemName' => 'SNOMED CT',
                        'displayName' => 'diet followed'
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        'xsi:type' => 'CD',
                        'code' => $PortionData['code'],
                        'displayName' => $PortionData['displayName'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName']

                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach($PortionData['Author'] as $Author)
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
