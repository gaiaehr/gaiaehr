<?php

/**
 * 3.46	Immunization Refusal Reason
 *
 * The Immunization Refusal Reason Observation documents the rationale for the patient declining an immunization.
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
 * Class immunizationRefusalReason
 * @package LevelEntry
 */
class immunizationRefusalReason
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from
            ValueSet No Immunization Reason Value Set');

        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from
            ValueSet No Immunization Reason Value Set');

        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHALL be selected from
            ValueSet No Immunization Reason Value Set');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'ImmunizationRefusalReason' => [
                'code' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet No Immunization Reason Value Set',
                'codeSystemName' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet No Immunization Reason Value Set',
                'displayName' => 'SHALL contain exactly one [1..1] code, which SHALL be selected from ValueSet No Immunization Reason Value Set'
            ]
        ];
    }

    /**
     * @param $PortionData
     * @return mixed
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.53'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => $PortionData['code'],
                        'displayName' => $PortionData['displayName'],
                        'codeSystemName' => $PortionData['codeSystemName'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName'])
                    ],
                    'statusCode' => Component::statusCode('completed')
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
