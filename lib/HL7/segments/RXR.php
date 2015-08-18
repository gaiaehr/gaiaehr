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
        parent::__construct($hl7, 'RXR');

        /**
         * RXR-1 Route (CE) 00309
         *
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
        $this->setField(1, 'ST', 250, true); // Identifier
        $this->setField(2, 'ST', 250, true); // Text
        $this->setField(3, 'ID', 250, true); // Name of Coding System
        $this->setField(4, 'ST', 250, false); // Alternate Identifier
        $this->setField(5, 'ST', 250, false); // Alternate Text
        $this->setField(6, 'ID', 250, false); // Name of Alternate Coding System

        /**
         * RXR-2 Administration Site (CWE) 00310
         *
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
        $this->setField(7, 'ST', 250, false); // Identifier
        $this->setField(8, 'ST', 250, false); // Text
        $this->setField(9, 'ID', 250, false); // Name of Coding System
        $this->setField(10, 'ST', 250, false); // Alternate Identifier
        $this->setField(11, 'ST', 250, false); // Alternate Text
        $this->setField(12, 'ID', 250, false); // Name of Alternate Coding System
        $this->setField(13, 'ST', 250, false); // Coding System Version ID
        $this->setField(14, 'ST', 250, false); // Alternate Coding System Version ID
        $this->setField(15, 'ST', 250, false); // Original Text

        /**
         * RXR-3 Administration Device (CE) 00311
         *
         * Components:
         * <Identifier (ST)> ^
         * <Text (ST)> ^
         * <Name of Coding System (ID)> ^
         * <Alternate Identifier (ST)> ^
         * <Alternate Text (ST)> ^
         * <Name of Alternate Coding System (ID)>
         *
         * Definition: This field contains the mechanical device used to aid in the administration of the drug or other
         * treatment. Common examples are IV-sets of different types. Refer to User-defined Table 0164 -
         * Administration device for valid entries.
         */
        $this->setField(16, 'ST', 250, false); // Identifier
        $this->setField(17, 'ST', 250, false); // Text
        $this->setField(18, 'ID', 250, false); // Name of Coding System
        $this->setField(19, 'ST', 250, false); // Alternate Identifier
        $this->setField(20, 'ST', 250, false); // Alternate Text
        $this->setField(21, 'ID', 250, false); // Name of Alternate Coding System

        /**
         * RXR-4 Administration Method (CWE) 00312
         *
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
         * Definition: This field identifies the specific method requested for the administration of the drug or
         * treatment to the patient. Refer To User-defined Table 0165 – Administration Method for valid values.
         */
        $this->setField(22, 'ST', 250, false); // Identifier
        $this->setField(23, 'ST', 250, false); // Text
        $this->setField(24, 'ID', 250, false); // Name of Coding System
        $this->setField(25, 'ST', 250, false); // Alternate Identifier
        $this->setField(26, 'ST', 250, false); // Alternate Text
        $this->setField(27, 'ID', 250, false); // Name of Alternate Coding System
        $this->setField(28, 'ST', 250, false); // Coding System Version ID
        $this->setField(29, 'ST', 250, false); // Alternate Coding System Version ID
        $this->setField(30, 'ST', 250, false); // Original Text

        /**
         * 4.14.2.5 RXR-5 Routing Instruction (CE) 01315
         *
         * Components:
         * <Identifier (ST)> ^
         * <Text (ST)> ^
         * <Name of Coding System (ID)> ^
         * <Alternate Identifier (ST)> ^
         * <Alternate Text (ST)> ^
         * <Name of Alternate Coding System (ID)>
         *
         * Definition: This field provides instruction on administration routing, especially in cases where more than
         * one route of administration is possible. A typical case would be designating which IV line should be used
         * when more than one IV line is a possible route for injection.
         */
        $this->setField(31, 'ST', 250); // Identifier
        $this->setField(32, 'ST', 250); // Text
        $this->setField(33, 'ID', 250); // Name of Coding System
        $this->setField(34, 'ST', 250); // Alternate Identifier
        $this->setField(35, 'ST', 250); // Alternate Text
        $this->setField(36, 'ID', 250); // Name of Alternate Coding System
    }


}