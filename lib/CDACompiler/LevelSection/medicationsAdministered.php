<?php

/**
 * 2.43	Medications Administered Section (V2)
 *
 * The Medications Administered Section contains medications and fluids administered during a procedure.
 * The section may also contain the procedure's encounter or other activity, excluding anesthetic medications.
 * This section is not intended for ongoing medications and medication history.

 *
 * Contains:
 * Medication Activity (V2)
 *
 */

namespace LevelSection;

use LevelEntry;
use Exception;

class medicationsAdministered
{

    /**
     * @param $Data
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
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.2.38.2',
                                'extension' => $PortionData['MedicationsAdministered']['date']
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '29549-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Medications Administered'
                            ]
                        ],
                        'title' => 'Medications Administered',
                        'text' => self::Narrative($PortionData)
                    ]
                ]
            ];

            // 3.51	Medication Activity (V2) [0..*]
            foreach($PortionData['MedicationsAdministered']['Activity'] as $Activity) {
                $Section['component']['section']['entry'][] = [
                    '@attributes' => [
                        'typeCode' => 'DRIV'
                    ],
                    'act' => LevelEntry\medicationsActivity::Insert($Activity, $CompleteData)
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
