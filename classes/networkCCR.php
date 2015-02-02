<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once('../lib/Matcha/Matcha.php');

class networkCCR
{

    private static $__hostURL;
    private static $__xmlData;

    /**
     * @param $host
     */
    static public function setHost($host)
    {
        self::$__hostURL = $host;
    }

    /**
     * @param $data
     */
    static public function setXMLData($data)
    {
        self::$__xmlData = $data;
    }

    /**
     * @param $file
     * @return bool
     */
    static public function loadXMLData($file)
    {
        try
        {
            self::setXMLData(file_get_contents($file));
            return true;
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    /**
     * @return bool
     */
    static public function transmitCCR()
    {
        try
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::$__hostURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, self::$__xmlData);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
            curl_exec($ch);
            return true;
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    /**
     * @return bool|mixed
     */
    static public function receiveCCR()
    {
        try
        {
            return file_get_contents('php://input');
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

}