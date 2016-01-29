<?php

/**
 * 3.48	Instruction (V2)
 *
 * The Instruction template can be used in several ways, such as to record patient instructions within a
 * Medication Activity or to record fill instructions within a supply order. The act/code defines the type of
 * instruction. Though not defined in this template, a Vaccine Information Statement (VIS) document could be
 * referenced through act/reference/externalDocument, and patient awareness of the instructions can be represented
 * with the generic participant and the participant/awarenessCode.
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
 * Class instruction
 * @package LevelEntry
 */
class instruction
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
            'Instruction' => [
                'code' => 'SHOULD be selected from ValueSet Problem Value Set',
                'codeSystemName' => 'SHOULD be selected from ValueSet Problem Value Set',
                'displayName' => 'SHOULD be selected from ValueSet Problem Value Set',
                'Narrated' => [
                    'text' => 'SHOULD contain zero or one [0..1] text (CONF:7395)'
                ]
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated']['text'];
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
                'act' => [
                    '@attributes' => [
                        'classCode' => 'ACT',
                        'moodCode' => 'INT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.20.2'),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'codeSystemName' => $PortionData['codeSystemName'],
                            'displayName' => $PortionData['displayName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed')
                ]
            ];

            // SHOULD contain zero or one [0..1] text
            if(isset($PortionData['Narrated']['text']))
                $Entry['act']['text'] = self::Narrative($PortionData);

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
