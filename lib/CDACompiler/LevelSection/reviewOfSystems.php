<?php

/**
 * 2.75	Review of Systems Section
 *
 * The Review of Systems section contains a relevant collection of symptoms and functions systematically
 * gathered by a clinician. It includes symptoms the patient is currently experiencing, some of which were not
 * elicited during the history of present illness, as well as a potentially large number of pertinent negatives,
 * for example, symptoms that the patient denied experiencing.
 *
 * Contains:
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class reviewOfSystems
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
            'ReviewOfSystems' => [

            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try
        {
            // Validate first
            self::Validate($Data['ReviewOfSystems']);

            $Section = [
                'component' => [
                    'section' => [
                        'templateId' => [
                            '@attributes' => [
                                'root' => '1.3.6.1.4.1.19376.1.5.3.1.3.18'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '10187-3',
                                'displayName' => 'Review Of Systems',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC'
                            ]
                        ],
                        'title' => 'Review Of Systems',
                        'text' => self::Narrative($Data['ReviewOfSystems'])
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
