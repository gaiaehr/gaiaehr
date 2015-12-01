<?php

/**
 * 1.13.3	dataEnterer
 *
 * The dataEnterer element represents the person who transferred the content, written or dictated by someone else,
 * into the clinical document. The guiding rule of thumb is that an author provides the content found within the
 * header or body of the document, subject to their own interpretation, and the dataEnterer adds that information
 * to the electronic system. In other words, a dataEnterer transfers information from one source to another
 * (e.g., transcription from paper form to electronic system). If the DataEnterer is missing, this role is assumed
 * to be played by the Author.
 *
 */


namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class dataEnterer
 * @package LevelDocument
 */
class dataEnterer
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
                'dataEnterer' => [
                    'assignedEntity' => [
                        'id' => Component::id(
                            '2.16.123.123.12345.1234',
                            $Data['UserCreated']['directMessageAddress']
                        ),
                        'code' => [
                            '@attributes' => [
                                'code' => $Data['who']['code'],
                                'displayName' => $Data['who']['displayName'],
                                'codeSystem' => Utilities::CodingSystemId($Data['who']['codeSystemName']),
                                'codeSystemName' => $Data['who']['codeSystemName']
                            ]
                        ],
                        'addr' => Component::addr(
                            $Data['UserCreated']['address']['use'],
                            $Data['UserCreated']['address']['streetAddressLine'],
                            $Data['UserCreated']['address']['city'],
                            $Data['UserCreated']['address']['state'],
                            $Data['UserCreated']['address']['postalCode'],
                            $Data['UserCreated']['address']['country']
                        ),
                        'telecom' => Component::telecom(
                            $Data['UserCreated']['telecom']['use'],
                            $Data['UserCreated']['telecom']['value']
                        ),
                        'assignedPerson' => [
                            'name' => [
                                Component::name(
                                    $Data['UserCreated']['name']['prefix'],
                                    $Data['UserCreated']['name']['prefixQualifier'],
                                    $Data['UserCreated']['name']['given'],
                                    $Data['UserCreated']['name']['givenQualifier'],
                                    $Data['UserCreated']['name']['family'],
                                    $Data['UserCreated']['name']['familyQualifier'],
                                    $Data['UserCreated']['name']['name'],
                                    $Data['UserCreated']['name']['nameQualifier']
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
