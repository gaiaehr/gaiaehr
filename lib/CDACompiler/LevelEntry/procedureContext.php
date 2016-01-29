<?php

/**
 * 3.84	Procedure Context
 *
 * The ServiceEvent Procedure Context of the document header may be overridden in the CDA structured body if
 * there is a need to refer to multiple imaging procedures or acts. The selection of the Procedure or Act
 * entry from the clinical statement choice box depends on the nature of the imaging service that has been performed.
 * The Procedure entry shall be used for image-guided interventions and minimal invasive imaging services,
 * whereas the Act entry shall be used for diagnostic imaging services.
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
 * Class procedureActivityProcedure
 * @package LevelEntry
 */
class procedureContext
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['procedureCode']))
            throw new Exception('SHALL be represented with the procedure or act elements depending on the nature of the procedure');
        if(!isset($PortionData['procedureCodeSystemName']))
            throw new Exception('SHALL be represented with the procedure or act elements depending on the nature of the procedure');
        if(!isset($PortionData['procedureDisplayName']))
            throw new Exception('SHALL be represented with the procedure or act elements depending on the nature of the procedure');
        if(!isset($PortionData['effectiveTime']))
            throw new Exception('SHALL be represented with the procedure or act elements depending on the nature of the procedure');
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
            'ProcedureContext' => [
                'procedureCode' => 'SHALL be represented with the procedure or act elements depending on the nature of the procedure',
                'procedureCodeSystemName' => 'SHALL be represented with the procedure or act elements depending on the nature of the procedure',
                'procedureDisplayName' => 'SHALL be represented with the procedure or act elements depending on the nature of the procedure',
                'effectiveTime' => 'SHALL be represented with the procedure or act elements depending on the nature of the procedure'
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
                'act' => [
                    '@attributes' => [
                        'moodCode' => 'EVN',
                        'classCode' => 'ACT'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.5'),
                    'code' => [
                        'code' => $PortionData['procedureCode'],
                        'codeSystem' => Utilities::CodingSystemId($PortionData['procedureCodeSystemName']),
                        'displayName' => $PortionData['procedureDisplayName'],
                        'codeSystemName' => $PortionData['procedureCodeSystemName']
                    ],
                    'effectiveTime' => Component::effectiveTime($PortionData['effectiveTime'])
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
