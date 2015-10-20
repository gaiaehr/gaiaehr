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

namespace modules\reportcenter\dataProvider;

include_once('../../../registry.php');
include_once('../../../classes/MatchaHelper.php');
require_once('../../../classes/Array2XML.php');

class ReportXML
{
    function __contruct($REQUEST)
    {
        if(!isset($REQUEST)) return;
    }

    function getSQLFile($reportDir)
    {
        $fo = new \MatchaHelper();

        $filePointer = "../reports/$reportDir/reportStatement.sql";
        if(file_exists($filePointer) && is_readable($filePointer))
        {
            return 'Got it!';
        }
    }
}