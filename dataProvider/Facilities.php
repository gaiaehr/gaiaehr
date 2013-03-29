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

class Facilities
{

    /**
     * Data Object
     */
    private $Facilities = null;

	function __construct()
	{
        if($this->Facilities == NULL) $this->Facilities = MatchaModel::setSenchaModel('App.model.administration.Facility');
		return;
	}

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
    public function getFacilities(stdClass $params)
	{
		$rows = array();
		foreach ($this->Facilities->load($params)->all() as $row)
		{
			if (strlen($row['pos_code']) <= 1) $row['pos_code'] = '0' . $row['pos_code'];
			array_push($rows, $row);
		}
		return $rows;
	}

	public function addFacility(stdClass $params)
	{
        $facility = $this->Facilities->save($params);
		$params->id = $facility['id'];
		return $params;
	}

	public function updateFacility(stdClass $params)
	{
		$this->Facilities->save($params);
		return $params;
	}

	public function deleteFacility(stdClass $params)
	{
		return $this->Facilities->destroy($params);
	}


    //------------------------------------------------------------------------------------------------------------------
    // Extra methods
    // This methods are used by the view to gather extra data from the store or the model
    //------------------------------------------------------------------------------------------------------------------
	public function getFacilityInfo($fid)
	{
        $resultRecord = $this->Facilities->load( array('id'=>$fid), array('name','phone','street','city','state','postal_code') )->one();
		return 'Facility: '.$resultRecord['name'].' '.$resultRecord['phone'].' '.$resultRecord['street'].' '.
            $resultRecord['city'].' '.$resultRecord['state'].' '.$resultRecord['postal_code'];
	}

	public function getActiveFacilities()
	{
		return $this->Facilities->load( array('active'=>'1') )->one();
	}

	public function getActiveFacilitiesById($facilityId)
	{
        return $this->Facilities->load( array('active'=>'1', 'id'=>$facilityId) )->one();
	}

	public function getBillingFacilities()
	{
        return $this->f->load( array('active'=>'1', 'billing_location'=>'1') )->one();
	}

}
