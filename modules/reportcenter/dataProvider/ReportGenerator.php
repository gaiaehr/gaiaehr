<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2015 TRA NextGen, Inc.
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

require_once('../../../lib/tcpdf/tcpdf.php');

class ReportGenerator
{

    private $request;
    private $reportDir;

    function setRequest($REQUEST)
    {
        if(!isset($REQUEST)) return;
        $this->request = json_decode($REQUEST['params'], true);
        $this->reportDir = $this->request['reportDir'];
    }

    function getXSLDocument()
    {
        try
        {
            $filePointer = "../reports/$this->reportDir/report.xsl";
            if(file_exists($filePointer) && is_readable($filePointer))
            {
                $fileContent = file_get_contents($filePointer);
                return $fileContent;
            }
            else
            {
                throw new Exception("Could not read the XSL file or the file does not exist.");
            }
        }
        catch(Exception $Error)
        {
            return $Error;
        }
    }
}

/**
 * This will combile the XML and the XSL
 */
header('Content-Type: application/xslt+xml');

$rg = new ReportGenerator();
$rg->setRequest($_REQUEST);
echo $rg->getXSLDocument();

