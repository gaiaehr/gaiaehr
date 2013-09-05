<?php
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


class MedlinePlus
{

    private $medlineUrl;
    private $codingSystem;

    function __construct()
    {
        /**
         * Pass the URL witch is ColdFusion Server
         */
        $this->medlineUrl = 'http://apps.nlm.nih.gov/medlineplus/services/mpconnect_service.cfm?';


    }

    function setCodingSystem($coding = 'ICD9')
    {
        if($coding == 'ICD9') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.103';
        if($coding == 'SNOMED') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.96';
        if($coding == 'RXCUI') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.88';
        if($coding == 'NDC') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.69';
        if($coding == 'LOINC') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.1';
    }

    /**
     * getResponse function
     * This will get a detailed description from MedlinePlus Connect
     * @param $coding
     * @param $code
     */
    function getResponse($coding, $code)
    {
        if($coding == 'ICD9') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.103';
        if($coding == 'SNOMED') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.96';
        if($coding == 'RXCUI') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.88';
        if($coding == 'NDC') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.69';
        if($coding == 'LOINC') $this->codingSystem = 'mainSearchCriteria.v.cs=2.16.840.1.113883.6.1';
        $urlBuilder = $this->medlineUrl . $this->codingSystem . '&mainSearchCriteria.v.c=' . $code;


    }

}