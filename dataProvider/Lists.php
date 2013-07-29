<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

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
        $rows = array();
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ComboListOptions');
		if(isset($params->list_id)){
			foreach($this->ComboListOptions->load(array('list_id'=>$params->list_id))->all() as $Options) array_push($rows, $Options);
		}
        return $rows;
	}

	public function addOption(stdClass $params)
	{
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ComboListOptions');
		$data = get_object_vars($params);
		unset($data['id'], $data['seq']);
        $this->ComboListOptions->save($data);
        $params->id = $this->ComboListOptions->lastInsertId();
		return $params;
	}

	public function updateOption(stdClass $params)
	{
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ComboListOptions');
		$data = get_object_vars($params);
		unset($data['id']);
        $this->ComboListOptions->save($data);
		return $params;
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
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ComboListOptions');
		$data = get_object_vars($params);
		$pos  = 10;
		foreach($data['fields'] as $field)
        {
			$row['seq'] = $pos;
            $this->ComboListOptions->save($row, array('id'=>$field) );
			$pos = $pos + 10;
		}
		return array('success' => true);
	}

	public function getLists()
	{
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.ComboList');
        if($this->FormFieldOptions == NULL) $this->FormFieldOptions = MatchaModel::setSenchaModel('App.model.administration.FormFieldOptions');
		$Combos = array();
		foreach($this->ComboList->load()->all() as $Combo)
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
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.ComboList');
		$data = get_object_vars($params);
		unset($data['id'], $data['in_use']);
        $this->ComboList->save($data);
        $params->id = $this->ComboList->lastInsertId();
		return $params;
	}

	public function updateList(stdClass $params)
	{
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.ComboList');
		$data = get_object_vars($params);
		unset($data['id'], $data['in_use']);
        $this->ComboList->save($data);
		return $params;
	}

	public function deleteList(stdClass $params)
	{
        if($this->FormFieldOptions == NULL) $this->FormFieldOptions = MatchaModel::setSenchaModel('App.model.administration.FormFieldOptions');
        if($this->ComboList == NULL) $this->ComboList = MatchaModel::setSenchaModel('App.model.administration.ComboList');
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ComboListOptions');
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
