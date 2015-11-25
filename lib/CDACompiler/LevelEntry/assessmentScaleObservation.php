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
    }

    public static function Structure()
    {
        return [
            'assessmentScaleCode' => '',
            'assessmentScaleName' => '',
            'assessmentScaleSystemName' => '',
            'status' => '',
            'effectiveDate' => '',
            'AssessmentScaleSupportingObservations' => assessmentScaleSupportingObservation\Structure()
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
                    'classCode' => 'OBS',
                    'moodCode' => 'EVN'
                ],
                'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.69'),
                'id' => Component::id( Utilities::UUIDv4() ),
                'code' => [
                    'code' => $PortionData['assessmentScaleCode'],
                    'displayName' => $PortionData['assessmentScaleName'],
                    'codeSystem' => Utilities::CodingSystemId($PortionData['assessmentScaleSystemName']),
                    'codeSystemName' => Utilities::CodingSystemId($PortionData['assessmentScaleSystemName']),
                ],
                'derivationExpr' => self::Narrative($PortionData),
                'statusCode' => [
                    '@attributes' => [
                        'code' => $PortionData['status']
                    ]
                ],
                'effectiveTime' => Component::effectiveTime($PortionData['effectiveDate'])
            ];

            foreach($PortionData['AssessmentScaleSupportingObservations'] as $Observation)
            {
                $Entry['observation'] = LevelEntry\assessmentScaleSupportingObservation\Insert($Observation);
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data){

    }

}
