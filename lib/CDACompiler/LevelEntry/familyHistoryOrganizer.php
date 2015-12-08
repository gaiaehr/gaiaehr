<?php

/**
 * 3.33	Family History Organizer
 *
 * The Family History Organizer associates a set of observations with a family member. For example,
 * the Family History Organizer can group a set of observations about the patient’s father.
 *
 * Contains:
 * Family History Observation
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
class familyHistoryOrganizer
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['familyMemberCode']) &&
            !isset($PortionData['familyMemberDisplayName']) &&
            !isset($PortionData['familyMemberCodeSystemName']))
            throw new Exception( self::Structure()['familyMemberCode'] );

        if(count($PortionData['FamilyHistoryObservation']) < 1)
            throw new Exception('SHALL contain exactly one [1..1]
            Family History Observation (templateId:2.16.840.1.113883.10.20.22.4.46) (CONF:16888)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'familyHistoryOrganizer' => [
                'familyMemberCode' => 'This code SHALL contain exactly one [1..1] @code, which SHOULD be selected from ValueSet Family Member Value Set 2.16.840.1.113883.1.11.19579 DYNAMIC',
                'familyMemberDisplayName' => 'This code SHALL contain exactly one [1..1] @code, which SHOULD be selected from ValueSet Family Member Value Set 2.16.840.1.113883.1.11.19579 DYNAMIC',
                'familyMemberCodeSystemName' => 'This code SHALL contain exactly one [1..1] @code, which SHOULD be selected from ValueSet Family Member Value Set 2.16.840.1.113883.1.11.19579 DYNAMIC',
                'subject' => [
                    'genderCode' => 'SHALL contain exactly one [1..1] administrativeGenderCode (CONF:15974)',
                    'birthTime' => ', SHOULD contain zero or one [0..1] birthTime (CONF:15976)'
                ],
                familyHistoryObservation::Structure()
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
                'organizer' => [
                    '@attributes' => [
                        'classCode' => 'CLUSTER',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.45'),
                    'statusCode' => Component::statusCode('completed'),
                    'subject' => [
                        'relatedSubject' => [
                            '@attributes' => [
                                'classCode' => 'PRS'
                            ],
                            'code' => [
                                '@attributes' => [
                                    'code' => $PortionData['familyMemberCode'],
                                    'displayName' => $PortionData['familyMemberDisplayName'],
                                    'codeSystem' => Utilities::CodingSystemId($PortionData['familyMemberCodeSystemName']),
                                    'codeSystemName' => $PortionData['familyMemberCodeSystemName']
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            // This relatedSubject SHOULD contain zero or one [0..1] subject (CONF:15248)
            if(isset($PortionData['subject'])){
                $Entry['organizer']['subject'] = [
                    'administrativeGenderCode' => [
                        '@attributes' => [
                            'code' => $PortionData['genderCode']
                        ]
                    ],
                    'birthTime' => Component::time($PortionData['birthTime'])
                ];
            }


            // SHALL contain at least one [1..*] component (CONF:8607)
            // Such components SHALL contain exactly one [1..1] Family History Observation
            // (templateId:2.16.840.1.113883.10.20.22.4.46) (CONF:16888)
            if(count($PortionData['FamilyHistoryObservation']) > 0)
            {
                foreach($PortionData['FamilyHistoryObservation'] as $Observation)
                $Entry['organizer']['component'] = [
                    'observation' => familyHistoryObservation::Insert(
                        $Observation,
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
