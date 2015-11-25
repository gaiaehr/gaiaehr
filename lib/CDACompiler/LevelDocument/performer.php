<?php

/**
 * 1.3.3	performer
 *
 * The serviceEvent/performer represents the healthcare providers involved in the current or pertinent historical
 * care of the patient. Preferably, the patient’s key healthcare providers would be listed, particularly their
 * primary physician and any active consulting physicians, therapists, and counselors.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class performer
 * @package LevelDocument
 */
class performer
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
            foreach($Data['Provider'] as $Provider) {
                $Section[] = array(
                    '@attributes' => [
                        'typeCode' => 'PRF'
                    ],
                    'functionCode' => [
                        '@attributes' => [
                            'code' => $Provider['code'],
                            'codeSystem' => '2.16.840.1.113883.5.88',
                            'codeSystemName' => 'ParticipationFunction',
                            'displayName' => Component::participationFunction($Provider['code'])
                        ]
                    ],
                    'assignedEntity' => [
                        'id' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.4.6',
                                'extension' => $Provider['npi']
                            ]
                        ],
                        'code' => Component::taxonomyCode($Provider['taxonomy']),
                        'addr' => Component::addr(
                            $Provider['address']['use'],
                            $Provider['address']['streetAddressLine'],
                            $Provider['address']['city'],
                            $Provider['address']['state'],
                            $Provider['address']['postalCode'],
                            $Provider['address']['country']
                        ),
                        'telecom' => Component::telecom(
                            $Provider['telecom']['use'],
                            $Provider['telecom']['value']
                        ),
                        'assignedPerson' => [
                            'name' => Component::name(
                                $Provider['name']['prefix'],
                                $Provider['name']['prefixQualifier'],
                                $Provider['name']['given'],
                                $Provider['name']['givenQualifier'],
                                $Provider['name']['family'],
                                $Provider['name']['familyQualifier'],
                                $Provider['name']['name'],
                                $Provider['name']['nameQualifier']
                            )
                        ],
                        'representedOrganization' => [
                            'id' => [
                                'root' => '1.2.16.840.1.113883.4.6',
                                'extension' => '219BX'
                            ],
                            'name' => $Data['Clinic']['name'],
                            'telecom' => Component::telecom(
                                $Data['Clinic']['telecom']['use'],
                                $Data['Clinic']['telecom']['value']
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

