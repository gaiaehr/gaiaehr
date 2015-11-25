<?php

/**
 * 1.13.8	authenticator
 *
 */

namespace LevelDocument;

use Component;
use Utilities;
use Exception;

class authenticator
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
            $Section = array(
                'authenticator' => [
                ]
            );

            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}

