<?php

/**
 * 2.12	DICOM Object Catalog Section - DCM 121181
 *
 * DICOM Object Catalog lists all referenced objects and their parent Series and Studies, plus other DICOM
 * attributes required for retrieving the objects.
 *
 * DICOM Object Catalog sections are not intended for viewing and contain empty section text.
 *
 * Contains:
 * Study Act
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class DICOMObjectCatalog
{
    /**
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
    }

    /**
     * Build the Narrative part of this section
     * @param $Data
     */
    public static function Narrative($Data)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'DICOMObjectCatalog' => [

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

            $Section = [
                'component' => [
                    'section' => [
                        '@attributes' => [
                            'classCode' => 'DOCSECT',
                            'moodCode' => 'EVN'
                        ],
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.6.1.1'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '121181',
                                'displayName' => 'DICOM Object Catalog',
                                'codeSystem' => '1.2.840.10008.2.16.4',
                                'codeSystemName' => 'DCM'
                            ]
                        ]
                    ]
                ]
            ];

            // Study Act [1..*]
            foreach($PortionData['DICOMObjectCatalog'] as $Catalog) {
                $Section['component']['section']['entry'][] = [
                    'act' => LevelEntry\studyAct::Insert($Catalog, $CompleteData)
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
