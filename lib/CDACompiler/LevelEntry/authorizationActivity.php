<?php

/**
 * 3.11	Authorization Activity
 *
 * An Authorization Activity represents authorizations or pre-authorizations currently active for the patient for
 * the particular payer.
 *
 * Authorizations are represented using an act subordinate to the policy or program that provided it.
 * The authorization refers to the policy or program. Authorized treatments can be grouped into an organizer class,
 * where common properties, such as the reason for the authorization, can be expressed. Subordinate acts represent
 * what was authorized.
 *
 * Contains:
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
class authorizationActivity
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['Relationships']) < 0)
            throw new Exception ('SHALL contain at least one [1..*] entryRelationship');
    }

    public static function Structure()
    {
        return [
            'AuthorizationActivity' => [
                0 => [
                    'code' => '',
                    'codeSystem' => '',
                    'codeSystemName' => ''
                ]
            ]
        ];
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
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
                'act' => [
                    '@attributes' => [
                        'classCode' => 'ACT',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.1.19'),
                    'id' => Component::id( Utilities::UUIDv4() )
                ]
            ];

            foreach ($PortionData['AuthorizationActivity'] as $AuthorizationActivity)
            {
                $Entry['act']['entryRelationship'][] = [
                    '@attributes' => [
                        'typeCode' => 'SUBJ'
                    ],
                    'procedure' => [
                        '@attributes' => [
                            'classCode' => 'PROC',
                            'moodCode' => 'PRMS'
                        ],
                        'code' => [
                            'code' => $AuthorizationActivity['code'],
                            'codeSystem' => $AuthorizationActivity['codeSystem'],
                            'codeSystemName' => $AuthorizationActivity['codeSystemName'],
                            'codeSystem' => Utilities::CodingSystemId( $AuthorizationActivity['codeSystemName'] )
                        ]
                    ]
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
