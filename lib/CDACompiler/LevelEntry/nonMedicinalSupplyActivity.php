<?php

/**
 * 3.57	Non-Medicinal Supply Activity (V2)
 *
 * This template represents non-medicinal supplies, such as medical equipment. - NOTES: RENT OR OWN EXPIRATION DATE
 *
 * Contains:
 * Instruction (V2)
 * Product Instance
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
class medicationDispense
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['statusCode']))
            throw new Exception('SHALL contain exactly one [1..1] statusCode');
    }


    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'statusCode' => 'SHALL contain exactly one [1..1] statusCode',
            'effectiveTime' => 'SHOULD contain zero or one [0..1] effectiveTime',
            'quantity' => 'SHOULD contain zero or one [0..1] quantity',
            'ProductInstance' => productInstance::Structure(),
            'Instruction' => instruction::Structure()
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
                '@attributes' => [
                    'classCode' => 'SPLY',
                    'moodCode' => 'RQO'
                ],
                'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.50'),
                'id' => Component::id( Utilities::UUIDv4() ),
                'statusCode' => Component::statusCode('completed'),
                'effectiveTime' => [
                    '@attributes' => [
                        'xsi:type' => 'IVL TS'
                    ],
                    'high' => [
                        '@attributes' => [
                            'value' => $PortionData['effectiveTime']
                        ]
                    ]
                ],
                'quantity' => [
                    '@attributes' => [
                        'value' => $PortionData['quantity']
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] participant
            // SHALL contain exactly one [1..1] Product Instance
            if(count($PortionData['ProductInstance']) > 0){
                $Entry['participant'] = [
                    'participantRole'=> productInstance::Insert(
                        $PortionData['ProductInstance'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or more [0..1] entryRelationship
            // SHALL contain exactly one [1..1] Instruction (V2)
            if(count($PortionData['Instruction']) > 0)
            {
                $Entry['entryRelationship'][] = [
                    '@attributes' => [
                        'typeCode' => 'SUBJ'
                    ],
                    'act' => instruction::Insert(
                        $PortionData['Instruction'][0],
                        $CompleteData
                    )
                ];
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
