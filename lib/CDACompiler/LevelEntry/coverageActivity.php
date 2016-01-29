<?php

/**
 * 3.21	Coverage Activity (V2)
 *
 * A Coverage Activity groups the policy and authorization acts within a Payers Section to order the payment sources.
 * A Coverage Activity contains one or more policy activities, each of which contains zero or more
 * authorization activities. The Coverage Activity id is the Id from the patient's insurance card.
 * The sequenceNumber/@value shows the policy order of preference.
 *
 * Contains:
 * Policy Activity (V2)
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
class coverageActivity
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['PolicyActivity']) < 1)
            throw new Exception ('SHALL contain exactly one [1..1] Policy Activity (V2) (templateId:2.16.840.1.113883.10.20.22.4.61.2) (CONF:15528).');
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
    public static function Structure(){
        return [
            'CoverageActivity' => [
                policyActivity::Structure()
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
                'act' => [
                    '@attributes' => [
                        'classCode' => 'ACT',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.60.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => '48768-6',
                            'codeSystem' => '2.16.840.1.113883.6.1',
                            'codeSystemName' => 'LOINC',
                            'displayName' => 'Payment sources'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed')
                ]
            ];

            // SHALL contain at least one [1..*] entryRelationship (CONF:8878)
            if (count($PortionData['PolicyActivity']) > 0)
            {
                foreach ($PortionData['PolicyActivity'] as $PolicyActivity)
                {
                    $Entry['act']['entryRelationship'][] = policyActivity::Insert(
                        $PolicyActivity,
                        $CompleteData
                    );
                }
            }

            return $Entry;
        } catch (Exception $Error) {
            return $Error;
        }
    }

}
