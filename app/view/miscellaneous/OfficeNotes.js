//******************************************************************************
// ofice_notes.ejs.php
// office Notes Page
// v0.0.1
// 
// Author: Ernest Rodriguez
// Modified:
// 
// GaiaEHR (Electronic Health Records) 2011
//******************************************************************************
Ext.define('App.view.miscellaneous.OfficeNotes', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelOfficeNotes',
	pageTitle    : 'Office Notes',
	pageLayout   : 'border',
	uses         : [
		'App.classes.GridPanel',
		'App.classes.RenderPanel'
	],
	initComponent: function() {
		var me = this;

		Ext.define('OfficeNotesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'date', type: 'date', dateFormat: 'c'},
				{name: 'body', type: 'string'},
				{name: 'user', type: 'string'},
				{name: 'facility_id', type: 'string'},
				{name: 'activity', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read  : OfficeNotes.getOfficeNotes,
					create: OfficeNotes.addOfficeNotes,
					update: OfficeNotes.updateOfficeNotes
				}
			}
		});
		me.store = Ext.create('Ext.data.Store', {
			model   : 'OfficeNotesModel',
			autoLoad: true
		});
		me.form = Ext.create('Ext.form.FormPanel', {
			region     : 'north',
			frame      : true,
			height     : 97,
			margin     : '0 0 3 0',
			items      : [
				{
					xtype     : 'textareafield',
					allowBlank: false,
					grow      : true,
					margin    : 0,
					itemId    : 'body',
					name      : 'body',
					anchor    : '100%',
					emptyText : 'Type new note here...',
					listeners : {
						scope         : me,
						validitychange: me.onValidityChange
					}
				}
			],
			dockedItems: [
				{
					xtype: 'toolbar',
					dock : 'top',
					items: [
						{
							text    : 'Save',
							iconCls : 'save',
							itemId  : 'cmdSave',
							disabled: true,
							scope   : me,
							handler : me.onSave
						},
						'-',
						{
							text    : 'Hide This Note',
							iconCls : 'save',
							itemId  : 'cmdHide',
							tooltip : 'Hide Selected Office Note',
							disabled: true,
							scope   : me,
							handler : me.onHide

						},
						'-',
						{
							text    : 'Reset Form',
							iconCls : 'save',
							itemId  : 'cmdReset',
							disabled: true,
							scope   : me,
							handler : me.onReset
						}
					]
				}
			]
		});
		me.grid = Ext.create('App.classes.GridPanel', {
			region   : 'center',
			store    : me.store,
			listeners: {
				scope    : me,
				itemclick: me.onItemClick
			},
			columns  : [
				{ width: 150, header: 'Date', sortable: true, dataIndex: 'date', renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s') },
				{ width: 150, header: 'User', sortable: true, dataIndex: 'user' },
				{ flex: 1, header: 'Note', sortable: true, dataIndex: 'body' }

			],
			tbar     : Ext.create('Ext.PagingToolbar', {
				store      : me.store,
				displayInfo: true,
				emptyMsg   : "No Office Notes to display",
				plugins    : Ext.create('Ext.ux.SlidingPager', {}),
				items      : [
					{
						text        : 'Show Only Active Notes',
						iconCls     : 'save',
						enableToggle: true,
						pressed     : true,
						handler     : function() {
							me.cmdShowAll.toggle(false);
							me.store.load({params: {show: 'active' }});
						}
					},
					'-',
					{
						text        : 'Show All Notes',
						iconCls     : 'save',
						enableToggle: true,
						handler     : function() {
							me.cmdShow.toggle(false);
							me.store.load({params: {show: 'all' }});
						}
					}
				]
			})
		}); // END GRID
		me.pageBody = [ me.form, me.grid ];
		me.callParent(arguments);
	},


	onSave: function(btn) {
		var form = btn.up('form').getForm(),
			store = this.store,
			record = form.getRecord(),
			values = form.getValues(),
			storeIndex = store.indexOf(record);
		if(storeIndex == -1) {
			store.add(values);
		} else {
			record.set(values);
		}
		store.sync();
		//store.load();
	},

	onhide: function() {

	},

	onReset: function(btn) {
		var panel = this.form,
			form = panel.getForm(),
			toolbar = panel.down('toolbar'),
			savebtn = toolbar.getComponent('cmdSave'),
			hidebtn = toolbar.getComponent('cmdHide'),
			resetbtn = toolbar.getComponent('cmdReset');
		form.reset();
		savebtn.disable();
		hidebtn.disable();
		resetbtn.disable();
		savebtn.setText('Save');
	},

	onItemClick: function(grid, record) {
		var panel = this.form,
			form = panel.getForm(),
			toolbar = panel.down('toolbar'),
			savebtn = toolbar.getComponent('cmdSave'),
			hidebtn = toolbar.getComponent('cmdHide'),
			resetbtn = toolbar.getComponent('cmdReset');
		form.reset();
		form.loadRecord(record);
		savebtn.enable();
		hidebtn.enable();
		resetbtn.enable();
		savebtn.setText('Update');
	},


	onValidityChange: function() {
		var panel = this.form,
			textfield = panel.getComponent('body'),
			toolbar = panel.down('toolbar'),
			savebtn = toolbar.getComponent('cmdSave'),
			resetbtn = toolbar.getComponent('cmdReset');

		if(textfield.isValid()) {
			savebtn.enable();
			resetbtn.enable();
		} else {
			savebtn.disable();
		}
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		this.store.load({params: {show: 'active' }});
		callback(true);
	}
}); //ens oNotesPage class