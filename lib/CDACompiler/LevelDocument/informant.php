<?php

/**
 * 1.14.4	informant
 *
 * The informant element describes an information source for any content within the clinical document. T
 * his informant is constrained for use when the source of information is an assigned health care provider
 * for the patient.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class informant
 * @package LevelDocument
 */
class informant
{
    /**
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
        foreach($Data['Providers'] as $Provider)
        {
            if(!isset($Provider['address'])) throw new Exception('[1.13.4 informant] SHALL contain address');
            if(!isset($Provider['name'])) throw new Exception('[1.13.4 informant] SHALL contain name');
            if(!isset($Provider['RelationshipCode'])) throw new Exception('[1.13.4 informant] SHALL contain RelationshipCode');
            if(!isset($Provider['RelationshipName'])) throw new Exception('[1.13.4 informant] SHALL contain RelationshipName');
            if(!isset($Provider['codeSystemName'])) throw new Exception('[1.13.4 informant] SHALL contain codeSystemName');
            if(!isset($Provider['codeSystemName'])) throw new Exception('[1.13.4 informant] SHALL contain codeSystemName');
        }
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
            $Section = [];
            foreach($Data['Providers'] as $Provider)
            {
                $Section['informant'][] = [
                    'informant' => [
                        'assignedEntity' => [
                            'id' => Component::id(
                                '2.16.840.1.113883.19',
                                '999.1'
                            ),
                            'code' => [
                                '@attributes' => [
                                    'code' => $Provider['RelationshipCode'],
                                    'displayName' => $Provider['RelationshipName'],
                                    'codeSystem' => Utilities::CodingSystemId($Provider['codeSystemName']),
                                    'codeSystemName' => $Provider['codeSystemName']
                                ]
                            ],
                            'addr' => Component::addr(
                                $Provider['address']['use'],
                                $Provider['address']['streetAddressLine'],
                                $Provider['address']['city'],
                                $Provider['address']['state'],
                                $Provider['address']['postalCode'],
                                $Provider['address']['country']
                            ),
                            'telecom' => Component::telecom(
                                $Provider['phone']['use'],
                                $Provider['phone']['value']
                            ),
                            'assignedPerson' => [
                                'name' =>  Component::name(
                                    $Provider['name']['prefix'],
                                    $Provider['name']['prefixQualifier'],
                                    $Provider['name']['given'],
                                    $Provider['name']['givenQualifier'],
                                    $Provider['name']['family'],
                                    $Provider['name']['familyQualifier'],
                                    $Provider['name']['name'],
                                    $Provider['name']['nameQualifier']
                                )
                            ]
                        ]
                    ]
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

