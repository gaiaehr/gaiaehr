<?php

/**
 * 3.32	Family History Observation
 *
 * Family History Observations related to a particular family member are contained within a Family History Organizer.
 * The effectiveTime in the Family History Observation is the biologically or clinically relevant time
 * of the observation. The biologically or clinically relevant time is the time at which the observation holds
 * (is effective) for the family member (the subject of the observation).
 *
 * Contains:
 * Age Observation
 * Family History Death Observation
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class encounterDiagnosis
 * @package LevelEntry
 */
class familyHistoryObservation
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['problemTypeCode']) &&
            !isset($PortionData['problemTypeDisplayName']) &&
            !isset($PortionData['problemTypeCodeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code, which SHOULD be selected from
            ValueSet Problem Type 2.16.840.1.113883.3.88.12.3221.7.2 STATIC 2012-06-01 (CONF:8589)');

        if(!isset($PortionData['problemValueCode']) &&
            !isset($PortionData['problemValueDisplayName']) &&
            !isset($PortionData['problemValueCodeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] value with @xsi:type="CD", where the code
            SHALL be selected from ValueSet Problem Value Set 2.16.840.1.113883.3.88.12.3221.7.4 DYNAMIC (CONF:8591)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'FamilyHistoryObservation' => [
                'problemTypeCode' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Problem Type 2.16.840.1.113883.3.88.12.3221.7.2 STATIC 2012-06-01',
                'problemTypeDisplayName' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Problem Type 2.16.840.1.113883.3.88.12.3221.7.2 STATIC 2012-06-01',
                'problemTypeCodeSystemName' => 'SHALL contain exactly one [1..1] code, which SHOULD be selected from ValueSet Problem Type 2.16.840.1.113883.3.88.12.3221.7.2 STATIC 2012-06-01',
                'problemValueCode' => 'SHALL contain exactly one [1..1] value with @xsi:type="CD", where the code SHALL be selected from ValueSet Problem Value Set 2.16.840.1.113883.3.88.12.3221.7.4 DYNAMIC',
                'problemValueDisplayName' => 'SHALL contain exactly one [1..1] value with @xsi:type="CD", where the code SHALL be selected from ValueSet Problem Value Set 2.16.840.1.113883.3.88.12.3221.7.4 DYNAMIC',
                'problemValueCodeSystemName' => 'SHALL contain exactly one [1..1] value with @xsi:type="CD", where the code SHALL be selected from ValueSet Problem Value Set 2.16.840.1.113883.3.88.12.3221.7.4 DYNAMIC',
                ageObservation::Structure(),
                familyHistoryDeathObservation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.46'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['problemTypeCode'],
                            'displayName' => $PortionData['problemTypeDisplayName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['problemTypeCodeSystemName']),
                            'codeSystemName' => $PortionData['problemTypeCodeSystemName']
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime']),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['problemValueCode'],
                            'displayName' => $PortionData['problemValueDisplayName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['problemValueCodeSystemName']),
                            'codeSystemName' => $PortionData['problemValueCodeSystemName']
                        ]
                    ]
                ]
            ];

            // MAY contain zero or one [0..1] entryRelationship (CONF:8675)
            // SHALL contain exactly one [1..1] Age Observation
            if(count($PortionData['AgeObservation']) > 0)
            {
                $Entry['observation']['entryRelationship'][] = [
                    '@attributes' => [
                        'typeCode' => 'SUBJ',
                        'inversionInd' => 'true'
                    ],
                    'observation' => ageObservation::Insert(
                        $PortionData['AgeObservation'][0],
                        $CompleteData
                    )
                ];
            }

            // MAY contain zero or one [0..1] entryRelationship (CONF:8678)
            // SHALL contain exactly one [1..1] Family History Death Observation
            if(count($PortionData['FamilyHistoryDeathObservation']) > 0)
            {
                $Entry['observation']['entryRelationship'][] = [
                    '@attributes' => [
                        'typeCode' => 'CAUS'
                    ],
                    'observation' => familyHistoryDeathObservation::Insert(
                        $PortionData['FamilyHistoryDeathObservation'][0],
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
