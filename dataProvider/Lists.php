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

class Lists extends MatchaHelper
{

    /**
     * Data Objects
     */
    private $ComboList = NULL;
    private $ComboListOptions = NULL;
    private $FormFieldOptions = NULL;

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
	public function getOptions(stdClass $params)
	{
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
        return $this->ComboListOptions->load($params)->all();
	}

	public function addOption(stdClass $params)
	{
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		return $this->ComboListOptions->save($params);
	}

	public function updateOption(stdClass $params)
	{
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		return $this->ComboListOptions->save($params);
	}

	public function deleteOption(stdClass $params)
	{
		return array('success' => true);
	}

    //------------------------------------------------------------------------------------------------------------------
    // Extra methods
    // This methods are used by the view to gather extra data from the store or the model
    //------------------------------------------------------------------------------------------------------------------
	public function sortOptions(stdClass $params)
	{
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
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
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
        if($this->FormFieldOptions == NULL) $this->FormFieldOptions = MatchaModel::setSenchaModel('App.model.administration.FormFieldOptions');
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
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
		return $this->ComboList->save($params);
	}

	public function updateList(stdClass $params)
	{
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
		return $this->ComboList->save($params);
	}

	public function deleteList(stdClass $params)
	{
        if($this->FormFieldOptions == NULL) $this->FormFieldOptions = MatchaModel::setSenchaModel('App.model.administration.FormFieldOptions');
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.Lists');
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		if($this->FormFieldOptions->load(array('oname'=>'list_id', 'ovalue'=>$params->id))->rowCount() == 0)
        {
            $this->ComboListOptions->destroy($params);
            $this->ComboList->destroy($params);
			return array('success' => true);
		}
        else
        {
			return array('success' => false);
		}
	}
}
