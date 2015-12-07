<?php

/**
 * 3.106	Tobacco Use (V2)
 *
 * This template represents a patient’s tobacco use.
 *
 * All the types of tobacco use are represented using the codes from the tobacco use and exposure-finding
 * hierarchy in SNOMED CT, including codes required for recording smoking status in Meaningful Use Stage 2.
 *
 * The effectiveTime element is used to describe dates associated with the patient's tobacco use. Whereas
 * the Current Smoking Status template (templateId 2.16.840.1.113883.10.20.22.4.78.2) represents
 * a “snapshot in time” observation, simply reflecting what the patient’s current smoking status is at the
 * time of the observation, this Tobacco Use template uses effectiveTime to represent the biologically
 * relevant time of the observation. Thus, an observation of “former smoker” will have an effectiveTime
 * defining the time during which the patient has been a former smoker; an observation of “current smoker”
 * will have an effectiveTime defining the time during which the patient has been a current smoker.
 *
 * Contains:
 * Author Participation (NEW)
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class tobaccoUse
 * @package LevelEntry
 */
class tobaccoUse
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {

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
            'TobaccoUse' => [
                'code' => 'SHALL be selected from ValueSet Tobacco Use',
                'codeSystemName' => 'SHALL be selected from ValueSet Tobacco Use',
                'displayName' => 'SHALL be selected from ValueSet Tobacco Use',
                'from_effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                'to_effectiveTime' => 'SHALL contain exactly one [1..1] effectiveTime',
                LevelOther\authorParticipation::Structure()
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
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.85.2'),
                    'code' => [
                        '@attributes' => [
                            'code' => '229819007',
                            'codeSystem' => '2.16.840.1.113883.6.96',
                            'displayName' => 'Tobacco use and exposure'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed'),
                    'effectiveTime' => Component::time(
                        $PortionData['from_effectiveTime'],
                        $PortionData['to_effectiveTime']
                    ),
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD'
                        ],
                        'reference' => [
                            '@attributes' => [
                                'code' => $PortionData['code'],
                                'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                                'codeSystemName' => $PortionData['codeSystemName'],
                                'displayName' => $PortionData['displayName']
                            ]
                        ]
                    ]
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            if(count($PortionData['Author']) > 0)
            {
                foreach ($PortionData['Author'] as $Author)
                {
                    $Entry['observation']['author'][] = LevelOther\authorParticipation::Insert(
                        $Author,
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
