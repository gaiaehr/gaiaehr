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


    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
	public function getOptions(stdClass $params)
	{
        $rows = array();
        if($this->ComboListOptions == NULL) $this->ComboListOptions = MatchaModel::setSenchaModel('App.model.administration.ComboListOptions');
        foreach($this->ComboListOptions->load(array('list_id'=>$params->list_id))->all() as $Options) array_push($rows, $Options);
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

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function sortOptions(stdClass $params)
	{
		$data = get_object_vars($params);
		$pos  = 10;
		foreach($data['fields'] as $field){
			$row['seq'] = $pos;
			$sql        = $this->sqlBind($row, "combo_lists_options", "U", "id = '" . $field . "'");
			$this->setSQL($sql);
			$this->execLog();
			$pos = $pos + 10;
		}
		return array('success' => true);
	}

	public function getLists()
	{
		$combos = array();
		$this->setSQL("SELECT options FROM forms_field_options");
		$forms_fields = $this->fetchRecords(PDO::FETCH_ASSOC);
		$this->setSQL("SELECT * FROM combo_lists ORDER BY title");
		foreach($this->fetchRecords(PDO::FETCH_ASSOC) as $combo){
			$combo['in_use'] = 0;
			foreach($forms_fields as $field){
				if(strstr($field['options'], '"list_id":'.$combo['id']) !== false) $combo['in_use']++;
			}
			$combo['in_use'] = ($combo['in_use'] == 0) ? 0 : 1;
			array_push($combos, $combo);
		}
		return $combos;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function addList(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id'], $data['in_use']);
		$sql = $this->sqlBind($data, 'combo_lists', 'I');
		$this->setSQL($sql);
		$this->execLog();
		$params->id = $this->lastInsertId;
		return $params;
	}

	public function updateList(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id'], $data['in_use']);
		$sql = $this->sqlBind($data, 'combo_lists', 'U', array('id' => $params->id));
		$this->setSQL($sql);
		$this->execLog();
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return array|stdClass
	 */
	public function deleteList(stdClass $params)
	{
		$this->setSQL("SELECT count(*)
                         FROM forms_field_options
                        WHERE oname = 'list_id'
                          AND ovalue = '$params->id'");
		$rec = $this->fetchRecord();
		if($rec['count(*)'] == 0){
			$this->setSQL("DELETE FROM combo_lists_options WHERE list_id = '$params->id'");
			$this->execLog();
			$this->setSQL("DELETE FROM combo_lists WHERE id = '$params->id'");
			$this->execLog();
			return array('success' => true);
		} else {
			return array('success' => false);
		}
	}
}

//$l = new Lists();
//echo '<pre>';
//print_r($l->getLists());
//$l->getLists();
