<?php

/**
 * 3.9	Assessment Scale Observation
 *
 * An assessment scale is a collection of observations that together yield a summary evaluation of a particular
 * condition. Examples include the Braden Scale (assesses pressure ulcer risk), APACHE Score (estimates mortality
 * in critically ill patients), Mini-Mental Status Exam (assesses cognitive function), APGAR Score
 * (assesses the health of a newborn), and Glasgow Coma Scale (assesses coma and impaired consciousness.)
 *
 * Contains:
 * Assessment Scale Supporting Observation
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
class assessmentScaleObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['displayName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHALL contain exactly one [1..1] code');
        if(!isset($PortionData['effectiveDate']))
            throw new Exception('SHALL contain exactly one [1..1] code');
    }

    public static function Structure()
    {
        return [
            'code' => 'SHALL contain exactly one [1..1] code',
            'displayName' => 'SHALL contain exactly one [1..1] code',
            'codeSystemName' => 'SHALL contain exactly one [1..1] code',
            'effectiveDate' => 'SHALL contain exactly one [1..1] effectiveTime',
            'AssessmentScaleSupportingObservations' => assessmentScaleSupportingObservation::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.69'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        'code' => $PortionData['code'],
                        'displayName' => $PortionData['displayName'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                        'codeSystemName' => $PortionData['codeSystemName'],
                    ],
                    'derivationExpr' => self::Narrative($PortionData),
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::effectiveTime($PortionData['effectiveDate'])
                ]
            ];

            // SHOULD contain zero or more [0..*] entryRelationship
            // SHALL contain exactly one [1..1] Assessment Scale Supporting Observation
            if(count($PortionData['AssessmentScaleSupportingObservations']) > 0)
            {
                foreach ($PortionData['AssessmentScaleSupportingObservations'] as $AssessmentScaleSupportingObservations)
                {
                    $Entry['observation']['entryRelationship'][] = assessmentScaleSupportingObservation::Insert(
                        $AssessmentScaleSupportingObservations,
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
