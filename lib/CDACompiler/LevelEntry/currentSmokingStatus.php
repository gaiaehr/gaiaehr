<?php

/**
 * 3.23	Current Smoking Status (V2)
 *
 * This template constrains the Tobacco Use template to represent the current smoking status of the patient as
 * specified in Meaningful Use (MU) Stage 2 requirements. Historic smoking status observations as well as details
 * about the smoking habit (e.g., how many per day) would be represented in the Tobacco Use template.
 *
 * This template represents a “snapshot in time” observation, simply reflecting what the patient’s current
 * smoking status is at the time of the observation. As a result, the effectiveTime is constrained to a time stamp,
 * and will approximately correspond with the author/time.
 *
 * The effectiveTime element reflects the date/time when the patient's current smoking status was observed.
 * Details regarding the time period when the patient is/was smoking would be recorded in the Tobacco Use template.
 *
 * If the patient's current smoking status is unknown, the value element must be populated with
 * SNOMED CT code '266927001' to communicate 'Unknown if ever smoked' from the Current Smoking Status Value Set.
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
 * Class advanceDirectiveOrganizer
 * @package LevelEntry
 */
class currentSmokingStatus
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:14814)');

        if(!isset($PortionData['code']) &&
            !isset($PortionData['displayName']) &&
            !isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] @code, which SHALL be selected from
                    ValueSet Current Smoking Status 2.16.840.1.113883.11.20.9.38.2 DYNAMIC 2013-07-25 (CONF:14817)');
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'CurrentSmokingStatus' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime (CONF:14814)',
                'code' => 'SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet Current Smoking Status 2.16.840.1.113883.11.20.9.38.2 DYNAMIC 2013-07-25 (CONF:14817)',
                'displayName' => 'SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet Current Smoking Status 2.16.840.1.113883.11.20.9.38.2 DYNAMIC 2013-07-25 (CONF:14817)',
                'codeSystemName' => 'SHALL contain exactly one [1..1] @code, which SHALL be selected from ValueSet Current Smoking Status 2.16.840.1.113883.11.20.9.38.2 DYNAMIC 2013-07-25 (CONF:14817)',
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.78.2'),
                    'code' => [
                        '@attributes' => [
                            'code' => '229819007',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'displayName' => 'Tobacco use and exposure'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => $PortionData['effectiveTime'],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'displayName' => $PortionData['displayName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName'])
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.119) (CONF:31148).
            if (count($PortionData['Authors']) > 0)
            {
                foreach ($PortionData['Authors'] as $Author)
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

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

    }

}
