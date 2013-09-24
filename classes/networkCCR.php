<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

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

class sndCCR
{

    private $hostURL;
    private $xmlData;

    /**
     * @param $host
     */
    static public function setHost($host)
    {
        self::$hostURL = $host;
    }

    /**
     * @param $data
     */
    static public function setXMLData($data)
    {
        self::$xmlData = $data;
    }

    /**
     * @param $file
     * @return bool
     */
    static public function loadXMLData($file)
    {
        try
        {
            self::$xmlData = file_get_contents($file);
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
            curl_setopt($ch, CURLOPT_URL, self::$hostURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, self::$xmlData);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
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
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            return curl_exec($ch);
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

}