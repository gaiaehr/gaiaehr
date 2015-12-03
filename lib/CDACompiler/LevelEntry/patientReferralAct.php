<?php

/**
 * 3.64	Patient Referral Act (NEW)
 *
 * This template represents the type of referral (e.g., for dental care, to a specialist, for aging problems) and
 * represents whether the referral is for full care or shared care. It may contain a reference to another act in
 * the document instance representing the clinical reason for the referral (e.g., problem, concern, procedure).
 *
 * Contains:
 * Act Reference (NEW)
 * Author Participation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class patientReferralAct
 * @package LevelEntry
 */
class patientReferralAct
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL contain exactly one [1..1] effectiveTime');
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
            'PatientReferralAct' => [
                'effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'Author' => LevelOther\authorParticipation::Structure(),
                'DocumentReferenceClinicalReasonReferral' => actReference::Structure(),
                'FullOrSharedCareObservation' => [
                    0 => [
                        'code' => 'This entryRelationship represents whether the referral is for full or shared care.',
                        'codeSystemName' => 'This entryRelationship represents whether the referral is for full or shared care.',
                        'displayName' => 'This entryRelationship represents whether the referral is for full or shared care.'
                    ]
                ]
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
                        'moodCode' => 'INT',
                        'classCode' => 'ACT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.140'),
                    'id' => Component::id(Utilities::UUIDv4()),
                    'code' => [
                        'code' => '44383000',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'codeSystemName' => 'SNOMED',
                        'displayName' => 'Patient referral for consultation'
                    ],
                    'statusCode' => Component::statusCode('active'),
                    'effectiveTime' => Component::time($PortionData['effectiveTime'])
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach($PortionData['Author'] as $Author)
                {
                    $Entry['act']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
                        $CompleteData
                    );
                }
            }

            /**
             * This entry relationship represents a reference to another act in the document instance representing
             * the clinical reason for the referral (e.g. problem, concern, procedure).
             */
            // MAY contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Act Reference (NEW)
            if(count($PortionData['DocumentReferenceClinicalReasonReferral']) > 0)
            {
                foreach($PortionData['DocumentReferenceClinicalReasonReferral'] as $DocumentReferenceClinicalReasonReferral)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'RSON'
                        ],
                        actReference::Insert(
                            $DocumentReferenceClinicalReasonReferral,
                            $CompleteData
                        )
                    ];
                }
            }

            // SHOULD contain zero or more [0..*] entryRelationship
            // This entryRelationship represents whether the referral is for full or shared care.
            if(count($PortionData['FullOrSharedCareObservation']) > 0)
            {
                foreach($PortionData['FullOrSharedCareObservation'] as $FullOrSharedCareObservation)
                {
                    $Entry['act']['entryRelationship'][] = [
                        '@attributes' => [
                            'typeCode' => 'SUBJ'
                        ],
                        'observation' => [
                            '@attributes' => [
                                'classCode' => 'OBS',
                                'moodCode' => 'EVN'
                            ],
                            'code' => [
                                'code' => 'ASSERTION',
                                'codeSystem' => '2.16.840.1.113883.5.4'
                            ],
                            'statusCode' => Component::statusCode('completed'),
                            'value' => [
                                'xsi:type' => 'CD',
                                'code' => $FullOrSharedCareObservation['code'],
                                'displayName' => $FullOrSharedCareObservation['displayName'],
                                'codeSystem' => Utilities::CodingSystemId($FullOrSharedCareObservation['codeSystemName'])
                            ]
                        ]
                    ];
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
