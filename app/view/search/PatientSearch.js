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
Ext.define('App.view.search.PatientSearch', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelPatientSearch',
	pageTitle    : 'Advance Patient Search',
	pageLayout   : 'border',
	uses         : [
		'App.classes.GridPanel'
	],
	initComponent: function() {
		var me = this;


		me.form = Ext.create('Ext.form.FormPanel', {
			region     : 'north',
			height     : 200,
			bodyPadding: 10,
			margin     : '0 0 3 0',
			buttonAlign: 'left',
			items      : [
				{
					xtype     : 'fieldcontainer',
					fieldLabel: 'Name',
					layout    : 'hbox',
					defaults  : { margin: '0 5 0 0' },
					items     : [
						{
							xtype    : 'textfield',
							emptyText: 'First Name',
							name     : 'fname'
						},
						{
							xtype    : 'textfield',
							emptyText: 'Middle Name',
							name     : 'mname'
						},
						{
							xtype    : 'textfield',
							emptyText: 'Last Name',
							name     : 'lname'
						}
					]
				}
			],

			buttons: [
				{
					text   : 'Search',
					iconCls: 'save',
					handler: function() {

					}
				},
				'-',
				{
					text   : 'Reset',
					iconCls: 'save',
					tooltip: 'Hide Selected Office Note',
					handler: function() {

					}
				}
			]
		});
		me.grid = Ext.create('App.classes.GridPanel', {
			region   : 'center',
			//store    : me.store,
			columns  : [
				{ header: 'id', sortable: false, dataIndex: 'id', hidden: true},
				{ width: 150, header: 'Date', sortable: true, dataIndex: 'date', renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s') },
				{ width: 150, header: 'User', sortable: true, dataIndex: 'user' },
				{ flex: 1, header: 'Note', sortable: true, dataIndex: 'body' }

			],
			tbar     : Ext.create('Ext.PagingToolbar', {
				store      : me.store,
				displayInfo: true,
				emptyMsg   : "No Office Notes to display",
				plugins    : Ext.create('Ext.ux.SlidingPager', {})
			})
		}); // END GRID
		me.pageBody = [ me.form, me.grid ];
		me.callParent(arguments);
	}, // end of initComponent
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive     : function(callback) {
		callback(true);
	}
}); //ens oNotesPage class