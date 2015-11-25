<?php

/**
 * 3.2	Admission Medication (V2)
 *
 * This template represents the medications taken by the patient prior to and at the time of admission.
 *
 * Contains:
 * Medication Activity (V2)
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
class admissionMedication
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        // 5. SHALL contain at least one [1..*] entryRelationship (CONF:7701)
        if(!isset($PortionData['entryRelationship']) ||
            count($PortionData['entryRelationship']) < 0)
        {
            throw new Exception ('SHALL contain at least one [1..*] entryRelationship (CONF:7701)');
        }
    }

    public static function Structure()
    {
        return [
            medicationActivity::Structure(),
            'Relationships' => [
                0 => medicationActivity::Structure()
            ]
        ];
    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data)
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
                '@attributes' => [
                    'classCode' => 'ACT',
                    'moodCode' => 'EVN'
                ],
                'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.36.2'),
                'code' => [
                    'code' => '42346-7',
                    'codeSystem' => '2.16.840.1.113883.6.1',
                    'codeSystemName' => 'LOINC'
                ]
            ];

            foreach ($PortionData['Relationships'] as $Relationship)
            {
                $Entry['entryRelationship'][] = [
                    '@attributes' => [
                        'typeCode' => 'SUBJ'
                    ],
                    'substanceAdministration' => [
                        '@attributes' => [
                            'classCode' => 'SBADM',
                            'moodCode' => 'EVN'
                        ]
                    ],
                    'substanceAdministration' => medicationActivity::Insert($Relationship, $CompleteData)
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
