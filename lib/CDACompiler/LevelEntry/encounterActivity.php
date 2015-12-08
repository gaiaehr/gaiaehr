<?php

/**
 * 3.28	Encounter Activity (V2)
 *
 * This clinical statement describes an interaction between a patient and clinician. Interactions may include
 * in-person encounters, telephone conversations, and email exchanges.
 *
 * Contains:
 * Encounter Diagnosis (V2)
 * Indication (V2)
 * Service Delivery Location
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class encounterActivity
 * @package LevelEntry
 */
class encounterActivity
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime ']))
            throw new Exception('6.	SHALL contain exactly one [1..1] effectiveTime (CONF:8715)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'encounterActivity' => [
                'code' => 'SHOULD contain zero or one [0..1] code, which SHOULD be selected from ValueSet EncounterTypeCode 2.16.840.1.113883.3.88.12.80.32 DYNAMIC',
                'displayName' => 'SHOULD contain zero or one [0..1] code, which SHOULD be selected from ValueSet EncounterTypeCode 2.16.840.1.113883.3.88.12.80.32 DYNAMIC',
                'systemCodeName' => 'SHOULD contain zero or one [0..1] code, which SHOULD be selected from ValueSet EncounterTypeCode 2.16.840.1.113883.3.88.12.80.32 DYNAMIC',
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime (CONF:8715)',
                'Narrated' => 'SHOULD contain zero or one [0..1] originalText (CONF:8719)',
                LevelDocument\performer::Structure(),
                indication::Structure(),
                encounterDiagnosis::Structure()
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
                'encounter' => [
                    '@attributes' => [
                        'classCode' => 'ENC',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.49.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'translation' => [
                        'code' => 'AMB',
                        'codeSystem' => '2.16.840.1.113883.5.4',
                        'displayName' => 'Ambulatory',
                        'codeSystemName' => 'HL7 ActEncounterCode'
                    ],
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
                ]
            ];

            // SHOULD contain zero or one [0..1] code, which SHOULD be selected from
            // ValueSet EncounterTypeCode 2.16.840.1.113883.3.88.12.80.32 DYNAMIC (CONF:8714)
            if(isset($PortionData['code']) &&
                isset($PortionData['displayName']) &&
                isset($PortionData['systemCodeName']))
            {
                $Entry['encounter']['code'] = [
                    '@attributes' => [
                        'code' => $PortionData['code'],
                        'displayName' => $PortionData['displayName'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['systemCodeName']),
                        'codeSystem' => $PortionData['systemCodeName']
                    ]
                ];
            }

            // The code, if present, SHOULD contain zero or one [0..1] originalText (CONF:8719)
            if(isset($PortionData['Narrated']))
                $Entry['encounter']['originalText'] = $PortionData['Narrated'];

            // MAY contain zero or more [0..*] performer (CONF:8725)
            // SHALL contain exactly one [1..1] Service Delivery Location
            if(count($PortionData['Performer']) > 0)
            {
                foreach($PortionData['Performer'] as $Performer)
                {
                    $Entry['encounter']['performer'][] = LevelDocument\performer::Insert($Performer);
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:8722)
            // SHALL contain exactly one [1..1] Indication (V2)
            if(count($PortionData['Indication']) > 0)
            {
                foreach($PortionData['Indication'] as $Indication)
                {
                    $Entry['encounter']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        'entry' => [
                            '@attributes' => [
                                'typeCode' => 'DRIV'
                            ],
                            indication::Insert($Indication)
                        ]
                    ];
                }
            }

            // MAY contain zero or more [0..*] entryRelationship (CONF:15492)
            // SHALL contain exactly one [1..1] Encounter Diagnosis (V2)
            if(count($PortionData['EncounterDiagnosis']) > 0)
            {
                foreach($PortionData['EncounterDiagnosis'] as $EncounterDiagnosis)
                {
                    $Entry['encounter']['entryRelationship'][] = encounterDiagnosis::Insert(
                        $EncounterDiagnosis,
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
