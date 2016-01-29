<?php

/**
 * 3.3	Advance Directive Observation (V2)
 *
 * This clinical statement represents Advance Directives Observations findings
 * (e.g., “resuscitation status is Full Code”) rather than orders, and should not be considered legal documents.
 * The related legal documents are referenced using the reference/externalReference element.
 *
 * The Advance Directive Observation describes the patient’s directives, including but not limited to
 *
 * •  Medications
 * •  Transfer of Care to Hospital
 * •  Treatment
 * •  Procedures
 * •  Intubation and Ventilation
 * •  Diagnostic Tests
 * •  Tests
 *
 * The general category of the patient’s directive is documented in the observation/code element.
 * The observation/value element contains the detailed patient directive which may be coded or text.
 * For example, a category  directive  may be antibiotics, and the details would be intravenous antibiotics only.
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class advanceDirectiveObservation
 * @package LevelEntry
 */
class advanceDirectiveObservation
{

    /**
     * @param $PortionData
     */
    private static function Validate($PortionData)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    /**
     * Give back the structure of this Entry
     * @return array
     */
    public static function Structure()
    {
        return [
            'AdvanceDirectiveObservation' => [

            ]
        ];
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
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId(
                        '2.16.840.1.113883.10.20.22.4.48',
                        $PortionData['observationDate']
                    ),
                    'id' => [
                        '@attributes' => [
                            'root' => Utilities::UUIDv4()
                        ]
                    ],
                    'code' => [
                        '@attributes' => [
                            'code' => $PortionData['didCode'],
                            'displayName' => $PortionData['didCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['didCodeSystemName']),
                            'codeSystemName' => $PortionData['didCodeSystemName']
                        ],
                        'originalText' => $PortionData['didText']
                    ],
                    'statusCode' => [
                        '@attributes' => [
                            'code' => $PortionData['statusCode']
                        ]
                    ],
                    'effectiveTime' => [
                        'low' => Component::time($PortionData['beginDate']),
                        'high' => Component::time($PortionData['endDate'])
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['resultCode'],
                            'displayName' => $PortionData['resultDisplayName'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['resultCodeSystemName']),
                            'codeSystemName' => $PortionData['resultCodeSystemName']
                        ],
                        'originalText' => $PortionData['resultText']
                    ],
                    'participant' => [
                        '@attributes' => [
                            'typeCode' => 'VRF'
                        ],
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.1.58'
                            ]
                        ],
                        'time' => [
                            'value' => Component::time($PortionData['observationDate'])
                        ],
                        'participantRole' => [
                            'id' => Utilities::UUIDv4(),
                            'code' => [
                                '@attributes' => [
                                    'code' => $PortionData['providerTaxonomyCode'],
                                    'codeSystem' => Utilities::CodingSystemId($PortionData['providerTaxonomySystem']),
                                    'codeSystemName' => $PortionData['providerTaxonomySystem'],
                                    'displayName' => $PortionData['providerTaxonomy']
                                ]
                            ],
                            'addr' => Component::addr(
                                $PortionData['address']['use'],
                                $PortionData['address']['streetAddressLine'],
                                $PortionData['address']['city'],
                                $PortionData['address']['state'],
                                $PortionData['address']['postalCode'],
                                $PortionData['address']['country']
                            ),
                            'telecom' => Component::telecom(
                                $PortionData['telecom']['use'],
                                $PortionData['telecom']['value']
                            )
                        ]
                    ]
                ]
            ];

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
