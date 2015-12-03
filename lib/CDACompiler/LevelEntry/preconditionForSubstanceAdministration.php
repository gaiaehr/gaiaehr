<?php

/**
 * 3.74	Precondition for Substance Administration (V2)
 *
 * A criterion for administration can be used to record that the medication is to be administered only when
 * the associated criteria are met.
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
 * Class preconditionForSubstanceAdministration
 * @package LevelEntry
 */
class preconditionForSubstanceAdministration
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL be selected from ValueSet Problem Value Set');
        if(!isset($PortionData['codeSystem']))
            throw new Exception('SHALL be selected from ValueSet Problem Value Set');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL be selected from ValueSet Problem Value Set');
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
            'PreconditionForSubstanceAdministration' => [
                'text' => 'MAY contain zero or one [0..1] text',
                'code' => 'SHALL be selected from ValueSet Problem Value Set',
                'codeSystem' => 'SHALL be selected from ValueSet Problem Value Set',
                'displayName' => 'SHALL be selected from ValueSet Problem Value Set'
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
                'precondition' => [
                    '@attributes' => [
                        'typeCode' => 'PRCN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.25.1.2'),
                    'criterion' => [
                        'code' => [
                            '@attributes' => [
                                'code' => 'ASSERTION',
                                'codeSystem' => '2.16.840.1.113883.5.4'
                            ]
                        ],
                        'value' => [
                            '@attributes' => [
                                'xsi:type' => 'CE',
                                'code' => $PortionData['code'],
                                'codeSystem' => $PortionData['codeSystem'],
                                'displayName' => $PortionData['displayName']
                            ]
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] text (CONF:7373)
            if(isset($PortionData['text']))
            {
                $Entry['precondition']['criteion']['text'] = $PortionData['text'];
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
