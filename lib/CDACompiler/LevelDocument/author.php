<?php
/**
 * 1.13.2	author
 *
 * The author element represents the creator of the clinical document.  The author may be a device, or a person.
 * The person is the patient or the patient’s advocate.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class author
 * @package LevelDocument
 */
class author
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
            $Section = [
                'author' => [
                    'time' => Component::time($Data['User']['datetime']),
                    'assignedAuthor' => [
                        'id' => Component::id('2.16.840.1.113883.4.6', $Data['User']['npi']),
                        'code' => Component::NUCCProviderCodes($Data['User']['nucc_code']),
                        'addr' => Component::addr(
                            $Data['User']['address']['use'],
                            $Data['User']['address']['streetAddressLine'],
                            $Data['User']['address']['city'],
                            $Data['User']['address']['state'],
                            $Data['User']['address']['postalCode'],
                            $Data['User']['address']['country']
                        ),
                        'telecom' => Component::telecom(
                            $Data['User']['telecom']['use'],
                            $Data['User']['telecom']['value']
                        ),
                        'assignedPerson' => [
                            'name' => [
                                Component::name(
                                    $Data['User']['name']['prefix'],
                                    $Data['User']['name']['prefixQualifier'],
                                    $Data['User']['name']['given'],
                                    $Data['User']['name']['givenQualifier'],
                                    $Data['User']['name']['family'],
                                    $Data['User']['name']['familyQualifier'],
                                    $Data['User']['name']['name'],
                                    $Data['User']['name']['nameQualifier']
                                )
                            ]
                        ]
                    ]
                ]
            ];
            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }
}
