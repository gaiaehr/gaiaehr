<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

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

class OfficeNotes extends MatchaHelper
{
    /**
     * Data Object
     */
    private $OfficeNotes = NULL;

    function __construct()
    {
        if($this->OfficeNotes == NULL) $this->OfficeNotes = MatchaModel::setSenchaModel('App.model.miscellaneous.OfficeNotes');
        return;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
    public function getOfficeNotes(stdClass $params)
	{
        $Where = (isset($params -> show)) ? array('activity'=>1) : '';
		return $this->OfficeNotes->load($Where)->all();
	}

	public function addOfficeNotes(stdClass $params)
	{
		$params->user = $_SESSION['user']['name'];
		$params->date = date('Y-m-d H:i:s');
		$params->activity = 1;
        $this->OfficeNotes->save($params);
		return $params;
	}

	public function updateOfficeNotes(stdClass $params)
	{
        $this->OfficeNotes->save($params);
		return $params;
	}

}
