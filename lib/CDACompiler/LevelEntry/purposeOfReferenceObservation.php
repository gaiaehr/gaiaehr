<?php

/**
 * 3.89	Purpose of Reference Observation
 *
 * A Purpose of Reference Observation describes the purpose of the DICOM composite object reference.
 * Appropriate codes, such as externally defined DICOM codes, may be used to specify the semantics of the
 * purpose of reference. When this observation is absent, it implies that the reason for the reference is unknown.
 *
 * Contains:
 *
 */

namespace LevelEntry;

use LevelOther;
use LevelDocument;
use Component;
use Utilities;
use Exception;

/**
 * Class purposeOfReferenceObservation
 * @package LevelEntry
 */
class purposeOfReferenceObservation
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['code']))
            throw new Exception('SHOULD be selected from ValueSet DICOMPurposeOfReference');
        if(!isset($PortionData['codeSystemName']))
            throw new Exception('SHOULD be selected from ValueSet DICOMPurposeOfReference');
        if(!isset($PortionData['displayName']) < 1)
            throw new Exception('SHOULD be selected from ValueSet DICOMPurposeOfReference');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {
        return $PortionData['Narrated'];
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'PurposeOfReferenceObservation' => [
                'code' => 'SHOULD be selected from ValueSet DICOMPurposeOfReference',
                'codeSystemName' => 'SHOULD be selected from ValueSet DICOMPurposeOfReference',
                'displayName' => 'SHOULD be selected from ValueSet DICOMPurposeOfReference'
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
        try {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'observation' => [
                    '@attributes' => [
                        'classCode' => 'OBS',
                        'moodCode' => 'EVN'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.9'),
                    'code' => [
                        '@attributes' => [
                            'code' => 'ASSERTION',
                            'codeSystem' => '2.16.840.1.113883.5.4'
                        ]
                    ],
                    'value' => [
                        '@attributes' => [
                            'xsi:type' => 'CD',
                            'code' => $PortionData['code'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['codeSystemName']),
                            'displayName' => $PortionData['codeSystemName'],
                            'codeSystemName' => $PortionData['displayName']
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
