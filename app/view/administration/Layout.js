/**
 * layout.ejs.php
 * Description: Layout Screen Panel
 * v0.0.1
 *
 * Author: GI Technologies, 2011
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011

 * @namespace FormLayoutBuilder.getFormFieldsTree
 * @namespace FormLayoutBuilder.getParentFields
 * @namespace FormLayoutBuilder.getForms
 * @namespace FormLayoutBuilder.addField
 * @namespace FormLayoutBuilder.updateField
 * @namespace FormLayoutBuilder.deleteField
 * @namespace FormLayoutBuilder.sortFields
 * @namespace CombosData.getFiledXtypes
 * @namespace CombosData.getOptionsByListId
 */
Ext.define('App.view.administration.Layout', {
	extend              : 'App.classes.RenderPanel',
	id                  : 'panelLayout',
	pageTitle           : 'Layout Form Editor',
	pageLayout          : 'border',
	uses                : [
		'App.classes.GridPanel'
	],
	initComponent       : function() {

		var me = this;
		me.currForm = null;
		me.currField = null;

		// *************************************************************************************
		// Form Fields TreeGrid Store
		// *************************************************************************************
		Ext.define('layoutTreeModel', {
			extend    : 'Ext.data.Model',
			fields    : [
				{name: 'id', type: 'string'},
				{name: 'text', type: 'string'},
				{name: 'pos', type: 'string'},
				{name: 'xtype', type: 'string'},
				{name: 'form_id', type: 'string'},
				{name: 'item_of', type: 'string'},
				{name: 'title', type: 'string'},
				{name: 'fieldLabel', type: 'string'},
				{name: 'emptyText', type: 'string'},
				{name: 'labelWidth', type: 'string'},
				{name: 'hideLabel', type: 'string'},
				{name: 'layout', type: 'string'},
				{name: 'width', type: 'string'},
				{name: 'height', type: 'string'},
				{name: 'anchor', type: 'string'},
				{name: 'margin', type: 'string'},
				{name: 'flex', type: 'string'},
				{name: 'collapsible', type: 'string'},
				{name: 'checkboxToggle', type: 'string'},
				{name: 'collapsed', type: 'string'},
				{name: 'inputValue', type: 'string'},
				{name: 'allowBlank', type: 'string'},
				{name: 'value', type: 'string'},
				{name: 'maxValue', type: 'string'},
				{name: 'minValue', type: 'string'},
				{name: 'boxLabel', type: 'string'},
				{name: 'grow', type: 'string'},
				{name: 'growMin', type: 'string'},
				{name: 'growMax', type: 'string'},
				{name: 'increment', type: 'string'},
				{name: 'name', type: 'string'},
				{name: 'list_id', type: 'string'}
			],
			idProperty: 'id'
		});
		/**
		 * form fields list (center grid)
		 */
		me.fieldsGridStore = Ext.create('Ext.data.TreeStore', {
			model      : 'layoutTreeModel',
			//clearOnLoad: true,
			proxy      : {
				type: 'direct',
				api : {
					read: FormLayoutBuilder.getFormFieldsTree
				}
			},
			folderSort : false,
			autoLoad   : false
		});
		/**
		 * Xtype Combobox store
		 */
		Ext.define('XtypesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'string'},
				{name: 'name', type: 'string'},
				{name: 'value', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getFiledXtypes
				}
			}
		});
		me.fieldXTypesStore = Ext.create('Ext.data.Store', {
			model   : 'XtypesComboModel',
			autoLoad: true
		});

		/**
		 * Forms grid store (left grid)
		 */
		Ext.define('FormsListModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'string'},
				{name: 'name', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: FormLayoutBuilder.getForms
				}
			}
		});
		me.formsGridStore = Ext.create('Ext.data.Store', {
			model   : 'FormsListModel',
			autoLoad: true
		});

		/**
		 * Field available on this form as parent items (fieldset / fieldcontainer )
		 * use to get the "Child of" combobox data
		 */
		Ext.define('ParentFieldsModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'name', type: 'string'},
				{name: 'value', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: FormLayoutBuilder.getParentFields
				}
			}
		});
		me.parentFieldsStore = Ext.create('Ext.data.Store', {
			model   : 'ParentFieldsModel',
			autoLoad: false
		});

		/**
		 * This are the select lists available to use for comboboxes
		 * this lists can be created an modified at "Lists" administration panel.
		 */
		Ext.define('formlistoptionsModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string'},
				{name: 'option_value', type: 'string'}
			]

		});
		me.selectListoptionsStore = Ext.create('Ext.data.Store', {
			model   : 'formlistoptionsModel',
			proxy   : {
				type: 'direct',
				api : {
					read: CombosData.getOptionsByListId
				}
			},
			autoLoad: false
		});

		/**
		 * This grid only available if the field is a Combobox
		 */
		me.selectListGrid = Ext.create('App.classes.GridPanel', {
			store           : me.selectListoptionsStore,
			region          : 'south',
			collapseMode    : 'mini',
			width           : 250,
			height          : 250,
			split           : true,
			border          : false,
			titleCollapse   : false,
			hideCollapseTool: true,
			collapsible     : true,
			collapsed       : true,
			columns         : [
				{
					text     : 'Name',
					flex     : 1,
					sortable : false,
					dataIndex: 'option_name'
				},
				{
					text     : 'Value',
					flex     : 1,
					sortable : false,
					dataIndex: 'option_value'
				}
			]
		});
		/**
		 * form to create and modified the fields
		 */
		me.fieldForm = Ext.create('App.classes.form.Panel', {
			region       : 'center',
			//url	            : 'app/administration/layout/data.php?task=formRequest',
			border       : false,
			autoScroll   : true,
			fieldDefaults: { msgTarget: 'side', labelWidth: 100 },
			defaults     : { anchor: '100%' },
			items        : [
				{
					name  : 'id',
					xtype : 'textfield',
					itemId: 'id',
					hidden: true
				},
				{
					name  : 'pos',
					xtype : 'textfield',
					itemId: 'pos',
					hidden: true
				},
				{
					name  : 'form_id',
					xtype : 'textfield',
					itemId: 'form_id',
					hidden: true
				},
				{
					fieldLabel  : 'Type',
					xtype       : 'combo',
					name        : 'xtype',
					displayField: 'name',
					valueField  : 'value',
					allowBlank  : false,
					editable    : false,
					store       : me.fieldXTypesStore,
					queryMode   : 'local',
					margin      : '5px 5px 5px 10px',
					itemId      : 'xtype',
					listeners   : {
						scope : me,
						change: me.onXtypeChange
					}
				},
				{
					fieldLabel  : 'Child Of',
					xtype       : 'combo',
					name        : 'item_of',
					displayField: 'name',
					valueField  : 'value',
					editable    : false,
					hideTrigger : true,
					store       : me.parentFieldsStore,
					queryMode   : 'local',
					margin      : '5px 5px 5px 10px',
					emptyText   : 'None',
					itemId      : 'parentFields',
					listeners   : {
						scope : me,
						expand: me.onParentFieldsExpand
					}
				},
				{
					xtype   : 'fieldset',
					itemId  : 'aditionalProperties',
					title   : 'Aditional Properties',
					defaults: { anchor: '100%' },
					items   : [
						{
							fieldLabel: 'Title',
							xtype     : 'textfield',
							name      : 'title',
							itemId    : 'title',
							allowBlank: false,
							hidden    : true
						},
						{
							fieldLabel: 'Field Label',
							xtype     : 'textfield',
							name      : 'fieldLabel',
							itemId    : 'fieldLabel',
							allowBlank: false,
							hidden    : true
						},
						{
							fieldLabel: 'Box Label',
							xtype     : 'textfield',
							name      : 'boxLabel',
							itemId    : 'boxLabel',
							allowBlank: false,
							hidden    : true
						},
						{
							fieldLabel: 'Label Width',
							xtype     : 'textfield',
							name      : 'labelWidth',
							itemId    : 'labelWidth',
							hidden    : true
						},
						{
							fieldLabel: 'Hide Label',
							xtype     : 'checkbox',
							name      : 'hideLabel',
							itemId    : 'hideLabel',
							hidden    : true
						},
						{
							fieldLabel: 'Empty Text',
							xtype     : 'textfield',
							name      : 'emptyText',
							itemId    : 'emptyText',
							hidden    : true
						},
						{
							fieldLabel: 'Layout',
							xtype     : 'textfield',
							name      : 'layout',
							itemId    : 'layout',
							hidden    : true
						},
						{
							fieldLabel: 'Name',
							xtype     : 'textfield',
							name      : 'name',
							itemId    : 'name',
							allowBlank: false,
							hidden    : true
						},
						{
							fieldLabel: 'Input Value',
							xtype     : 'textfield',
							name      : 'inputValue',
							itemId    : 'inputValue',
							allowBlank: false,
							hidden    : true
						},
						{
							fieldLabel: 'Width',
							xtype     : 'textfield',
							name      : 'width',
							itemId    : 'width',
							emptyText : 'ei. 5 for 5px',
							hidden    : true
						},
						{
							fieldLabel: 'Height',
							xtype     : 'textfield',
							name      : 'height',
							itemId    : 'height',
							emptyText : 'ei. 5 for 5px',
							hidden    : true
						},
						{
							fieldLabel: 'Anchor',
							xtype     : 'textfield',
							name      : 'anchor',
							itemId    : 'anchor',
							emptyText : 'ei. 100%',
							hidden    : true
						},
						{
							fieldLabel: 'Flex',
							xtype     : 'checkbox',
							name      : 'flex',
							itemId    : 'flex',
							hidden    : true
						},
						{
							fieldLabel: 'Collapsible',
							xtype     : 'checkbox',
							name      : 'collapsible',
							itemId    : 'collapsible',
							hidden    : true
						},
						{
							fieldLabel: 'Checkbox Toggle',
							xtype     : 'checkbox',
							name      : 'checkboxToggle',
							itemId    : 'checkboxToggle',
							hidden    : true
						},
						{
							fieldLabel: 'Collapsed',
							xtype     : 'checkbox',
							name      : 'collapsed',
							itemId    : 'collapsed',
							hidden    : true
						},
						{
							fieldLabel: 'Margin',
							xtype     : 'textfield',
							name      : 'margin',
							itemId    : 'margin',
							emptyText : 'ei. 5 5 5 5',
							hidden    : true
						},
						{
							fieldLabel: 'Column Width',
							xtype     : 'textfield',
							name      : 'columnWidth',
							itemId    : 'columnWidth',
							emptyText : 'ei. .5',
							hidden    : true
						},
						{
							fieldLabel: 'Is Required',
							xtype     : 'checkbox',
							name      : 'allowBlank',
							itemId    : 'allowBlank',
							hidden    : true
						},
						{
							fieldLabel: 'Value',
							xtype     : 'textfield',
							name      : 'value',
							itemId    : 'value',
							hidden    : true
						},
						{
							fieldLabel: 'Max Value',
							xtype     : 'textfield',
							name      : 'maxValue',
							itemId    : 'maxValue',
							hidden    : true
						},
						{
							fieldLabel: 'Min Value',
							xtype     : 'textfield',
							name      : 'minValue',
							itemId    : 'minValue',
							hidden    : true
						},
						{
							fieldLabel: 'Max Value',
							xtype     : 'timefield',
							name      : 'maxValue',
							itemId    : 'timeMaxValue',
							hidden    : true
						},
						{
							fieldLabel: 'Min Value',
							xtype     : 'timefield',
							name      : 'minValue',
							itemId    : 'timeMinValue',
							hidden    : true
						},
						{
							fieldLabel: 'Grow',
							xtype     : 'checkbox',
							name      : 'grow',
							itemId    : 'grow',
							hidden    : true
						},
						{
							fieldLabel: 'Grow Min',
							xtype     : 'textfield',
							name      : 'growMin',
							itemId    : 'growMin',
							hidden    : true
						},
						{
							fieldLabel: 'Grow Max',
							xtype     : 'textfield',
							name      : 'growMax',
							itemId    : 'growMax',
							hidden    : true
						},
						{
							fieldLabel: 'Increment',
							xtype     : 'textfield',
							name      : 'increment',
							itemId    : 'increment',
							hidden    : true
						},
						{
							fieldLabel: 'List Options',
							xtype     : 'mitos.listscombo',
							name      : 'list_id',
							itemId    : 'list_id',
							hidden    : true,
							allowBlank: false,
							listeners : {
								scope : me,
								change: me.onSelectListSelect
							}
						}
					]
				}
			]
		});
		/**
		 * this container holds the form and the select list grid.
		 * remember that the select list grid only shows if
		 * the field xtype is a combobox
		 */
		me.formContainer = Ext.create('Ext.panel.Panel', {
			title      : 'Field Configuration',
			border     : true,
			split      : true,
			width      : 390,
			region     : 'east',
			layout     : 'border',
			bodyStyle  : 'background-color:#fff!important',
			items      : [
				me.fieldForm,
				me.selectListGrid
			],
			dockedItems: [
				{
					xtype: 'toolbar',
					items: [
						{
							text   : 'Save',
							iconCls: 'save',
							scope  : me,
							handler: me.onSave
						},
						'-',
						{
							text   : 'New',
							iconCls: 'icoAddRecord',
							scope  : me,
							handler: me.onFormReset
						},
						'-',
						{
							text    : 'Add Child',
							iconCls : 'icoAddRecord',
							itemId  : 'addChild',
							disabled: true,
							scope   : me,
							handler : me.onAddChild
						},
						'-',
						{
							text   : 'Delete',
							iconCls: 'delete',
							cls    : 'toolDelete',
							scope  : me,
							handler: me.onDelete
						},
						'-',
						{
							text        : 'Form Preview',
							iconCls     : 'icoPreview',
							enableToggle: true,
							listeners   : {
								scope : me,
								toggle: me.onFormPreview
							}
						}
					]

				}
			]
		});
		/**
		 * This is the fields associated with the current Form selected
		 */
		me.fieldsGrid = Ext.create('Ext.tree.Panel', {
			store      : me.fieldsGridStore,
			region     : 'center',
			border     : true,
			sortable   : false,
			rootVisible: false,
			title      : 'Field editor (Demographics)',
			viewConfig : {
				plugins  : { ptype: 'treeviewdragdrop', allowParentInsert: true },
				listeners: {
					scope: me,
					drop : me.onDragDrop
				}
			},
			columns    : [
				{
					xtype    : 'treecolumn',
					text     : 'Field Type',
					sortable : false,
					dataIndex: 'xtype',
					width    : 200,
					align    : 'left'
				},
				{
					text     : 'Title',
					sortable : false,
					dataIndex: 'title',
					width    : 100,
					align    : 'left'
				},
				{
					text     : 'Label',
					sortable : false,
					dataIndex: 'fieldLabel',
					flex     : 1,
					align    : 'left'
				}
			],
			listeners  : {
				scope    : me,
				itemclick: me.onFieldsGridClick
			}
		});
		/**
		 * Form grid will show the available forms to modified.
		 * the user will not have the options to create
		 * forms, just to modified the fields of existing forms.
		 */
		me.formsGrid = Ext.create('App.classes.GridPanel', {
			title      : 'Form list',
			region     : 'west',
			store      : me.formsGridStore,
			width      : 200,
			border     : true,
			split      : true,
			hideHeaders: true,
			columns    : [
				{
					dataIndex: 'id',
					hidden   : true
				},
				{
					flex     : 1,
					sortable : true,
					dataIndex: 'name'
				}
			],
			listeners  : {
				scope    : me,
				itemclick: me.onFormGridItemClick
			}
		});
		/**
		 * this panel will render the current form to preview
		 * all the changes done.
		 */
		me.fromPreview = Ext.create('Ext.form.Panel', {
			region          : 'south',
			height          : 300,
			collapsible     : true,
			titleCollapse   : false,
			hideCollapseTool: true,
			collapsed       : true,
			border          : true,
			split           : true,
			collapseMode    : 'header',
			bodyStyle       : 'padding: 5px',
			layout          : 'anchor',
			fieldDefaults   : {msgTarget: 'side'},
			tools           : [
				{
					itemId : 'refresh',
					type   : 'refresh',
					scope  : me,
					handler: me.previewFormRender
				}
			]
		});

		me.pageBody = [ me.fieldsGrid, me.formsGrid , me.formContainer, me.fromPreview ];
		me.callParent(arguments);
	},
	/**
	 * if the form is valid send the POST request
	 */
	onSave              : function() {
		var me = this,
			form = me.fieldForm.getForm();
		if(form.isValid()) {
			var params = form.getValues();

			if(form.findField('id').getValue() == '') {
				FormLayoutBuilder.addField(params, function(provider, response) {
					if(response.result.success) {
						me.loadFieldsGrid();
					} else {
						Ext.Msg.alert('Opps!', response.result.error);
					}
				});
			} else {
				FormLayoutBuilder.updateField(params, function(provider, response) {
					if(response.result.success) {
						me.loadFieldsGrid();
					} else {
						Ext.Msg.alert('Opps!', response.result.error);
					}
				});
			}
		}
	},
	/**
	 * Delete logic
	 */
	onDelete            : function() {
		var me = this,
			form = me.fieldForm.getForm(),
			rec = form.getRecord();

		Ext.Msg.show({
			title  : 'Please confirm...',
			icon   : Ext.MessageBox.QUESTION,
			msg    : 'Are you sure to delete this field?',
			buttons: Ext.Msg.YESNO,
			scope  : this,
			fn     : function(btn) {
				if(btn == 'yes') {
					var params = {
						id     : rec.data.id,
						form_id: rec.data.form_id,
						name   : rec.data.name,
						xtype  : rec.data.xtype
					};

					FormLayoutBuilder.deleteField(params, function(provider, response) {
						if(response.result.success) {
							me.msg('Sweet!', 'Field deleted');
							me.currField = null;
							me.loadFieldsGrid();
							me.onFormReset();
						} else {
							Ext.Msg.alert('Opps!', response.result.error);
						}
					});
				}
			}
		});
	},
	/**
	 *
	 * @param node
	 * @param data
	 * @param overModel
	 */
	onDragDrop          : function(node, data, overModel) {
		var me = this,
			childItems = [];
        for(var i=0; i < overModel.parentNode.childNodes.length; i++ ){
			childItems.push(overModel.parentNode.childNodes[i].data.id);
		}
		var params = {
			id              : data.records[0].data.id,
			parentNode      : overModel.parentNode.data.id,
			parentNodeChilds: childItems
		};


		FormLayoutBuilder.sortFields(params, function(provider, response) {
			if(response.result.success) {
				me.msg('Sweet!', 'Form Fields Sorted');
				me.loadFieldsGrid();
				me.onFormReset();
			} else {
				Ext.Msg.alert('Opps!', response.result.error);
			}
		});
	},
	/**
	 * This is to reset the Form and load
	 * a new Model with the currForm id
	 */
	onFormReset         : function() {
		var formPanel = this.fieldForm,
			form = formPanel.getForm(),
			row = this.fieldsGrid.getSelectionModel();
		row.deselectAll();
		form.reset();
		var model = Ext.ModelManager.getModel('layoutTreeModel'),
			newModel = Ext.ModelManager.create({
				form_id: this.currForm
			}, model);
		formPanel.el.unmask();
		form.loadRecord(newModel);
	},
	/**
	 *
	 * load a new model with the form_id and item_of values.
	 * This is the easy way to add a child to a fieldset or fieldcontainer.
	 */
	onAddChild          : function() {
		var formPanel = this.fieldForm,
			form = formPanel.getForm(),
			row = this.fieldsGrid.getSelectionModel();
		row.deselectAll();
		form.reset();
		var model = Ext.ModelManager.getModel('layoutTreeModel'),
			newModel = Ext.ModelManager.create({
				form_id: this.currForm,
				item_of: this.currField
			}, model);
		formPanel.el.unmask();
		form.loadRecord(newModel);
	},
	/**
	 *
	 * This will load the current field data to the form,
	 * set the currField, and enable the Add Child btn if
	 * the field allows child items (fieldset or fieldcontainer)
	 *
	 * @param grid
	 * @param record
	 */
	onFieldsGridClick   : function(grid, record) {
		var formPanel = this.fieldForm,
			form = formPanel.getForm();
		form.loadRecord(record);
		this.currField = record.data.id;
		if(record.data.xtype == 'fieldset' || record.data.xtype == 'fieldcontainer') {
			this.formContainer.down('toolbar').getComponent('addChild').enable();
		} else {
			this.formContainer.down('toolbar').getComponent('addChild').disable();
		}
		formPanel.el.unmask();
	},
	/**
	 *
	 * @param DataView
	 * @param record
	 */
	onFormGridItemClick : function(DataView, record) {
		this.currForm = record.get('id');
		this.fieldsGrid.setTitle('Field editor (' + record.get('name') + ')');
		this.loadFieldsGrid();
		this.onFormReset();
		this.fieldForm.el.mask('Click "New" or Select a field to update');
	},
	/**
	 *
	 * This will load the Select List options. This Combobox shows only when
	 * a Type of Combobox is selected
	 *
	 * @param combo
	 * @param value
	 */
	onSelectListSelect  : function(combo, value) {
		this.selectListoptionsStore.load({params: {list_id: value}});
	},
	/**
	 *
	 * This is to handle a error when loading a combobox store.
	 *
	 * @param combo
	 */
	onParentFieldsExpand: function(combo) {
		combo.picker.loadMask.destroy();
	},
	/**
	 * onXtypeChange will search the combo value and enable/disable
	 * the fields appropriate for the xtype selected
	 *
	 * @param combo
	 * @param value
	 */
	onXtypeChange       : function(combo, value) {
		var me = this;

		if(value == 'combobox') {
			me.selectListGrid.setTitle('Select List Options');
			me.selectListGrid.expand();
			me.selectListGrid.enable();
		} else {
			me.selectListGrid.setTitle('');
			me.selectListGrid.collapse();
			me.selectListGrid.disable();
		}

		/**
		 *
		 * @param searchStr
		 */
		Array.prototype.find = function(searchStr) {
			var returnArray = false;
			for(var i = 0; i < this.length; i++) {
				if(typeof(searchStr) == 'function') {
					if(searchStr.test(this[i])) {
						if(!returnArray) {
							returnArray = [];
						}
						returnArray.push(i);
					}
				} else {
					if(this[i] === searchStr) {
						if(!returnArray) {
							returnArray = [];
						}
						returnArray.push(i);
					}
				}
			}
			return returnArray;
		};


		var addProp = me.fieldForm.getComponent('aditionalProperties');
		var is = addProp.items.keys;

		/**
		 *
		 * @param items
		 */
		function enableItems(items) {
			for(var i = 0; i < is.length; i++) {
				if(!items.find(is[i])) {
					addProp.getComponent(is[i]).hide();
					addProp.getComponent(is[i]).disable();
				} else {
					addProp.getComponent(is[i]).show();
					addProp.getComponent(is[i]).enable();
				}

			}
		}

		var items;
		if(value == 'fieldset') {
			items = [
				'title',
				'collapsible',
				'collapsed',
				'checkboxToggle',
				'margin',
				'columnWidth'
			];
		} else if(value == 'fieldcontainer') {
			items = [
				'fieldLabel',
				'labelWidth',
				'hideLabel',
				'width',
				'layout',
				'margin',
				'columnWidth'
			];
		} else if(value == 'combobox') {
			items = [
				'name',
				'width',
				'emptyText',
				'fieldLabel',
				'hideLabel',
				'labelWidth',
				'margin',
				'allowBlank',
				'list_id'
			];
		} else if(value == 'mitos.checkbox') {
			items = [
				'name',
				'width',
				'fieldLabel',
				'hideLabel',
				'labelWidth',
				'margin'
			];
		} else if(value == 'textfield') {
			items = [
				'name',
				'width',
				'anchor',
				'emptyText',
				'fieldLabel',
				'hideLabel',
				'labelWidth',
				'allowBlank',
				'margin'
			];
		} else if(value == 'textareafield') {
			items = [
				'name',
				'width',
				'anchor',
				'height',
				'emptyText',
				'fieldLabel',
				'hideLabel',
				'labelWidth',
				'allowBlank',
				'grow',
				'growMin',
				'growMax',
				'margin'
			];
		} else if(value == 'numberfield') {
			items = [
				'name',
				'width',
				'value',
				'emptyText',
				'maxValue',
				'minValue',
				'increment',
				'fieldLabel',
				'labelWidth',
				'hideLabel',
				'margin'
			];
		} else if(value == 'timefield') {
			items = [
				'name',
				'width',
				'value',
				'emptyText',
				'timeMaxValue',
				'timeMinValue',
				'increment',
				'fieldLabel',
				'labelWidth',
				'hideLabel',
				'margin'
			];
		} else if(value == 'radiofield') {
			items = [
				'name',
				'width',
				'boxLabel',
				'labelWidth',
				'hideLabel',
				'margin',
				'inputValue'
			];
		} else if(value == 'datefield' || value == 'mitos.datetime') {
			items = [
				'name',
				'width',
				'value',
				'layout',
				'emptyText',
				'fieldLabel',
				'labelWidth',
				'hideLabel',
				'allowBlank',
				'margin'
			];
		} else {
			items = [
				'name',
				'width',
				'emptyText',
				'fieldLabel',
				'labelWidth',
				'hideLabel',
				'margin'
			];
		}
		enableItems(items);
	},
	/**
	 *
	 * On toggle down/true expand the preview panel and re-render the form
	 *
	 * @param btn
	 * @param toggle
	 */
	onFormPreview       : function(btn, toggle) {
		var me = this;

		if(toggle === true) {
			me.previewFormRender();
			me.fromPreview.expand(false);
		} else {
			me.fromPreview.collapse(false);
		}
	},
	/**
	 *
	 *  this function re-render the preview form
	 */
	previewFormRender   : function() {
		var me = this,
			form = this.fromPreview;

		form.el.mask();
		me.getFormItems(form, me.currForm, function() {
			form.doLayout();
			form.el.unmask();
		});

	},
	/**
	 *
	 *  re-load the fields grid (main TreeGrid)
	 *  check if a form is selected, if not the select the first choice
	 *  save the form id inside this.currForm and load the grid and the
	 *  parent fields of this form.
	 *
	 *  parentFieldsStore is use to create the child of select list
	 */
	loadFieldsGrid      : function() {
		var me = this,
			row = me.formsGrid.getSelectionModel();
		if(me.currForm === null) {
			row.select(0);
		}
		me.currForm = row.getLastSelected().data.id;

		me.fieldsGridStore.load({params: {currForm: me.currForm }});
		me.parentFieldsStore.load({params: {currForm: me.currForm }});

		me.previewFormRender();
		me.fieldsGrid.doLayout()
	},
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive            : function(callback) {
        var me = this;
        me.onFormReset();
        me.fieldForm.el.mask('Click "New" or Select a field to update');
        me.selectListoptionsStore.load({
            callback:function(){
                me.loadFieldsGrid();
            }
        });
        //me.loadFieldsGrid();
		callback(true);
	}
});