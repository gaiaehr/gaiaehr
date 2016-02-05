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

class ReportList
{

    public $AllowedMethods = [
        'getAvailableReports'
    ];

    /**
     * Get the available reports in the /report directory
     * this will include OpenSource ones and commercial ones.
     * All the mini-reports modules have to be installed in this
     * directory.
     */
    public function getAvailableReports()
    {
        try
        {
            $AvailableReports = array();
            $compileResults = array();
            $Index = 0;
            if ($handle = opendir('../modules/reportcenter/reports'))
            {
                while (false !== ($entry = readdir($handle)))
                {
                    if($entry == '.' || $entry == '..') continue;
                    if(is_dir("../modules/reportcenter/reports/$entry"))
                    {
                        $filePointer = "../modules/reportcenter/reports/$entry/reportSpec.json";
                        if(file_exists($filePointer) && is_readable($filePointer))
                        {
                            $fileContent = json_decode(file_get_contents($filePointer), true);
                            $AvailableReports[$Index]['id'] = $Index;
                            $AvailableReports[$Index] = $fileContent;
                            $Index++;
                        }
                        else
                        {
                            throw new \Exception("The Report Specification JSON file, can't be read or does not exist.");
                        }
                    }
                }
                closedir($handle);
            }
            else
            {
                throw new \Exception('Error: Reports directory not found.');
            }
            $compileResults['total'] = $Index;
            $compileResults['data'] = $AvailableReports;
            return $compileResults;
        }
        catch(\Exception $Error)
        {
            error_log(print_r($Error, true));
            return $Error;
        }
    }

}
