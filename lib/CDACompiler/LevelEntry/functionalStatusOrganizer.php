<?php

/**
 * 3.35	Functional Status Organizer (V2)
 *
 * This template groups related functional status observations into categories (e.g ambulation, self-care).
 *
 * Contains:
 * Author Participation (NEW)
 * Functional Status Observation (V2)
 * Self-Care Activities (ADL and IADL) (NEW)
 *
 */

namespace LevelEntry;

use LevelDocument;
use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class functionalStatusOrganizer
 * @package LevelEntry
 */
class functionalStatusOrganizer
{
    /**
     * Validate the data of this Entry
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['FunctionalStatusObservation']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] Functional Status Observation (V2)');

        if(count($PortionData['SelfCareActivities_ADL_IADL']) < 1)
            throw new Exception('SHALL contain exactly one [1..1] Self-Care Activities (ADL and IADL) (NEW)');
    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'FunctionalStatusOrganizer' => [
                LevelDocument\author::Structure(),
                functionalStatusObservation::Structure(),
                selfCareActivitiesADLIADL::Structure()
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
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.4.66.2'),
                    'id' => Component::id( Utilities::UUIDv4() ),
                    'code' => [
                        '@attributes' => [
                            'code' => 'd5',
                            'displayName' => 'Self-Care',
                            'codeSystem' => '2.16.840.1.113883.6.254',
                            'codeSystemName' => 'ICF'
                        ]
                    ],
                    'statusCode' => Component::statusCode('completed')
                ]
            ];

            // SHOULD contain zero or more [0..*] Author Participation (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.119) (CONF:31585)
            if(count($PortionData['Authors']) > 0)
            {
                foreach($PortionData['Authors'] as $Author)
                {
                    $Entry['organizer']['author'][] = LevelDocument\author::Insert($Author);
                }
            }

            // SHALL contain at least one [1..*] component (CONF:14359)
            // SHALL contain exactly one [1..1] Functional Status Observation (V2)
            // (templateId:2.16.840.1.113883.10.20.22.4.67.2) (CONF:14368)
            if(count($PortionData['FunctionalStatusObservation']) > 0)
            {
                foreach($PortionData['FunctionalStatusObservation'] as $FunctionalStatusObservation)
                {
                    $Entry['organizer']['component'][] = functionalStatusObservation::Insert(
                        $FunctionalStatusObservation,
                        $CompleteData
                    );
                }
            }

            // SHALL contain at least one [1..*] component (CONF:31432)
            // SHALL contain exactly one [1..1] Self-Care Activities (ADL and IADL) (NEW)
            // (templateId:2.16.840.1.113883.10.20.22.4.128) (CONF:31433)
            if(count($PortionData['SelfCareActivities_ADL_IADL']) > 0)
            {
                foreach($PortionData['SelfCareActivities_ADL_IADL'] as $SelfCareActivities_ADL_IADL)
                {
                    $Entry['organizer']['component'][] = selfCareActivitiesADLAndIADL::Insert(
                        $SelfCareActivities_ADL_IADL,
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
