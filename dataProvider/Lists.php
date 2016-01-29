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

class Lists
{

    /**
     * Data Objects
     */
    private $ComboList = NULL;
    private $ComboListOptions = NULL;
    private $FormFieldOptions = NULL;

    /**
	 * Main Sencha Model Getter and Setters
	 * @param stdClass $params
	 * @return mixed
     */
	public function getOptions(stdClass $params)
	{
        if(!isset($this->ComboListOptions))
            $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
        return $this->ComboListOptions->load($params)->all();
	}

	public function addOption(stdClass $params)
	{
        if(!isset($this->ComboListOptions))
			$this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		return $this->ComboListOptions->save($params);
	}

	public function updateOption(stdClass $params)
	{
        if(!isset($this->ComboListOptions))
            $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		return $this->ComboListOptions->save($params);
	}

	public function deleteOption(stdClass $params)
	{
		return array('success' => true);
	}

    /**
	 * Extra methods
	 * This methods are used by the view to gather extra data from the store or the model
	 * @param stdClass $params
	 * @return array
     */
	public function sortOptions(stdClass $params)
	{
        if(!isset($this->ComboListOptions))
            $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		$data = get_object_vars($params);
		$pos  = 10;
		foreach($data['fields'] as $field)
        {
	        $data = new stdClass();
	        $data->id = $field;
	        $data->seq = $pos;
            $this->ComboListOptions->save($data);
			$pos = $pos + 10;
		}
		return array('success' => true);
	}

	public function getLists($params)
	{
        if(!isset($this->ComboList))
			$this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
        if(!isset($this->FormFieldOptions))
            $this->FormFieldOptions = MatchaModel::setSenchaModel('App.model.administration.FormFieldOptions');
		$Combos = array();
		foreach($this->ComboList->load($params)->all() as $Combo)
        {
			$Combo['in_use'] = 0;
			foreach($this->FormFieldOptions->load()->all() as $Field)
            {
				if(strstr($Field['options'], '"list_id":'.$Combo['id']) !== false) $Combo['in_use']++;
			}
			$Combo['in_use'] = ($Combo['in_use'] == 0) ? 0 : 1;
			array_push($Combos, $Combo);
		}
		return $Combos;
	}

	public function addList(stdClass $params)
	{
		try
		{
            if(!isset($this->ComboList))
				$this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
			return $this->ComboList->save($params);
		}
		catch(Exception $ErrorObject)
		{
			return array('success' => false);
		}
	}

	/**
	 * updateList
	 * Method to update a master list record
	 * @param stdClass $params
	 * @return array|object
	 */
	public function updateList(stdClass $params)
	{
		try
		{
            if(!isset($this->ComboList))
				$this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
			return $this->ComboList->save($params);
		}
		catch(Exception $ErrorObject)
		{
			return array('success' => false);
		}
	}

	/**
	 * deleteList
	 * Delete a master list from the database, this method is called from the GaiaEHR Web Client
	 * @param stdClass $params
	 * @return array
	 */
	public function deleteList(stdClass $params)
	{
		try
		{
			// Check if the model is already loaded, if it is loaded, do not load it again.
            if(!isset($this->ComboList))
				$this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
            if(!isset($this->ComboListOptions))
				$this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
			$this->ComboListOptions->destroy($params);
			$this->ComboList->destroy($params);
			return array('success' => true);
		}
		catch(Exception $ErrorObject)
		{
			return array('success' => false);
		}
	}
}
