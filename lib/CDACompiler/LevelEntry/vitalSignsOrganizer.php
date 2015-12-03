<?php

/**
 * Class Vital Signs Organizer
 * 5.66 Vital Signs Organizer
 *
 * The Vital Signs Organizer groups vital signs, which is similar to the Result Organizer,
 * but with further constraints.
 *
 * An appropriate nullFlavor can be used when a single result observation is contained in
 * the organizer, and organizer/code or organizer/id is unknown.
 *
 * Example:
 *   <organizer classCode="CLUSTER" moodCode="EVN">
 *      <templateId root="2.16.840.1.113883.10.20.22.4.26"/>
 *      <!-- Vital signs organizer template -->
 *      <id root="c6f88320-67ad-11db-bd13-0800200c9a66"/>
 *      <code code="46680005" codeSystem="2.16.840.1.113883.6.96" codeSystemName="SNOMED CT" displayName="Vital signs"/>
 *      <statusCode code="completed"/>
 *      <effectiveTime value="19991114"/>
 *      <component>
 *          <observation classCode="OBS" moodCode="EVN">
 *              <templateId root="2.16.840.1.113883.10.20.22.4.27"/>
 *              ...
 *          </observation>
 *      </component>
 *  </observation>
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class VitalSignsOrganizer
 * @package LevelEntry
 */
class vitalSignsOrganizer {

    /**
     * @param $Data
     * @throws Exception
     */
    public static function Validate($Data)
    {
        if(!isset($Data['VitalSign_Status']))
            throw new Exception('VitalSign_Status: Shall be declared.', '5.66_1');
        if(!isset($Data['VitalSign_DateTime']))
            throw new Exception('VitalSign_DateTime: Shall be declared.', '5.66_2');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData){

    }

    public static function Structure(){
        return [
            'VitalSignsOrganizer' => [

            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|\Exception|Exception
     */
    public static function insert($PortionData, $CompleteData)
    {
        try{
            // Validate first
            self::Validate($PortionData);

            // Compose the segment
            $Section['organizer'] = [
                '@attributes' => [
                    'classCode' => 'CLUSTER',
                    'moodCode' => 'EVN'
                ],
                'templateId' => [
                    '@attributes' => [
                        'root' => '2.16.840.1.113883.10.20.22.4.26'
                    ]
                ],
                'id' => Component::id(Utilities::UUIDv4()),
                'code' => [
                    '@attributes' => [
                        'code' => '46680005',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'codeSystemName' => 'SNOMED CT',
                        'displayName' => 'Vital signs'
                    ]
                ],
                'statusCode' => [
                    '@attributes' => [
                        'code' => $PortionData['VitalSigns']['code']
                    ]
                ],
                'effectiveTime' => [
                    '@attributes' => [
                        'value' => $PortionData['VitalSigns']['date']
                    ]
                ]
            ];
            return $Section;
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }

}
