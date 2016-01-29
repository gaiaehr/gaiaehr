<?php

/**
 * 1.13.6	informationRecipient
 *
 * The informationRecipient element records the intended recipient of the information at the time the document
 * is created. For example, in cases where the intended recipient of the document is the patient's health chart,
 * set the receivedOrganization to be the scoping organization for that chart.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class informationRecipient
 * @package LevelDocument
 */
class informationRecipient
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
            $Section = array(
                'informationRecipient' => [
                    'intendedRecipient' => [
                        'id' => Component::id(
                            '2.16.123.123.12345.1234',
                            $Data['destinationProvider']['directMessageAddress']
                        ),
                        'informationRecipient' => Component::name(
                            $Data['destinationProvider']['name']['prefix'],
                            $Data['destinationProvider']['name']['prefixQualifier'],
                            $Data['destinationProvider']['name']['given'],
                            $Data['destinationProvider']['name']['givenQualifier'],
                            $Data['destinationProvider']['name']['family'],
                            $Data['destinationProvider']['name']['familyQualifier'],
                            $Data['destinationProvider']['name']['name'],
                            $Data['destinationProvider']['name']['nameQualifier']
                        ),
                        'receivedOrganization' => [
                            'id' => Component::id(
                                '2.16.840.1.113883.19',
                                '999.3'
                            ),
                            'name' => $Data['destinationProvider']['clinicName']
                        ]
                    ]
                ],
                'informationRecipient' => [
                    'intendedRecipient' => [
                        'id' => Component::id(
                            '2.16.840.1.113883.19',
                            '999.4'
                        ),
                        'id' => array(
                            0 => Component::id(
                                '2.16.840.1.113883.4.6',
                                $Data['Provider']['npi']),
                            1 => Component::id(
                                '2.16.123.123.12345.4321',
                                $Data['Provider']['directMessageAddress'])
                        ),
                        'telecom' => array(
                            0 => Component::telecom(
                                $Data['Provider']['phone']['use'],
                                $Data['Provider']['phone']['value']
                            ),
                            1 => Component::telecom(
                                $Data['Provider']['email']['use'],
                                $Data['Provider']['email']['value']
                            )
                        ),
                        'informationRecipient' => [
                            'name' => Component::name(
                                $Data['Provider']['name']['prefix'],
                                $Data['Provider']['name']['prefixQualifier'],
                                $Data['Provider']['name']['given'],
                                $Data['Provider']['name']['givenQualifier'],
                                $Data['Provider']['name']['family'],
                                $Data['Provider']['name']['familyQualifier'],
                                $Data['Provider']['name']['name'],
                                $Data['Provider']['name']['nameQualifier']
                            )
                        ],
                        'receivedOrganization' => [
                            'id' => array(
                                0 => Component::id(
                                    '2.16.840.1.113883.19',
                                    '999.2'
                                ),
                                1 => Component::id(
                                    '2.16.840.1.113883.4.6',
                                    $Data['Clinic']['npi']
                                )
                            ),
                            'name' => $Data['Clinic']['name'],
                            'telecom' => Component::telecom(
                                $Data['Clinic']['phone']['use'],
                                $Data['Clinic']['phone']['phone']
                            ),
                            'addr' => Component::addr(
                                $Data['Clinic']['address']['use'],
                                $Data['Clinic']['address']['streetAddressLine'],
                                $Data['Clinic']['address']['city'],
                                $Data['Clinic']['address']['state'],
                                $Data['Clinic']['address']['postalCode'],
                                $Data['Clinic']['address']['country']
                            )
                        ]
                    ]
                ]
            );
            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}

