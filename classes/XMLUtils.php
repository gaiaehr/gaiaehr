<?
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

class XMLUtil
{

    private $xmlObject;

    /**
     * setXmlHeader function
     * This to configure XML document header
     * @param string $version
     * @param string $encode
     * @param string $root
     */
    public function setXmlHeader($version = '1.0', $encode = 'UTF-8', $root = 'root')
    {
        $this->xmlObject = new SimpleXMLElement("<?xml version=\"$version\" encoding=\"$encode\"?><$root></$root>");
    }

    /**
     * array2xml function
     * Used to pass an array and then convert it to an XML
     * @param $arrayData
     * @return mixed
     */
    public function array2xml($arrayData)
    {
        foreach($arrayData as $key => $value)
        {
            if(is_array($value))
            {
                if(!is_numeric($key))
                {
                    $subnode = $this->xmlObject->addChild("$key");
                    $this->array2xml($value, $subnode);
                }
                else
                {
                    $subnode = $this->xmlObject->addChild("item$key");
                    $this->array2xml($value, $subnode);
                }
            }
            else
            {
                $this->xmlObject->addChild("$key","$value");
            }
        }
        return $this->xmlObject->asXML();
    }

}