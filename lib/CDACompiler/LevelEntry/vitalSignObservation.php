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
class VitalSignObservation {

    public function Validate($Data)
    {

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
