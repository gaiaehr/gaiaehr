<?php

/**
 * 1.13.5	custodian
 *
 * The custodian element represents the organization or person that is in charge of maintaining the document.
 * The custodian is the steward that is entrusted with the care of the document. Every CDA document has exactly
 * one custodian. The custodian participation satisfies the CDA definition of Stewardship. Because CDA is an
 * exchange standard and may not represent the original form of the authenticated document
 * (e.g., CDA could include scanned copy of original), the custodian represents the steward of the original
 * source document. The custodian may be the document originator, a health information exchange, or
 * other responsible party. Also, the custodian may be the patient or an organization acting on behalf of
 * the patient, such as a PHR organization.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

class custodian
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
                'custodian' => [
                    'assignedCustodian' => [
                        'representedCustodianOrganization' => [
                            'id' => Component::id(
                                '2.16.840.1.113883.19',
                                '999.3'
                            ),
                            'name' => $Data['Clinic']['name'],
                            'addr' => Component::addr(
                                $Data['Clinic']['address']['use'],
                                $Data['Clinic']['address']['streetAddressLine'],
                                $Data['Clinic']['address']['city'],
                                $Data['Clinic']['address']['state'],
                                $Data['Clinic']['address']['postalCode'],
                                $Data['Clinic']['address']['country']
                            ),
                            'telecom' => Component::telecom(
                                $Data['Clinic']['phone']['use'],
                                $Data['Clinic']['phone']['value']
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

