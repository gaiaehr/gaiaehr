<?php

/**
 * 1.3.2	documentationOf
 *
 * The documentationOf relationship in a Continuity Care Document contains the representation of providers who are
 * wholly or partially responsible for the safety and well-being of a subject of care.
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

/**
 * Class documentationOf
 * @package LevelDocument
 */
class documentationOf
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
                'documentationOf' => [
                    'serviceEvent' => [
                        '@attributes' => [
                            'classCode' => 'PCPR'
                        ]
                    ],
                    'effectiveTime' => [
                        'low' => [
                            '@attributes' => [ 'value' => $Data['Patient']['dateOfBirth'] ]
                        ],
                        'high' => [
                            '@attributes' => [ 'value' => date('Ymd') ]
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

