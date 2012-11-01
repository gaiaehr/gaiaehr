<?php
if(!isset($_SESSION)) {
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['root'] . '/classes/dbHelper.php');
include_once($_SESSION['root'] . '/dataProvider/CombosData.php');
/**
 * @brief       Form Layout Engine
 * @details     This class will create dynamic ExtJS v4 form items array,
 *              previously created or edited from the Layout Form Editor.
 *              Gathering all it's data and parameters from the layout_options table.
 *
 *              What this class will not do: This class will not create the
 *              entire Screen Panel for you, this will only create the form
 *              items array with the fields names & dataStores configured on
 *              the layout_options table.
 *
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 */
class FormLayoutEngine
{
	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * @var dbHelper
	 */
	private $cb;

	/**
	 * Creates the dbHelper instance
	 */
	function __construct()
	{
		$this->db = new dbHelper();
		$this->cb = new CombosData();
		return;
	}

	/**
	 * @brief       Get Form Fields by Form ID or Form Title
	 * @details     We can get the form fields by form name or form if
	 *              example: getFields('Demographics') or getFields('1')
	 *              The logic of the function is to get the form parent field
	 *              and its options, then get the child items if any with it options.
	 *              Then.. use reg Expression to remove the double quotes from all
	 *              the options and leave the double quotes to all options values,
	 *              unless the value is a int or bool.
	 *
	 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
	 * @version     Vega 1.0
	 *
	 * @param       stdClass $params With the form Tirle or Form ID
	 * @internal    $params->formToRender Holds the Title or ID of the form to render
	 * @return      string String of javascript array
	 */
	function getFields(stdClass $params)
	{
		/**
		 * define $items as an array to push all the $item into.
		 */
		$items = array();
		$items2 = array();
		/**
		 * get the form parent fields
		 */
		$this->db->setSQL("Select ff.*
                         FROM forms_fields AS ff
                    LEFT JOIN forms_layout AS fl
                           ON ff.form_id = fl.id
                        WHERE (fl.name = '$params->formToRender' OR fl.id = '$params->formToRender')
                          AND (ff.parentId IS NULL OR ff.parentId = 'NaN')
                     ORDER BY pos ASC, ff.id ASC");
		/**
		 * for each parent item lets get all the options and children items
		 */
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $item) {
			/**
			 * get parent field options using the parent item "id" as parameter and
			 * store the return array in $opts.
			 */
			$opts = $this->getItemsOptions($item['id']);
			/**
			 * now take each option and add it to this $item array
			 */
			foreach($opts as $opt => $val) {
				if($opt != 'pos') {
					$item[$opt] = $val;
				};
			}
			if($item['xtype'] == 'combobox') {
				$item = $this->getComboDefaults($item);
				$item['store'] = $this->getStore($item['list_id']);
			}

			if($item['xtype'] == 'datefield') {
				$item['format'] = 'Y-m-d';
			}


			/**
			 * now lets get the the child items using the parent item ID parameter
			 */
			$item['items'] = $this->getChildItems($item['id']);
			if($item['xtype'] == 'fieldset' && $item['title'] == 'Assessment') {
				$item['items'][] = array(
					'xtype'    => 'icdsfieldset',
					'emptyText'=> 'Search For Diagnosis Codes',
					'name'     => 'icdxCodes'
				);
			}
			/**
			 * lets check if this item has a child items. If not, the unset the $item['Items']
			 * this way we make sure the we done return a items property
			 */
			if($item['items'] == null) unset($item['items']);


			/**
			 * unset the stuff that are not properties
			 */
			unset($item['id'], $item['form_id'], $item['parentId'], $item['pos']);

			/**
			 * push this item into the $items Array
			 */
			if($item['xtype'] == 'fieldset' && ($params->formToRender == 'Demographics' || $params->formToRender == 1)) {
				$item['xtype'] = 'panel';
				$item['border'] = false;
				$item['bodyBorder'] = false;
				$item['bodyPadding'] = 10;
				if($item['title'] == 'Primary Insurance' || $item['title'] == 'Secondary Insurance' || $item['title'] == 'Tertiary Insurance' ){
					array_push($items2, $item);
				}else{
					array_push($items, $item);
				}
			}else{
				array_push($items, $item);
			}

		}
		/**
		 * <p>In this next block of code we are going to clean the json output using a reg expression
		 * to remove the unnecessary double quotes from the properties, bools, and ints values.
		 * basically we start we this input..</p>
		 * <code>
		 * [{
		 *      "xtype":"fieldset",
		 *      "title":"Who",
		 *      "collapsible":"true",
		 *      "items":[{
		 *          "xtype":"fieldcontainer",
		 *          "fieldLabel":"Name",
		 *          "layout":"hbox",
		 *          "anchor":"100%",
		 *       }]
		 * }]
		 * </code>
		 * <p>and finish with this output...</p>
		 * <code>
		 * [{
		 *      xtype:'fieldset',
		 *      title:'Who',
		 *      collapsible:true,
		 *      items:[{
		 *          xtype:'fieldcontainer',
		 *          fieldLabel:'Name',
		 *          layout:'hbox',
		 *          anchor:'100%',
		 *       }]
		 * }]
		 * </code>
		 * <p>The regular expression will select any string that...</p>
		 *
		 * <p>is surrounded by double quotes and follow by : for example "xtype": </p>
		 *
		 * <p>or "Ext.create</p>
		 *
		 * <p>or }]})"</p>
		 *
		 * <p>Then remove the double quotes form that selection.</p>
		 *
		 * <p>Then replace remaining double quotes for single quotes <-- not required but...
		 * we do it because GaiaEHR user single quotes to define strings.</p>
		 */
		$rawStr     = json_encode($items);

		if($params->formToRender == 'Demographics' || $params->formToRender == 1){
			$rawStr2     = json_encode($items2);
			$rawStr = "Ext.create('Ext.container.Container',{layout:{type:'vbox',align:'stretch'},items:[Ext.create('Ext.tab.Panel',{border:false,height:240,defaults:{autoScroll:true},items:$rawStr}),";
			$rawStr .= "Ext.create('Ext.tab.Panel',{height:390,border:false,flex:1,defaults:{autoScroll:true},action:'insurances',items:$rawStr2})]})";
		}


		$regex      = '("\w*?":|"Ext\.create|\)"\})';
		$cleanItems = array();
		preg_match_all($regex, $rawStr, $rawItems);
		foreach($rawItems[0] as $item) {
			array_push($cleanItems, str_replace('"', '', $item));
		}
		$itemsJsArray = str_replace('"', '\'', str_replace($rawItems[0], $cleanItems, $rawStr));
		return preg_replace("/(\w)(')(\w)/i", "$1\'$3", $itemsJsArray);
		//return $items;
	}

	/**
	 * @param $parent
	 * @return array
	 *
	 * Here we use the parent id to get the child items and it options
	 * using basically the same logic of getFields() function and returning
	 * an array of child items
	 */
	function getChildItems($parent)
	{
		$items = array();
		$this->db->setSQL("Select * FROM forms_fields WHERE parentId = '$parent' ORDER BY pos ASC");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $item) {
			$opts = $this->getItemsOptions($item['id']);
			foreach($opts as $opt => $val) {
				$item[$opt] = $val;
			}
			/**
			 * If the item is a combo box lets create a store...
			 */
			if($item['xtype'] == 'combobox') {
				$item = $this->getComboDefaults($item);
				$item['store'] = $this->getStore($item['list_id']);
			}
			if($item['xtype'] == 'datefield') {
				$item['format'] = 'Y-m-d';
			}
			/**
			 * this if what makes this function reclusive this function will keep
			 * calling it self
			 */
			$item['items'] = $this->getChildItems($item['id']);
			if($item['items'] == null) unset($item['items']);
			unset($item['id'], $item['form_id'], $item['parentId'], $item['pos']);
			array_push($items, $item);
		}
		return $items;
	}

	/*
		 * @param $item_id
		 * @return array
		 */
	function getItemsOptions($item_id)
	{
		$foo = array();
		$this->db->setSQL("Select options FROM forms_field_options WHERE field_id = '$item_id'");
		$options = $this->db->fetchRecord();
		$options = json_decode($options['options'], true);
		foreach($options as $option => $value) {
			$foo[$option] = $value;
			if($value == 'temp_f' ||
				$value == 'temp_c' ||
				$value == 'weight_lbs' ||
				$value == 'weight_kg' ||
				$value == 'height_cm' ||
				$value == 'height_in' ||
				$value == 'head_circumference_cm' ||
				$value == 'head_circumference_in' ||
				$value == 'waist_circumference_cm' ||
				$value == 'waist_circumference_in'
			) {
				$foo['enableKeyEvents'] = true;
			}
		}
		return $foo;
	}

	/**
	 * The return of this function is use for testing only
	 *
	 * @param $list_id
	 * @return string
	 */
	function getStore($list_id)
	{
		$params = new stdClass();
		$params->list_id = $list_id;
		$options = $this->cb->getOptionsByListId($params);
		$buff = "Ext.create('Ext.data.Store',{fields:['option_name','option_value'],data:[";
		foreach($options as $option) {
			$option_name  = $option['option_name'];
			$option_value = $option['option_value'];
			$buff .= "{option_name:'$option_name',option_value:'$option_value'},";
		}
		$buff = rtrim($buff, ',');
		$buff .= "]})";
		return $buff;
	}

	/**
	 * @param $item
	 * @return array
	 */
	function getComboDefaults($item)
	{
		$item['displayField'] = 'option_name';
		$item['valueField']   = 'option_value';
		$item['queryMode']    = 'local';
		$item['editable']     = false;
		if(!isset($item['emptyText'])) {
			$item['emptyText'] = 'Select';
		}
		return $item;
	}
}
//echo '<pre>';
//$params = new stdClass;
//$params->formToRender = '8';
//$f = new FormLayoutEngine();
//print_r($f->getFields($params));
////print_r($f->getItemsOptions(342));