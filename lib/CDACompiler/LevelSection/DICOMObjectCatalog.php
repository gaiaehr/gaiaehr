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
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(count($PortionData['StudyAct'])<0)
            throw new Exception('SHALL contain exactly one [1..1] Study Act');

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {

    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'DICOMObjectCatalog' => [
                LevelEntry\studyAct::Structure()
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

            // MAY contain zero or more [1..*] entry
            // SHALL contain exactly one [1..1] Study Act
            if(count($PortionData['StudyAct']) > 0)
            {
                foreach ($PortionData['StudyAct'] as $StudyAct)
                {
                    $Section['component']['section']['entry'][] = LevelEntry\studyAct::Insert(
                        $StudyAct,
                        $CompleteData
                    );
                }
            }

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
