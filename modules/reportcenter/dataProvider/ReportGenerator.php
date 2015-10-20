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

class ReportGenerator
{

    private $request;
    private $reportDir;
    private $conn;
    private $site;

    function __construct($site = 'default')
    {
        $this->site = $site;
        if(!defined('_GaiaEXEC')) define('_GaiaEXEC', 1);
        require_once('../../../registry.php');
        require_once("../../../sites/$this->site/conf.php");
        require_once('../../../classes/MatchaHelper.php');

        require_once('../../../lib/tcpdf/tcpdf.php');
        require_once('../../../classes/Array2XML.php');
    }

    function setRequest($REQUEST)
    {
        if(!isset($REQUEST)) return;
        $this->request = json_decode($REQUEST['params'], true);
        $this->reportDir = $this->request['reportDir'];
        unset($this->request['reportDir']);
    }

    function getXSLTemplate()
    {
        try
        {
            $filePointer = "../reports/$this->reportDir/report.xsl";
            if(file_exists($filePointer) && is_readable($filePointer))
            {
                return file_get_contents($filePointer);
            }
            else
            {
                throw new Exception('Error: Not XSLT file was found or is readable.');
            }
        }
        catch(Exception $Error)
        {
            error_log(print_r($Error,true));
            return $Error;
        }
    }

    function getXMLDocument()
    {
        try
        {
            $this->conn = Matcha::getConn();
            $filePointer = "../reports/$this->reportDir/reportStatement.sql";

            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

            if(file_exists($filePointer) && is_readable($filePointer))
            {
                // Get the SQL content
                $fileContent = file_get_contents($filePointer);
                $RunSQL = $this->conn->prepare($fileContent);

                // Copy all the request variables into the ExecuteValues
                foreach($this->request as $field)
                {
                    $PrepareField[':'.$field['name']] = $field['value'];
                }

                $RunSQL->execute($PrepareField);
                $records = $RunSQL->fetchAll(PDO::FETCH_ASSOC);$ExtraAttributes['xml-stylesheet'] = 'type="text/xsl" href="http://localhost/gaiaehr/modules/reportcenter/reports/'.$this->reportDir.'/report.xsl"';
                Array2XML::init('1.0', 'UTF-8', true,$ExtraAttributes);
                $xml = Array2XML::createXML('records', array('record' => $records));
                return $xml->saveXML();
            }
            else
            {
                throw new Exception('Error: Not SQL Statement file was found or is readable.');
            }
        }
        catch(Exception $Error)
        {
            error_log(print_r($Error,true));
            return $Error;
        }
    }
}

/**
 * This will combine the XML and the XSL
 */
header('Content-Type: application/xslt+xml');
$rg = new ReportGenerator();
$rg->setRequest($_REQUEST);

$xslt = new XSLTProcessor();
$xslt->importStylesheet(new SimpleXMLElement($rg->getXSLTemplate()));
echo $xslt->transformToXml(new SimpleXMLElement($rg->getXMLDocument()));
