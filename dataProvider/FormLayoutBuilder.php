<?php
/* 
 * layoutEngine.class.php
 * 
 * @DESCRIPTION@: This class object will create dynamic ExtJS v4 form, previously created or edited
 * from the Layout Form Editor. Gathering all it's data and parameters from the layout_options table. 
 * Most of the structural database table was originally created by OpenEMR developers.
 * 
 * What this class will not do: This class will not create the entire Screen Panel for you, this
 * will only create the form object with the fields names & dataStores configured on the layout_options table.
 * 
 * version: 0.1.0
 * author: GI Technologies, 2011
 * modified: Ernesto J Rodriguez
 * 
 */
if(!isset($_SESSION)){
    session_name ("GaiaEHR" );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['root'].'/classes/dbHelper.php');
class FormLayoutBuilder {

    /**
     * @var
     */
    private $form_data_table;
    /**
     * @var
     */
    private $col;
    /**
     * @var dbHelper
     */
    private $db;
    /**
     * Creates the dbHelper instance
     */
    function __construct(){
        $this->db = new dbHelper();
        return;
    }
    /**
     * @param stdClass $params
     * @return array
     */
    public function createFormField(stdClass $params){

        $data = get_object_vars($params);
        $this->getFormDataTable($data['form_id']);
        $this->col  = $data['name'];
        $container  = false;
        /**
         * lets defines what is a container for later use.
         */
        if( $data['xtype'] == 'fieldcontainer' || $data['xtype'] == 'fieldset' ) $container = true;
        /**
         * if getFieldDataCol returns true, means there is a column in the
         * current database form table. in that case we need to return that
         * error letting the user know there is a duplicated field or duplicated
         * name property. The user has 2 options, verify the form to make sure
         * the the field is not getting duplicated or change the name property
         * to save the field data inside another column.
         */
        if($this->fieldHasColumn() && $data['xtype'] != 'radiofield') {
            return array('success' => false, 'error'=> 'Field \"'.$this->col.'\" exist, please verify the form or change the Field \"name\" preoperty');
        }else{
            /**
             * since now we know the column doesn't exist, lets create one for the new field
             */
            if(!$container){

                if(!$this->fieldHasColumn()){
                    $this->addColumn('TEXT');
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
            $field              = array();
            $field['form_id']   = $data['form_id'];
            $field['xtype']     = $data['xtype'];

	        $field              = array();
		    $field['xtype']     = $data['xtype'];
	        $field['form_id']   = intval($data['form_id']);
	        $field['parentId']  = $data['parentId'];
	        $field['pos']       = intval($data['pos']);

	        unset($data['id'],$data['xtype'],$data['form_id'],$data['parentId'],$data['pos'],$data['leaf']);
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
            $this->setFieldOptions($data, $params->id, true);
            return $params;
        }
    }

    /**
     * This function will update the fields and print
     * the success callback if no errors found along the way
     *
     * @param stdClass $params
     * @return array
     */
    public function updateField(stdClass $params)
    {
        $data               = get_object_vars($params);
        $data               = $this->sanitizedData($data);
        $field              = array();
	    $field['xtype']     = $data['xtype'];
        $field['form_id']   = intval($data['form_id']);
	    $field['parentId']  = $data['parentId'];
        $field['pos']       = intval($data['pos']);

        $this->db->setSQL($this->db->sqlBind($field, 'forms_fields', 'U', array('id' => $params->id)));
        $this->db->execLog();

	    unset($data['id'],$data['xtype'],$data['form_id'],$data['parentId'],$data['pos'],$data['leaf']);

        $this->setFieldOptions($data, $params->id);
        return array('success' => true);
    }

	public function updateFormField($params){
		if(is_array($params)){
			foreach($params as $field){
				$this->updateField($field);
			}
		}else{
			$this->updateField($params);
		}
		return $params;
	}
    /**
     * This function will delete the field and print success is no
     * error were found along the way.
     *
     * @param stdClass $params
     * @return array
     */
    public function removeFormField(stdClass $params)
    {
        $data = get_object_vars($params);
        $this->getFormDataTable($data['form_id']);
        $this->col = $data['name'];
        $container = false;
        /**
         * lets defines what is a container for later use.
         */
        if( $data['xtype'] == 'fieldcontainer' || $data['xtype'] == 'fieldset' ) $container = true;
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
                return array('success' => false, 'error' => 'This field has one or more child field(s). Please, remove or moved the child fields before removing this field.');
            }
        }else{
            /**
             * for all other fields lats check that the item has a
             * column in the database and that the column is empty
             * the user can NOT delete field with data in it.
             */
            if(!$this->fieldHasColumn()) {
	            return array('success' => false, 'error' => 'This field does NOT have a column in the database.<br> This is very odd... please cotact Technical Support for help');
            }else{
                if($this->filedInUsed()){
	                return array('success' => false, 'error' => 'Can NOT delete this field. This field already has data store in the database.');
                }
            }
        }
        /**
         * If the field is NOT a container the remove database
         * column for this field
         */
        if(!$container && !$this->fieldHasBrother()){
            $this->dropColumn();
        }
        /**
         * remove field and field options
         */
        $id = $data['id'];
        $this->db->setSQL("DELETE FROM forms_fields WHERE id='$id'");
        $this->db->execOnly();
        $this->db->setSQL("DELETE FROM forms_field_options WHERE field_id='$id'");
        $this->db->execOnly();
        return array('success' => true);
    }

    /**
     * @brief       Add a column to the form data table
     * @details     Simple exec SQL Statement, with no Event LOG injection
     *
     * @author      Ernesto J Rodriguez (Certun) <erodriguez@certun.com>
     * @version     Vega 1.0
     * @copyright   Gnu Public License (GPLv3)
     *
     * @param       $conf
     * @return      mixed
     */
    private function addColumn($conf)
    {
        $this->db->setSQL("ALTER TABLE $this->form_data_table ADD $this->col $conf");
        $this->db->execOnly();
        if(!$this->fieldHasColumn()) {
	        return false;
        }else{
	        return true;
        }
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
    private function dropColumn()
    {
        $this->db->setSQL("ALTER TABLE $this->form_data_table DROP $this->col");
        $this->db->execOnly();
        if($this->fieldHasColumn()) {
	        return false;
        }else{
	        return true;
        }
    }

	/**
	 * @param $data
	 * @param $id
	 * @param $new
	 * @return mixed
	 */
    private function setFieldOptions($data , $id, $new = false){
        $json = json_encode($data, JSON_NUMERIC_CHECK);
        if($new){
	        $options = array('field_id' => $id, 'options' => $json);
            $sql = $this->db->sqlBind($options, 'forms_field_options', 'I');
        }else{
	        $options = array('options' => $json);
            $sql = $this->db->sqlBind($options, 'forms_field_options', 'U', array('field_id' => $id));
        }
        $this->db->setSQL($sql);
        $this->db->execOnly();
    return;
    }
    /**
     * @param $data
     * @return array
     */
    private function setDefaults($data){
        if($data['xtype'] != 'fieldcontainer' && $data['xtype'] != 'fieldset' ){
            if(!isset($data['margin'])) $data['margin'] = '0 5 0 0';
        }
        if($data['xtype'] == 'radiofield'){
          // $data['flex'] = 1;
        }

        return $data;
    }

    /**
     * @param $form_id
     * @return mixed
     */
    private function getFormDataTable($form_id){
        $this->db->setSQL("SELECT form_data FROM forms_layout WHERE id = '$form_id'");
        $form_data_table = $this->db->fetchRecord();
        $this->form_data_table = $form_data_table['form_data'];
        return;
    }

    /**
     * @return bool
     */
    private function fieldHasColumn(){
        $this->db->setSQL("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '$this->form_data_table' AND COLUMN_NAME = '$this->col'");
        $ret = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        if(isset($ret[0]['COLUMN_NAME'])) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    private function fieldHasChild($id){
        $this->db->setSQL("SELECT id FROM forms_fields WHERE parentId ='$id'");
        $this->db->fetchRecords(PDO::FETCH_ASSOC);
        $count = $this->db->rowCount();
        if($count >= 1 ) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return bool
     */
    private function fieldHasBrother(){
        $this->db->setSQL("SELECT id FROM forms_field_options WHERE options LIKE '%\"name\":\"$this->col%\"'");
        $this->db->fetchRecords(PDO::FETCH_ASSOC);
        $count = $this->db->rowCount();
        if($count >= 2 ) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return bool
     */
    private function filedInUsed(){
        $this->db->setSQL("SELECT $this->col FROM $this->form_data_table");
        $ret = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        if($ret[0]){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function sanitizedData($data){
        foreach($data as $option => $val){
            if($val == '' || $val == null) unset($data[$option]);
            if($option == 'hideLabel' || $option == 'checkboxToggle' || $option == 'collapsed' || $option == 'collapsible'){
                if($val == 0){
                    $data[$option] = false;
                }else{
                    $data[$option] = true;
                }

            }

	        if($option == 'allowBlank'){
		        if($val){
			        $data[$option] = false;
		        }else{
			        unset($data[$option]);
		        }
	        }

            if($val == 'on'){
                $data[$option] = 'true';
            }elseif($val == 'off'){
                $data[$option] = 'false';
            }
        }
        return $data;
    }

    /**
     * This function is call after every sql statement and
     * will print the success callback with the errors found
     * then, stop the script.
     *
     * @param $err
     * @return mixed
     */
    private function checkError($err){
        if($err[2]){
            print '{"success":false,"error":"'.$err[2].'"}';
            exit;
        }else{
            return;
        }
    }

    /**
     * @return array
     */
    public function getForms(){
        $this->db->setSQL("SELECT * FROM forms_layout");
        $rows = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
            array_push($rows, $row);
        }
        return $rows;
    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function getParentFields(stdClass $params){
        $this->db->setSQL("Select ff.id, ff.xtype
                         FROM forms_fields AS ff
                    LEFT JOIN forms_layout AS fl
                           ON fl.id = ff.form_id
                        WHERE (fl.name  = '$params->currForm' OR fl.id    = '$params->currForm')
                          AND (ff.xtype = 'fieldcontainer'    OR ff.xtype = 'fieldset')
                     ORDER BY ff.pos");
        $parentFields = array();
        array_push($parentFields, array('name' => 'Root', 'value' => 'NaN'));
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $parentField){
            $id = $parentField['id'];
            $this->db->setSQL("SELECT options FROM forms_field_options WHERE field_id = '$id'");
            $fo = $this->db->fetchRecord();
            $foo = json_decode($fo['options'],true);


            $row['name']  =  $foo['title'].$foo['fieldLabel'].' ('.$parentField['xtype'].')';
            $row['value'] =  $parentField['id'];
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
            $this->db->setSQL("Select * FROM forms_fields WHERE form_id = '$params->currForm' AND (parentId IS NULL OR parentId = 'NaN') ORDER BY pos ASC, id ASC");
            $results = $this->db->fetchRecords(PDO::FETCH_ASSOC);
            foreach($results as $item){
                $opts = $this->getItemsOptions($item['id']);
                foreach($opts as $opt => $val){
                    $item[$opt] = $val;
                }
                $item['children'] = $this->getChildItems($item['id']);
                if($item['children'] == null) {
                    unset($item['children']);
                    if($item['xtype'] != 'fieldset' && $item['xtype'] != 'fieldcontainer') $item['leaf'] = true;
                }else{
                    if($item['collapsed']== 0){
                        $item['expanded'] = true;
                    }else{
                        $item['expanded'] = false;
                    }
                }
                array_push($fields,$item);
            }
        }
        return $fields;
    }

    /**
     * @param $parent
     * @return array
     */
    private function getChildItems($parent){
        $items = array();
        $this->db->setSQL("Select * FROM forms_fields WHERE parentId = '$parent' ORDER BY pos ASC");
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $item){
            $opts = $this->getItemsOptions($item['id']);
            foreach($opts as $opt => $val){
                $item[$opt] = $val;
            }
            $item['children'] = $this->getChildItems($item['id']);
            if($item['children'] == null) {
                unset($item['children']);
                if($item['xtype'] != 'fieldset' && $item['xtype'] != 'fieldcontainer') $item['leaf'] = true;
            }else{
                if($item['collapsed'] == 0){
                    $item['expanded'] = true;
                }else{
                    $item['expanded'] = false;
                }
            }
            array_push($items,$item);
        }
        return $items;
    }

    /**
     * @param $item_id
     * @return array
     */
    private function getItemsOptions($item_id){
        $foo = array();
        $this->db->setSQL("Select options FROM forms_field_options WHERE field_id = '$item_id'");
        $options = $this->db->fetchRecord();
        $options = json_decode($options['options'],true);
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
		$this->db->setSQL("Select id, options FROM $table");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$options = json_decode($row['options'],true);
			if(isset($options['allowBlank'])){
				unset($options['allowBlank']);
				$id = $row['id'];
				$data['options'] = json_encode($options);
				$this->db->setSQL($this->db->sqlBind($data, $table, 'U', "id='$id'"));
				$this->db->execOnly();
			}
		}
	}

}
//$params = new stdClass();
////$params->id         = '129';
//$params->currForm    = '2';
////$params->name       = 'allow_voice_msg';
////$params->xtype      = 'mitos.checkbox';
//print '<pre>';
//$p = new FormLayoutBuilder();
//echo '<pre>';
//print_r($p->getFormFieldsTree($params));
