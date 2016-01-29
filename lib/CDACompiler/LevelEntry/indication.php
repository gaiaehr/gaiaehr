<?php

/**
 * 3.47	Indication (V2)
 *
 * This template represents the rationale for an action such as the reason for an encounter, a medication
 * administration or a procedure. The id element can be used to reference a problem recorded elsewhere in the
 * document or with a code and value to record the problem. Indications for treatment are not lab results,
 * rather the problem associated with the lab result should be sited (e.g., hypokalemia instead of a lab result
 * of Potassium 2.0 mEq/L). Use the Drug Monitoring Act templateId 2.16.840.1.113883.10.20.22.4.123] to indicate
 * if a particular drug needs special monitoring (e.g., anticoagulant therapy). Use Precondition for
 * Substance Administration templateId 2.16.840.1.113883.10.20.22.4.25] to represent that a medication is
 * to be administered only when the associated criteria are met.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class indication
 * @package LevelEntry
 */
class indication
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:8834)');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:8834)');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime (CONF:8834)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'Indication' => [
                'code' => 'SHOULD be selected from ValueSet Problem Value Set',
                'codeSystemName' => 'SHOULD be selected from ValueSet Problem Value Set',
                'displayName' => 'SHOULD be selected from ValueSet Problem Value Set'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {

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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.19.2'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ]
                ]
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
