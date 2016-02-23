<?php
/**
 *
 * Matcha::connect
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


class MatchaLog extends Matcha
{

    /**
     * Log Path
     * @var
     */
    private $__logPath;

    /**
     * Log File
     * @var
     */
    private $__logFile;

    /**
     * MatchaLog constructor.
     * On contact set the log path and file. If the path is not set
     * it will log on tmp/log of the sites root path
     */
    function __construct()
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        $oldUmask = umask(0);
        clearstatcache();
        // Check the directory first.
        if(!file_exists($this->__logPath)) mkdir($this->__logPath, 0775, true);
        // Check the log file.
        if(!file_exists($this->__logPath.$this->__logFile))
        {
            touch($this->__logPath.$this->__logFile);
            chmod($this->__logPath.$this->__logFile, 0775);
        }
        if(is_writable($this->__logPath.$this->__logFile)) ini_set('error_log', $this->__logPath.$this->__logFile);
        umask($oldUmask);
    }

    function __logRotate()
    {

    }

    function setPath($logFile)
    {
        $this->__logPath = $logFile;
    }

    function getPath()
    {
        if($this->__logPath)
        {
            return $this->__logPath = '/tmp';
        }
        else
        {
            return $this->__logPath;
        }
    }
}
