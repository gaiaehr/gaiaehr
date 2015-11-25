<?php

/**
 * 1.13.7	legalAuthenticator
 *
 * In a patient authored document, the legalAuthenticator identifies the single person legally responsible for
 * the document and must be present if the document has been legally authenticated. (Note that per the following
 * section, there may also be one or more document authenticators.)
 *
 * Based on local practice, patient authored documents may be provided without legal authentication.
 * This implies that a patient authored document that does not contain this element has not been legally authenticated.
 *
 * The act of legal authentication requires a certain privilege be granted to the legal authenticator depending
 * upon local policy. All patient documents have the potential for legal authentication, given the
 * appropriate legal authority.
 *
 * Local policies MAY choose to delegate the function of legal authentication to a device or system that
 * generates the document. In these cases, the legal authenticator is the person accepting responsibility for
 * the document, not the generating device or system.
 *
 * Note that the legal authenticator, if present, must be a person.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class legalAuthenticator
 * @package LevelDocument
 */
class legalAuthenticator
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
                'legalAuthenticator' => [
                    'time' => Component::time($Data['Clinic']['effectiveDate']),
                    'signatureCode' => 'S',
                    'id' => Component::id(
                        '2.16.123.123.12345.1234',
                        $Data['Provider']['directMessageAddress']
                    ),
                    'addr' => Component::addr(
                        $Data['Provider']['address']['use'],
                        $Data['Provider']['address']['streetAddressLine'],
                        $Data['Provider']['address']['city'],
                        $Data['Provider']['address']['state'],
                        $Data['Provider']['address']['postalCode'],
                        $Data['Provider']['address']['country']
                    ),
                    'telecom' => Component::telecom(
                        $Data['Provider']['phone']['use'],
                        $Data['Provider']['phone']['value']
                    ),
                    'assignedPerson' => [
                        'name' => Component::name(
                            $Data['destinationProvider']['name']['prefix'],
                            $Data['destinationProvider']['name']['prefixQualifier'],
                            $Data['destinationProvider']['name']['given'],
                            $Data['destinationProvider']['name']['givenQualifier'],
                            $Data['destinationProvider']['name']['family'],
                            $Data['destinationProvider']['name']['familyQualifier'],
                            $Data['destinationProvider']['name']['name'],
                            $Data['destinationProvider']['name']['nameQualifier']
                        )
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

