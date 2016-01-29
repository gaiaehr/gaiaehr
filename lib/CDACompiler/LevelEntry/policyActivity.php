<?php

/**
 * 3.72	Policy Activity (V2)
 *
 * A policy activity represents the policy or program providing the coverage. The person for whom payment is
 * being provided (i.e., the patient) is the covered party. The subscriber of the policy or program is
 * represented as a participant that is the holder the coverage. The payer is represented as the performer
 * of the policy activity.
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
 * Class policyActivity
 * @package LevelEntry
 */
class policyActivity
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['healthInsuranceCode']))
            throw new Exception('SHOULD be selected from ValueSet Health Insurance Type Value Set');
        if(!isset($PortionData['healthInsuranceCodeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet Health Insurance Type Value Set');
        if(count($PortionData['Payer']) < 0)
            throw new Exception('Payer Performer (CONF:16809)');
        if(count($PortionData['Coverage']) < 0)
            throw new Exception('Covered Party Participant (CONF:16814)');
        if(count($PortionData['Holder']) < 0)
            throw new Exception('Policy Holder Participant (CONF:16815)');
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
            'PolicyActivity' => [
                'healthInsuranceCode' => 'SHOULD be selected from ValueSet Health Insurance Type Value Set',
                'healthInsuranceCodeSystemName' => 'SHOULD be selected from ValueSet Health Insurance Type Value Set',
                'Payer' => LevelDocument\performer::Structure(),
                'Guarantor' => LevelDocument\performer::Structure(),
                'Coverage' => LevelDocument\participant::Structure(),
                'Holder' => LevelDocument\participant::Structure()
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
                        'moodCode' => 'EVN',
                        'classCode' => 'ACT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.61.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => $PortionData['healthInsuranceCode'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['healthInsuranceCodeSystemName']),
                        'codeSystemName' => $PortionData['healthInsuranceCodeSystemName']
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'participant' => [
                        0 => LevelDocument\participant::Insert($PortionData['Coverage']),
                        1 => LevelDocument\participant::Insert($PortionData['Holder'])
                    ]
                ]
            ];

            // SHOULD contain zero or more [1..1] performer Participation (NEW)
            // This templateId SHALL contain exactly one [1..1] Payer Performer
            if(count($PortionData['Payer']) > 0)
            {
                $Entry['act']['performer'][] = LevelDocument\performer::Insert(
                    $PortionData['Payer'][0],
                    $CompleteData
                );
            }

            // SHOULD contain zero or more [0..1] performer Participation (NEW)
            // This templateId SHALL contain exactly one [1..1] Guarantor Performer
            if(count($PortionData['Guarantor']) > 0)
            {
                $Entry['act']['performer'][] = LevelDocument\performer::Insert(
                    $PortionData['Guarantor'][0],
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
