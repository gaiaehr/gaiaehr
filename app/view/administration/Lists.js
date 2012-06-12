/**
 *
 * list.ejs.php
 * List Options Panel
 * v0.0.2
 *
 * Author: Ernest Rodriguez
 * Modified: GI Technologies, 2011
 *
 * GaiaEHR (Eletronic Health Records) 2011
 *
 * @namespace Lists.getOptions
 * @namespace Lists.addOption
 * @namespace Lists.updateOption
 * @namespace Lists.deleteOption
 * @namespace Lists.sortOptions
 * @namespace Lists.getLists
 * @namespace Lists.addList
 * @namespace Lists.updateList
 * @namespace Lists.deleteList
 *
 */
Ext.define('App.view.administration.Lists', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelLists',
	pageTitle    : 'Select List Options',
	pageLayout   : 'border',
	uses         : [
		'App.classes.GridPanel',
		'App.classes.form.FormPanel',
		'Ext.grid.plugin.RowEditing'
	],
	initComponent: function() {

		var me = this;
		me.currList = null;
		me.currTask = null;

		/**
		 * Options Store
		 */
		Ext.define('ListOptionsModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'        },
				{name: 'list_id', type: 'string'    },
				{name: 'option_value', type: 'string'    },
				{name: 'option_name', type: 'string'    },
				{name: 'seq', type: 'string'     },
				{name: 'notes', type: 'string'    },
				{name: 'active', type: 'bool'    }
			]

		});
		me.optionsStore = Ext.create('Ext.data.Store', {
			model   : 'ListOptionsModel',
			proxy   : {
				type: 'direct',
				api : {
					read  : Lists.getOptions,
					create: Lists.addOption,
					update: Lists.updateOption
				}
			},
			autoLoad: false
		});
		/**
		 * List Store
		 */
		Ext.define('ListsGridModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'        },
				{name: 'title', type: 'string'    },
				{name: 'active', type: 'bool'    },
				{name: 'in_use', type: 'bool'    }
			]
		});
		me.listsStore = Ext.create('Ext.data.Store', {
			model   : 'ListsGridModel',
			proxy   : {
				type: 'direct',
				api : {
					read  : Lists.getLists,
					create: Lists.addList,
					update: Lists.updateList
				}
			},
			autoLoad: false
		});
		/**
		 * RowEditor Classes
		 */
		me.optionsRowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			autoCancel   : false,
			errorSummary : false,
			listeners    : {
				scope     : me,
				afteredit : me.afterEdit,
				canceledit: me.onCancelEdit
			}
		});
		me.listsRowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			autoCancel   : false,
			errorSummary : false,
			listeners    : {
				scope     : me,
				afteredit : me.afterEdit,
				canceledit: me.onCancelEdit
			}
		});
		/**
		 * Lists Grid
		 */
		me.listsGrid = Ext.create('App.classes.GridPanel', {
			store      : me.listsStore,
			itemId     : 'listsGrid',
			plugins    : [ me.listsRowEditing ],
			width      : 320,
			margin     : '0 2 0 0',
			region     : 'west',
			columns    : [
				{
					text     : 'Select Lists',
					flex     : 1,
					sortable : false,
					dataIndex: 'title',
					editor   : {
						allowBlank: false
					}
				},
				{
					text     : 'Active?',
					width    : 55,
					sortable : false,
					dataIndex: 'active',
					renderer : me.boolRenderer,
					editor   : {
						xtype  : 'mitos.checkbox',
						padding: '0 0 0 18'
					}
				},
				{
					text     : 'In Use?',
					width    : 55,
					sortable : false,
					dataIndex: 'in_use',
					renderer : me.boolRenderer
				}
			],
			listeners  : {
				scope    : me,
				selectionchange: me.onListsGridClick
			},
			dockedItems: [
				{
					xtype: 'toolbar',
					dock : 'top',
					items: [
						{
							text   : 'New List',
							iconCls: 'icoAddRecord',
							scope  : me,
							handler: me.onNewList
						},
						'->',
						{
							text    : 'Delete List',
							iconCls : 'icoDeleteBlack',
							itemId  : 'listDeleteBtn',
							disabled: true,
							scope   : me,
							handler : me.onDelete,
							tooltip : 'Lists currently in used by forms can NOT be deleted, but they can be disable'
						}
					]
				}
			]
		});
		/**
		 * Options Grid
		 */
		me.optionsGrid = Ext.create('App.classes.GridPanel', {
			store      : me.optionsStore,
			itemId     : 'optionsGrid',
			plugins    : [ me.optionsRowEditing ],
			region     : 'center',
			viewConfig : {
				plugins  : {
					ptype   : 'gridviewdragdrop',
					dragText: 'Drag and drop to reorganize'
				},
				listeners: {
					scope: me,
					drop : me.onDragDrop
				}
			},
			columns    : [
				{
					text     : 'Option Title',
					width    : 200,
					sortable : true,
					dataIndex: 'option_name',
					editor   : {
						allowBlank     : false,
						enableKeyEvents: true,
						listeners      : {
							scope: me,
							keyup: me.onOptionTitleChange
						}
					}
				},
				{
					text     : 'Option Value',
					width    : 200,
					sortable : true,
					dataIndex: 'option_value',
					editor   : {
						allowBlank: false,
						readOnly  : true,
						itemId    : 'optionValueTextField'
					}
				},
				{
					text     : 'Notes',
					sortable : true,
					dataIndex: 'notes',
					flex     : 1,
					editor   : { allowBlank: true }
				},
				{
					text     : 'Active?',
					width    : 55,
					sortable : false,
					dataIndex: 'active',
					renderer : me.boolRenderer,
					editor   : {
						xtype  : 'mitos.checkbox',
						padding: '0 0 0 18'
					}
				}
			],
			dockedItems: [
				{
					xtype: 'toolbar',
					dock : 'top',
					items: ['->', {
						text   : 'Add Option',
						iconCls: 'icoAddRecord',
						scope  : me,
						handler: me.onNewOption
					}]
				}
			]
		});
		me.pageBody = [me.listsGrid, me.optionsGrid ];
		me.callParent(arguments);
	},

	/**
	 * This wll load a new record to the grid
	 * and start the rowEditor
	 */
	onNewList: function() {
		var me = this;
		me.listsRowEditing.cancelEdit();
		var m = Ext.create('ListsGridModel', {});
		me.listsStore.insert(0, m);
		me.listsRowEditing.startEdit(0, 0);
	},

	/**
	 *
	 * @param grid
	 * @param record
	 */
	onListsGridClick: function(grid, selected) {
		var me = this,
			deleteBtn = me.listsGrid.down('toolbar').getComponent('listDeleteBtn'),
		inUse = !!selected[0].data.in_use == '1';

		me.currList = selected[0].data.id;
		me.optionsStore.load({params: {list_id: me.currList}});

		inUse ? deleteBtn.disable() : deleteBtn.enable();
	},

	/**
	 * This wll load a new record to the grid
	 * and start the rowEditor
	 */
	onNewOption: function() {
		var me = this;
		me.optionsRowEditing.cancelEdit();
		var m = Ext.create('ListOptionsModel', {
			list_id: me.currList
		});
		me.optionsStore.insert(0, m);
		me.optionsRowEditing.startEdit(0, 0);
	},

	/**
	 * Set the Option Value same as Option Title
	 * @param a
	 */
	onOptionTitleChange: function(a) {
		var value = a.getValue(),
			field = a.up('container').getComponent('optionValueTextField');
		field.setValue(value);
	},
	/**
	 * Logic to sort the options
	 * @param node
	 * @param data
	 * @param overModel
	 */
	onDragDrop         : function(node, data, overModel) {
		var me = this,
			items = overModel.stores[0].data.items,
			gridItmes = [];
		Ext.each(items, function(iteme) {
			gridItmes.push(iteme.data.id);
		});
		var params = {
			list_id: data.records[0].data.list_id,
			fields : gridItmes
		};
		Lists.sortOptions(params, function() {
			me.optionsStore.load({params: {list_id: me.currList}});
		});
	},


	/**
	 * Row Editting stuff
	 * @param a
	 */
	afterEdit: function(a) {
		a.context.store.sync();
		//a.context.store.load({params: {list_id: this.currList}});
	},

	onCancelEdit: function(a) {
        say(a);
		a.context.store.load({params: {list_id: this.currList}});
	},

	onDelete: function(a) {
		var me = this,
			grid = a.up('grid'),
			store = grid.getStore(),
			sm = grid.getSelectionModel(),
			record = sm.getLastSelected();

		Ext.Msg.show({
			title  : 'Please confirm...',
			icon   : Ext.MessageBox.QUESTION,
			msg    : 'Are you sure to delete this record?',
			buttons: Ext.Msg.YESNO,
			scope  : me,
			fn     : function(btn) {
				if(btn == 'yes') {
					Lists.deleteList(record.data, function(provider, response) {
						if(response.result.success) {
							me.msg('Sweet!', 'List "' + record.data.title + '" deleted.');
							store.load();
							me.optionsStore.load();
						} else {
							Ext.Msg.alert('Oops!', 'Unable to delete "' + record.data.title + '"<br>This List is currently been used by one or more forms.');
						}

					});
				}
			}
		});
	},

	loadGrid: function() {
		var me = this;
		if(me.currList === null) {
			me.currList = me.listsStore.getAt(0).data.id;
		}
		me.optionsStore.load({params: {list_id: me.currList}});
	},
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
        var me = this;
        this.listsStore.load({
            scope:me,
            callback:me.loadGrid
        });
		//this.loadGrid();
        callback(true);
	}
});
