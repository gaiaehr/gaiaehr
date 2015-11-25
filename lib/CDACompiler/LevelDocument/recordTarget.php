<?php

/**
 * 1.14.1	recordTarget
 *
 * The recordTarget records the administrative and demographic data of the patient whose health information is
 * described by the clinical document; each recordTarget must contain at least one patientRole element
 *
 * The sdtc:raceCode is only used to record additional values when the patient has indicated multiple races.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class recordTarget
 * @package LevelDocument
 */
class recordTarget
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
        try
        {
            // Validate first
            self::Validate($Data);

            // Build the section
            $Section = [
                'recordTarget' => [
                    'patientRole' => [
                        'id' => Component::id(
                            $Data['Patient']['socialSecurity'],
                            Utilities::CodingSystemId('SocialSecurityNumber')
                        ),
                        'addr' => Component::addr(
                            $Data['Patient']['address']['use'],
                            $Data['Patient']['address']['streetAddressLine'],
                            $Data['Patient']['address']['city'],
                            $Data['Patient']['address']['state'],
                            $Data['Patient']['address']['postalCode'],
                            $Data['Patient']['address']['country']
                        ),
                        'telecom' => Component::telecom(
                            $Data['Patient']['telecom']['use'],
                            $Data['Patient']['telecom']['value']
                         ),
                        'patient' => [
                            'name' => [
                            Component::name(
                                $Data['Patient']['name']['prefix'],
                                $Data['Patient']['name']['prefixQualifier'],
                                $Data['Patient']['name']['given'],
                                $Data['Patient']['name']['givenQualifier'],
                                $Data['Patient']['name']['family'],
                                $Data['Patient']['name']['familyQualifier'],
                                $Data['Patient']['name']['name'],
                                $Data['Patient']['name']['nameQualifier']
                            )],
                            'administrativeGenderCode' => Component::administrativeGenderCode(
                                $Data['Patient']['gender']['code']
                            ),
                            'birthTime' => Component::birthTime($Data['Patient']['dateOfBirth']),
                            'maritalStatusCode' => Component::maritalStatusCode($Data['Patient']['maritalStatus']),
                            'religiousAffiliationCode' => Component::religiousAffiliationCode(
                                $Data['Patient']['religious']['code']
                            ),
                            'raceCode' => Component::raceCode($Data['Patient']['race']['code']),
                            'ethnicGroupCode' => Component::ethnicGroupCode($Data['Patient']['race']['ethnic']),
                            'birthplace' => [
                                'place' => [
                                    'addr' => Component::addr(
                                        null,
                                        $Data['Patient']['birthplace']['address']['streetAddressLine'],
                                        $Data['Patient']['birthplace']['address']['city'],
                                        $Data['Patient']['birthplace']['address']['state'],
                                        $Data['Patient']['birthplace']['address']['postalCode'],
                                        $Data['Patient']['birthplace']['address']['country']
                                    )
                                ]
                            ]
                        ],
                        'providerOrganization' => [
                            'id' => Component::id('1.1.1.1.1.1.1.1.2', $Data['Clinic']['uin']),
                            'name' => $Data['Clinic']['name'],
                            'telecom' => Component::telecom(
                                'WP',
                                $Data['Clinic']['telecom']['value']
                            ),
                            'addr' =>  Component::addr(
                                null,
                                $Data['Clinic']['address']['streetAddressLine'],
                                $Data['Clinic']['address']['city'],
                                $Data['Clinic']['address']['state'],
                                $Data['Clinic']['address']['postalCode'],
                                $Data['Clinic']['address']['country']
                            )
                        ]
                    ]
                ]
            ];

            // iii.	This patientRole MAY contain zero or one [0..1] providerOrganization (CONF:28476).
            if(isset($Data['Patient']['language'])) {
                $Section['recordTarget']['patientRole']['patient']['languageCommunication'] = [
                    'languageCode' => Component::languageCode($Data['Patient']['language']['primary']),
                    'modeCode' => Component::languageAbilityMode($Data['Patient']['language']['ability']),
                    'proficiencyLevelCode' => $Data['Patient']['language']['proficiency'],
                    'preferenceInd' => Component::preferenceInd($Data['Patient']['language']['indicator'])
                ];
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }
}
