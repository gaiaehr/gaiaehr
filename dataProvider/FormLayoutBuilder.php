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

class FormLayoutBuilder {

	/**
	 * @var
	 */
	private $table;

	/**
	 * @var
	 */
	private $model;

	/**
	 * @var
	 */
	private $name;

	/**
	 * @var MatchaHelper
	 */
	private $db;

	/**
	 * @var MatchaCUP
	 */
	private $ff;

	/**
	 * Creates the MatchaHelper instance
	 */
	function __construct(){
		$this->db = new MatchaHelper();
		$this->conn = Matcha::getConn();
        if(!isset($this->ff))
            $this->ff = MatchaModel::setSenchaModel('App.model.administration.FormField');
	}

	/**
	 * @param stdClass $params
	 * @throws Exception
	 * @return array
	 */
	public function createFormField($params){

		try{
			if(is_array($params)){
				foreach($params AS $index => $record){
					$record = $this->createField($record);
					$params[$index]['id'] = $record['id'];
				}
			} else{
				$params = $this->createField($params);
			}
			return $params;

		} catch(Exception $e){
			return array('success' => false, 'message' => $e->getMessage());
		}

	}

	/**
	 * @param $params
	 * @return mixed
	 * @throws Exception
	 */
	public function createField($params){

		$data = get_object_vars($params);
		$this->getFormTable($data['form_id']);
		$this->getFormModel($data['form_id']);
		$this->name = $data['name'];

		/**
		 * lets defines what is a container for later use.
		 */
		$container = $data['xtype'] == 'fieldcontainer' || $data['xtype'] == 'fieldset';

		/**
		 * if getFieldDataCol returns true, means there is a column in the
		 * current database form table. in that case we need to return that
		 * error letting the user know there is a duplicated field or duplicated
		 * name property. The user has 2 options, verify the form to make sure
		 * the the field is not getting duplicated or change the name property
		 * to save the field data inside another column.
		 */
		if($this->fieldHasColumn() && $data['xtype'] != 'radiofield'){
			throw new Exception("Field '$this->name' exist, please verify the form or change the Field 'name' property");
		} else{

			/**
			 * add field to the sencha model if
			 * field is not a container and column doesn't exist
			 */
			if(!$container && !$this->fieldHasColumn()){
				if(!$this->addFieldModel($data['xtype'])){
					throw new Exception("Unable to modified '$this->model' sencha model");
				}
			}

			/**
			 * sanitized Data check the data array and if
			 * the value is empty delete it form the array
			 * then check the value and if is equal to "on"
			 * set it to true, and "off" set it to false
			 */
			$data = $this->sanitizedData($data);

			/**
			 * if not xtype fieldcontainer and fieldset the add some
			 * default values.
			 */

			$data = $this->setDefaults($data);
			/**
			 * now lets start creating the field in the database
			 */
			$field = array();
			$field['xtype'] = $data['xtype'];
			$field['form_id'] = intval($data['form_id']);
			$field['parentId'] = $data['parentId'];
			$field['x_index'] = $data['index'];

			unset($data['id'], $data['xtype'], $data['form_id'], $data['parentId'], $data['index'], $data['leaf']);

			/**
			 * Exec the new field sql statement and store the its ID
			 * in $field_id to then store its options
			 */
			$this->db->setSQL($this->db->sqlBind($field, 'forms_fields', 'I'));
			$this->db->execLog();
			$params->id = $this->db->lastInsertId;

			/**
			 * take each option and insert it in the forms_field_options
			 * table using $field_id
			 */
			if($params->id != 0){
				$this->setFieldOptions($data, $params->id, true);
			} else{
				throw new Exception("Unable to save '$this->name' field");
			}
		}

		return $params;
	}

	/**
	 * This function will delete the field and print success is no
	 * error were found along the way.
	 *
	 * @param stdClass $params
	 * @throws Exception
	 * @return array
	 */
	public function removeFormField($params){
		try{
			if(is_array($params)){
				foreach($params AS $record){
					$this->removeField($record);
				}
			} else{
				$this->removeField($params);
			}
			return $params;

		} catch(Exception $e){
			return array('success' => false, 'message' => $e->getMessage());
		}
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 * @throws Exception
	 */
	public function removeField(stdClass $params){

		$data = get_object_vars($params);
		$this->getFormTable($data['form_id']);
		$this->getFormModel($data['form_id']);
		$this->name = $data['name'];

		/**
		 * lets defines what is a container for later use.
		 */
		$container = $data['xtype'] == 'fieldcontainer' || $data['xtype'] == 'fieldset';

		/**
		 * check for all kind ao error combination and exit the
		 * script if error found. If not, then continue.
		 */
		if($container){

			/**
			 * for fieldcontainers and fieldsets lets make sure the
			 * field does NOT have child items
			 */
			if($this->fieldHasChild($data['id'])){
				throw new Exception('This field has one or more child field(s). Please, remove or moved the child fields before removing this field.');
			}

		} else{

			/**
			 * for all other fields lats check that the item has a
			 * column in the database and that the column is empty
			 * the user can NOT delete field with data in it.
			 */
			if(!$this->fieldHasColumn()){
				throw new Exception('This field does NOT have a column in the database.<br> This is very odd... please cotact Technical Support for help');
			} else{

				if($this->filedInUsed()){
					throw new Exception('Can NOT delete this field. This field already has data store in the database.');
				}
			}
		}

		/**
		 * If the field is NOT a container the remove database
		 * column for this field
		 */
		if(!$container && !$this->fieldHasBrother()){
			if(!$this->removeFieldModel()){
				throw new Exception("Unable to modified '$this->model' sencha model");
			}
		}

		/**
		 * remove field and field options
		 */
		$id = $data['id'];
		$this->db->setSQL("DELETE FROM `forms_fields` WHERE `id` ='$id'");
		$this->db->execOnly();
		$this->db->setSQL("DELETE FROM `forms_field_options` WHERE field_id='$id'");
		$this->db->execOnly();

		return $params;

	}

	/**
	 * @param $params
	 * @return array
	 */
	public function updateFormField($params){
		if(is_array($params)){
			foreach($params as $index => $field){
				$field = (object)$this->updateField($field);
				$params[$index]->id = $field->id;
			}
		} else{
			$params = $this->updateField($params);
		}
		return $params;
	}

	/**
	 * This function will update the fields and print
	 * the success callback if no errors found along the way
	 *
	 * @param stdClass $params
	 * @return array
	 */
	public function updateField(stdClass $params){
		try{
			$data = get_object_vars($params);
			$data = $this->sanitizedData($data);

			$this->getFormTable($data['form_id']);
			$this->getFormModel($data['form_id']);

			$field = array();
			$field['xtype'] = $data['xtype'];
			$field['form_id'] = intval($data['form_id']);
			$field['parentId'] = $data['parentId'];
			$field['x_index'] = $data['index'];

			$this->db->setSQL($this->db->sqlBind($field, 'forms_fields', 'U', array('id' => $params->id)));
			$this->db->execLog();

			unset($data['id'], $data['xtype'], $data['form_id'], $data['parentId'], $data['index'], $data['leaf']);

			$this->setFieldOptions($data, $params->id);
			return $params;

		} catch(Exception $e){
			return $e;
		}
	}

	/**
	 * @brief       Add a column to the form data table
	 * @details     Simple exec SQL Statement, with no Event LOG injection
	 *
	 * @author      Ernesto J Rodriguez (Certun) <erodriguez@certun.com>
	 * @version     Vega 1.0
	 * @copyright   Gnu Public License (GPLv3)
	 *
	 * @param       $xtype {string} field xtype
	 * @return      mixed
	 */
	private function addFieldModel($xtype){
		$type = ($xtype == 'checkbox' ? 'bool' : 'string');
		$array = array('model' => $this->model, 'field' => array('name' => $this->name, 'type' => $type));
		return MatchaModel::addFieldsToModel($array);
	}

	/**
	 * @brief       drop a column to the form data table
	 * @details     Simple exec SQL Statement, with no Event LOG injection
	 *
	 * @author      Ernesto J Rodriguez (Certun) <erodriguez@certun.com>
	 * @version     Vega 1.0
	 * @copyright   Gnu Public License (GPLv3)
	 *
	 * @return      mixed
	 */
	private function removeFieldModel(){
		$array = array('model' => $this->model, 'field' => array('name' => $this->name));
		MatchaModel::removeFieldsFromModel($array);
		return MatchaModel::removeFieldsFromModel($array);

	}

	/**
	 * @param $data
	 * @param $id
	 * @param $new
	 * @return mixed
	 */
	private function setFieldOptions($data, $id, $new = false){
		$json = json_encode($data, JSON_NUMERIC_CHECK);
		if($new){
			$options = array('field_id' => $id, 'options' => $json);
			$sql = $this->db->sqlBind($options, 'forms_field_options', 'I');
		} else{
			$options = array('options' => $json);
			$sql = $this->db->sqlBind($options, 'forms_field_options', 'U', array('field_id' => $id));
		}
		$this->db->setSQL($sql);
		$this->db->execOnly();
		return;
	}

	/**
	 * set defaults values (not in use)
	 *
	 * @param $data
	 * @return array
	 */
	private function setDefaults($data){

		return $data;
	}

	/**
	 * @param $form_id
	 * @return mixed
	 */
	private function getFormTable($form_id){
		$this->db->setSQL("SELECT `form_data` FROM `forms_layout` WHERE `id` = '$form_id'");
		$record = $this->db->fetchRecord();
		$this->table = $record['form_data'];
		return;
	}

	private function getFormModel($form_id){
		$this->db->setSQL("SELECT `model` FROM `forms_layout` WHERE `id` = '$form_id'");
		$model = $this->db->fetchRecord();
		$this->model = $model['model'];
		return;
	}

	/**
	 * @return bool
	 */
	private function fieldHasColumn(){
		$this->db->setSQL("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$this->table}' AND COLUMN_NAME = '$this->name'");
		$record = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		if(isset($record[0]['COLUMN_NAME'])){
			return true;
		} else{
			return false;
		}
	}

	/**
	 * @param $id
	 * @return bool
	 */
	private function fieldHasChild($id){
		$this->db->setSQL("SELECT `id` FROM `forms_fields` WHERE `parentId` ='$id'");
		$this->db->fetchRecords(PDO::FETCH_ASSOC);
		$count = $this->db->rowCount();
		if($count >= 1){
			return true;
		} else{
			return false;
		}
	}

	/**
	 * @return bool
	 */
	private function fieldHasBrother(){
		$this->db->setSQL("SELECT `id` FROM `forms_field_options` WHERE options LIKE '%\"name\":\"$this->name\"%'");
		$this->db->fetchRecords(PDO::FETCH_ASSOC);
		$count = $this->db->rowCount();
		if($count >= 2){
			return true;
		} else{
			return false;
		}
	}

	/**
	 * @return bool
	 */
	private function filedInUsed(){
		try{
			$this->db->setSQL("SELECT {$this->name} FROM {$this->table} WHERE {$this->name} IS NOT NULL");
			$ret = $this->db->fetchRecords(PDO::FETCH_ASSOC);
			if(isset($ret[0])){
				return true;
			} else{
				return false;
			}
		} catch(PDOException $e){
			return false;
		}

	}

	/**
	 * @param $data
	 * @return array
	 */
	private function sanitizedData($data){
		foreach($data as $option => $val){

			// remove null and empty values
			if($val === '' || $val === null)
				unset($data[$option]);

			// invert the require to allowBlank
			if($option === 'allowBlank'){
				if($val){
					$data[$option] = false;
				} else{
					unset($data[$option]);
				}
			}

			// parse the checkboxes values if any
			if($val === 'on'){
				$data[$option] = true;
			} elseif($val === 'off'){
				$data[$option] = false;
			}
		}
		return $data;
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function getForms($params){
		$form = MatchaModel::setSenchaModel('App.model.administration.FormsList');
		return $form->load($params)->all();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getParentFields(stdClass $params){
		$this->db->setSQL("Select ff.id, ff.xtype
                         FROM `forms_fields` AS ff
                    LEFT JOIN `forms_layout` AS fl
                           ON fl.id = ff.form_id
                        WHERE (fl.`name`  = '$params->currForm' OR fl.`id`    = '$params->currForm')
                          AND (ff.`xtype` = 'fieldcontainer'    OR ff.`xtype` = 'fieldset')
                     ORDER BY ff.`x_index`");
		$parentFields = array();
		array_push($parentFields, array('name' => 'Root', 'value' => 'root'));
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $parentField){
			$id = $parentField['id'];
			$this->db->setSQL("SELECT `options` FROM `forms_field_options` WHERE `field_id` = '$id'");
			$fo = $this->db->fetchRecord();
			$foo = json_decode($fo['options'], true);

			if(isset($foo['title'])){
				$row['name'] = $foo['title'] . ' (' . $parentField['xtype'] . ')';
			}elseif(isset($foo['fieldLabel'])){
				$row['name'] = $foo['fieldLabel'] . ' (' . $parentField['xtype'] . ')';
			}else{
				$row['name'] = '(' . $parentField['xtype'] . ')';
			}
			$row['value'] = $parentField['id'];
			array_push($parentFields, $row);
		}
		return $parentFields;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getFormFieldsTree(stdClass $params){
		$fields = array();
		if(isset($params->currForm)){
			$this->db->setSQL("Select * FROM `forms_fields` WHERE `form_id` = '$params->currForm' AND `parentId` = 'root' ORDER BY `x_index` ASC, `id` ASC");
			$results = $this->db->fetchRecords(PDO::FETCH_ASSOC);
			foreach($results as $item){
				$opts = $this->getItemsOptions($item['id']);
				foreach($opts as $opt => $val){
					$item[$opt] = $val;
				}
				$item['children'] = $this->getChildItems($item['id']);
				if($item['children'] == null){
					unset($item['children']);
					if($item['xtype'] != 'fieldset' && $item['xtype'] != 'fieldcontainer')
						$item['leaf'] = true;
				} else{
					if(isset($item['collapsed']) && $item['collapsed'] == 0){
						$item['expanded'] = true;
					} else{
						$item['expanded'] = false;
					}
				}
				array_push($fields, $item);
			}
		}
		return $fields;
	}

	/**
	 * @param $parentId
	 * @return array
	 */
	private function getChildItems($parentId){
		$items = array();
		$this->db->setSQL("Select * FROM `forms_fields` WHERE `parentId` = '$parentId' ORDER BY `x_index` ASC, `id` ASC");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $item){
			$opts = $this->getItemsOptions($item['id']);
			foreach($opts as $opt => $val){
				$item[$opt] = $val;
			}
			$item['children'] = $this->getChildItems($item['id']);
			if($item['children'] == null){
				unset($item['children']);
				if($item['xtype'] != 'fieldset' && $item['xtype'] != 'fieldcontainer')
					$item['leaf'] = true;
			} else{
				if(isset($item['collapsed']) && $item['collapsed'] == 0){
					$item['expanded'] = true;
				} else{
					$item['expanded'] = false;
				}
			}
			array_push($items, $item);
		}
		return $items;
	}

	/**
	 * @param $item_id
	 * @return array
	 */
	private function getItemsOptions($item_id){
		$foo = array();
		$this->db->setSQL("Select `options` FROM `forms_field_options` WHERE `field_id` = '$item_id'");
		$options = $this->db->fetchRecord();
		$options = json_decode($options['options'], true);
		foreach($options as $option => $value){

			if($option == 'allowBlank'){
				$value = ($value) ? false : true;
			}

			$foo[$option] = $value;
		}
		return $foo;
	}

	/* for manually remove all allowBlank form any table */
	public function removeAllowBlank($table){
		$this->db->setSQL("Select `id`, `options` FROM $table");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$options = json_decode($row['options'], true);
			if(isset($options['allowBlank'])){
				unset($options['allowBlank']);
				$id = $row['id'];
				$data['options'] = json_encode($options);
				$this->db->setSQL($this->db->sqlBind($data, $table, 'U', array('id' => $id)));
				$this->db->execOnly();
			}
		}
	}

}
