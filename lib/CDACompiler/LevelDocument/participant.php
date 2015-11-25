<?php

/**
 * 1.13.9	participant
 *
 * The participant element identifies other supporting participants, including parents, relatives, caregivers,
 * insurance policyholders, guarantors, and other participants related in some way to the patient.
 *
 * A supporting person or organization is an individual or an organization with a relationship to the patient.
 * A supporting person who is playing multiple roles would be recorded in multiple participants
 * (e.g., emergency contact and next-of-kin)
 *
 * 11.	MAY contain zero or more [0..*] participant (CONF:28703).
 *
 * Unless otherwise specified by the document specific header constraints, when participant/@typeCode is IND,
 * associatedEntity/@classCode SHALL be selected from ValueSet 2.16.840.1.113883.11.20.9.33
 * INDRoleclassCodes STATIC 2011-09-30
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class participant
 * @package LevelDocument
 */
class participant
{
    /**
     * @param $Data
     */
    private static function Validate($Data)
    {

    }

    /**
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try {

            // Validate first
            self::Validate($Data);

            // Build the section
            foreach($Data['Participants'] as $Participant) {
                $Section[] = array(
                    'participant' => [
                        '@attributes' => [
                            'typeCode' => 'IND'
                        ],
                        'time' => [
                            '@attributes' => [
                                'xsi:type' => 'IVL_TS'
                            ],
                            'low' => $Participant['startDate'],
                            'high' => $Participant['endDate'],
                        ],
                        'associatedEntity' => [
                            '@attributes' => [
                                'classCode' => 'NOK'
                            ],
                            'code' => Component::PersonalAndLegalRelationshipRole($Participant['relationship']),
                            'addr' => Component::addr(
                                $Participant['address']['use'],
                                $Participant['address']['streetAddressLine'],
                                $Participant['address']['city'],
                                $Participant['address']['state'],
                                $Participant['address']['postalCode'],
                                $Participant['address']['country']
                            ),
                            'telecom' => Component::telecom($Participant['telecom']),
                            'name' => Component::name($Participant['name'])
                        ]
                    ]
                );
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}

