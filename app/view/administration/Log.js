/**
 * Logs.ejs.php
 * Description: Log Screen
 * v0.0.4
 *
 * Author: Ernesto J Rodriguez
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace Logs.getLogs
 */
Ext.define('App.view.administration.Log', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelLog',
	uses         : [ 'App.classes.GridPanel' ],
	pageTitle    : 'Event History Log',
	initComponent: function() {
		var me = this;

		Ext.define('LogsModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'date', type: 'string'},
				{name: 'event', type: 'auto'},
				{name: 'user', type: 'string'},
				{name: 'facility', type: 'string'},
				{name: 'comments', type: 'string'},
				{name: 'user_notes', type: 'string'},
				{name: 'patient_id', type: 'string'},
				{name: 'success', type: 'int'},
				{name: 'checksum', type: 'string'},
				{name: 'crt_user', type: 'string'}
			]

		});

		me.logStore = Ext.create('Ext.data.Store', {
			model   : 'LogsModel',
			proxy   : {
				type  : 'direct',
				api   : {
					read: Logs.getLogs
				},
				reader: {
					totalProperty: 'totals',
					root         : 'rows'
				}
			},
			autoLoad: false
		});

		// *************************************************************************************
		// Create the GridPanel
		// *************************************************************************************
		me.logGrid = Ext.create('App.classes.GridPanel', {
			store    : me.logStore,
			columns  : [
				{ text: 'id', sortable: false, dataIndex: 'id', hidden: true},
				{ width: 120, text: 'Date', sortable: true, dataIndex: 'date' },
				{ width: 160, text: 'User', sortable: true, dataIndex: 'user' },
				{ width: 100, text: 'Event', sortable: true, dataIndex: 'event' },
				{ flex: 1, text: 'Activity', sortable: true, dataIndex: 'comments' }
			],
			listeners: {
				scope       : this,
				itemclick   : me.onItemclick,
				itemdblclick: me.onItemdblclick
			},
			tbar     : Ext.create('Ext.PagingToolbar', {
				store      : me.logStore,
				displayInfo: true,
				emptyMsg   : 'No Office Notes to display',
				plugins    : Ext.create('Ext.ux.SlidingPager', {}),
				items      : [
					{
						xtype   : 'button',
						text    : 'View Log Event Details',
						iconCls : 'edit',
						itemId  : 'detail',
						disabled: true,
						handler : function() {
							me.winLog.show();
						}
					}
				]
			})
		});

		// *************************************************************************************
		// Event Detail Window
		// *************************************************************************************
		me.winLog = Ext.create('Ext.window.Window', {
			title      : 'Log Event Details',
			width      : 500,
			closeAction: 'hide',
			items      : [
				{
					xtype     : 'form',
					bodyStyle : 'padding: 10px;',
					autoWidth : true,
					border    : false,
					hideLabels: true,
					defaults  : { labelWidth: 89, anchor: '100%',
						layout              : { type: 'hbox', defaultMargins: {top: 0, right: 5, bottom: 0, left: 0} }
					},
					items     : [
						{ xtype: 'textfield', hidden: true, name: 'id'},
						{ fieldLabel: 'Date', xtype: 'displayfield', name: 'date'},
						{ fieldLabel: 'Event', xtype: 'displayfield', name: 'event'},
						{ fieldLabel: 'User', xtype: 'displayfield', name: 'user'},
						{ fieldLabel: 'Facility', xtype: 'displayfield', name: 'facility'},
						{ fieldLabel: 'Comments', xtype: 'displayfield', name: 'comments'},
						{ fieldLabel: 'user Notes', xtype: 'displayfield', name: 'user_notes'},
						{ fieldLabel: 'Patient ID', xtype: 'displayfield', name: 'patient_id'},
						{ fieldLabel: 'Success', xtype: 'displayfield', name: 'success'},
						{ fieldLabel: 'Check Sum', xtype: 'displayfield', name: 'checksum'},
						{ fieldLabel: 'CRT USER', xtype: 'displayfield', name: 'crt_user'}
					]
				}
			],
			buttons    : [
				{
					text   : 'Close',
					handler: function() {
						this.up('window').hide();
					}
				}
			]
		});
		me.pageBody = [ me.logGrid ];
		me.callParent(arguments);
	}, // end of initComponent

	onItemclick   : function(view, record) {
		var form = this.winLog.down('form'),
			editBtn = this.logGrid.down('toolbar').getComponent('detail');
		form.getForm().loadRecord(record);
		editBtn.enable();
	},
	onItemdblclick: function(view, record) {
		var form = this.winLog.down('form');
		form.getForm().loadRecord(record);
		this.winLog.show();
	},
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive      : function(callback) {
		this.logStore.load();
		callback(true);
	}
});