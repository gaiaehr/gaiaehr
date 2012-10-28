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
	extend       : 'App.ux.RenderPanel',
	id           : 'panelPatientSearch',
	pageTitle    : i18n('advance_patient_search'),
	pageLayout   : 'border',
	uses         : [
		'App.ux.GridPanel'
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
					fieldLabel: i18n('name'),
					layout    : 'hbox',
					defaults  : { margin: '0 5 0 0' },
					items     : [
						{
							xtype    : 'textfield',
							emptyText: i18n('first_name'),
							name     : 'fname'
						},
						{
							xtype    : 'textfield',
							emptyText: i18n('middle_name'),
							name     : 'mname'
						},
						{
							xtype    : 'textfield',
							emptyText: i18n('last_name'),
							name     : 'lname'
						}
					]
				}
			],

			buttons: [
				{
					text   : i18n('search'),
					iconCls: 'save',
					handler: function() {

					}
				},
				'-',
				{
					text   : i18n('reset'),
					iconCls: 'save',
					tooltip: i18n('hide_selected_office_note'),
					handler: function() {

					}
				}
			]
		});
		me.grid = Ext.create('App.ux.GridPanel', {
			region   : 'center',
			//store    : me.store,
			columns  : [
				{ header: 'id', sortable: false, dataIndex: 'id', hidden: true},
				{ width: 150, header: i18n('date'), sortable: true, dataIndex: 'date', renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s') },
				{ width: 150, header: i18n('user'), sortable: true, dataIndex: 'user' },
				{ flex: 1, header: i18n('note'), sortable: true, dataIndex: 'body' }

			],
			tbar     : Ext.create('Ext.PagingToolbar', {
				store      : me.store,
				displayInfo: true,
				emptyMsg   : i18n('no_office_notes_to_display'),
				plugins    : Ext.create('Ext.ux.SlidingPager', {})
			})
		}); // END GRID
		me.pageBody = [ me.form, me.grid ];
		me.callParent(arguments);
	}, // end of initComponent
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive     : function(callback) {
		callback(true);
	}
}); //ens oNotesPage class