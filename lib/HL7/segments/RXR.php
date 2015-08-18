<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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
include_once (dirname(__FILE__).'/Segments.php');

class RXR extends Segments{

    function __destruct(){
        parent::__destruct();
    }

    /*
     * 4.14.2 RXR - Pharmacy/Treatment Route Segment
     * The Pharmacy/Treatment Route segment contains the alternative combination of route, site, administration
     * device, and administration method that are prescribed as they apply to a particular order. The pharmacy,
     * treatment staff and/or nursing staff has a choice between the routes based on either their professional
     * judgment or administration instructions provided by the physician.
     */
    function __construct($hl7)
    {
        parent::__construct($hl7);
        $this->rawSeg = array();
        $this->rawSeg[0] = 'RXR';

        /**
         * RXR-1 Route (CE) 00309
         * Components:
         * <Identifier (ST)> ^
         * <Text (ST)> ^
         * <Name of Coding System (ID)> ^
         * <Alternate Identifier (ST)> ^
         * <Alternate Text (ST)> ^
         * <Name of Alternate Coding System (ID)>
         *
         * Definition: This field is the route of administration.
         * Some current "route codes," such as some of the NDC-derived codes include the site already. In such
         * cases, the entire code can be included in this field as a "locally-defined code" for the CE data type.
         * Refer to User-Defined Table 0162 - Route Of Administration for valid values.
         */
        $this->rawSeg[1] = $this->getType('ST'); // Identifier
        $this->rawSeg[2] = $this->getType('ST'); // Text
        $this->rawSeg[3] = $this->getType('ID'); // Name of Coding System
        $this->rawSeg[4] = $this->getType('ST'); // Alternate Identifier
        $this->rawSeg[5] = $this->getType('ST'); // Alternate Text
        $this->rawSeg[6] = $this->getType('ID'); // Name of Alternate Coding System

        /**
         * RXR-2 Administration Site (CWE) 00310
         * Components:
         * <Identifier (ST)> ^
         * <Text (ST)> ^
         * <Name of Coding System (ID)> ^
         * <Alternate Identifier (ST)> ^
         * <Alternate Text (ST)> ^
         * <Name of Alternate Coding System (ID)> ^
         * <Coding System Version ID (ST)> ^
         * <Alternate Coding System Version ID (ST)> ^
         * <Original Text (ST)>
         *
         * Definition: This field contains the site of the administration route. When using a post-coordinated code
         * table in this field, RXR-6 Administration Site may be used to modify the meaning of this field.
         * Refer to HL7 Table 0550 – Body Parts for valid values. For backward compatibility, HL7 Table 0163 –
         * Body Site may also be employed. Other appropriate external code sets (e.g., SNOMED) may also be
         * employed.
         */
        $this->rawSeg[7] = $this->getType('ST'); // Identifier
        $this->rawSeg[8] = $this->getType('ST'); // Text
        $this->rawSeg[9] = $this->getType('ID'); // Name of Coding System
        $this->rawSeg[10] = $this->getType('ST'); // Alternate Identifier
        $this->rawSeg[11] = $this->getType('ST'); // Alternate Text
        $this->rawSeg[12] = $this->getType('ID'); // Name of Alternate Coding System
        $this->rawSeg[13] = $this->getType('ST'); // Coding System Version ID
        $this->rawSeg[14] = $this->getType('ST'); // Alternate Coding System Version ID
        $this->rawSeg[14] = $this->getType('ST'); // Original Text
    }

}