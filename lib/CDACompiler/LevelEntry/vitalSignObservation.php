<?php
/**
 * Class Vital Sign Observation
 * 5.65 Vital Sign Observation
 *
 *
 * Example:
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class VitalSignObservation
 * @package LevelEntry
 */
class vitalSignObservation {

    public function Validate($PortionData)
    {

    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData){

    }

    public static function Structure(){
        return [
            'VitalSignObservation' => [

            ]
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|Exception
     */
    public function insert($PortionData, $CompleteData)
    {
        try{
            // Compose the segment
            $Section = array(
                '@attributes' => array(
                    'classCode' => 'OBS',
                    'moodCode' => 'EVN'
                )
            );
            return $Section;
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }
}
