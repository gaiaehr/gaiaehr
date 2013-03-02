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

Ext.define('App.view.administration.Log',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelLog',
	uses : ['App.ux.GridPanel'],
	pageTitle : i18n('event_history_log'),
	initComponent : function()
	{
		var me = this;

        // *************************************************************************************
        // Log Data Store
        // *************************************************************************************
		me.logStore = Ext.create('App.store.administration.LogsModel');

		// *************************************************************************************
		// Create the GridPanel
		// *************************************************************************************
		me.logGrid = Ext.create('App.ux.GridPanel',
		{
			store : me.logStore,
			columns : [
			{
				text : 'id',
				sortable : false,
				dataIndex : 'id',
				hidden : true
			},
			{
				width : 120,
				text : 'Date',
				sortable : true,
				dataIndex : 'date'
			},
			{
				width : 160,
				text : 'User',
				sortable : true,
				dataIndex : 'user'
			},
			{
				width : 100,
				text : 'Event',
				sortable : true,
				dataIndex : 'event'
			},
			{
				flex : 1,
				text : 'Activity',
				sortable : true,
				dataIndex : 'comments'
			}],
			listeners :
			{
				scope : this,
				itemclick : me.onItemclick,
				itemdblclick : me.onItemdblclick
			},
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.logStore,
				displayInfo : true,
				emptyMsg : i18n('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				}),
				items : [
				{
					xtype : 'button',
					text : i18n('view_log_event_details'),
					iconCls : 'edit',
					itemId : 'detail',
					disabled : true,
					handler : function()
					{
						me.winLog.show();
					}
				}]
			})
		});

		// *************************************************************************************
		// Event Detail Window
		// *************************************************************************************
		me.winLog = Ext.create('Ext.window.Window',
		{
			title : i18n('log_event_details'),
			width : 500,
			closeAction : 'hide',
			items : [
			{
				xtype : 'form',
				bodyStyle : 'padding: 10px;',
				autoWidth : true,
				border : false,
				hideLabels : true,
				defaults :
				{
					labelWidth : 89,
					anchor : '100%',
					layout :
					{
						type : 'hbox',
						defaultMargins :
						{
							top : 0,
							right : 5,
							bottom : 0,
							left : 0
						}
					}
				},
				items : [
				{
					xtype : 'textfield',
					hidden : true,
					name : 'id'
				},
				{
					fieldLabel : i18n('date'),
					xtype : 'displayfield',
					name : 'date'
				},
				{
					fieldLabel : i18n('event'),
					xtype : 'displayfield',
					name : 'event'
				},
				{
					fieldLabel : i18n('user'),
					xtype : 'displayfield',
					name : 'user'
				},
				{
					fieldLabel : i18n('facility'),
					xtype : 'displayfield',
					name : 'facility'
				},
				{
					fieldLabel : i18n('comments'),
					xtype : 'displayfield',
					name : 'comments'
				},
				{
					fieldLabel : i18n('user_notes'),
					xtype : 'displayfield',
					name : 'user_notes'
				},
				{
					fieldLabel : i18n('patient_id'),
					xtype : 'displayfield',
					name : 'patient_id'
				},
				{
					fieldLabel : i18n('success'),
					xtype : 'displayfield',
					name : 'success'
				},
				{
					fieldLabel : i18n('check_sum'),
					xtype : 'displayfield',
					name : 'checksum'
				},
				{
					fieldLabel : i18n('crt_user'),
					xtype : 'displayfield',
					name : 'crt_user'
				}]
			}],
			buttons : [
			{
				text : i18n('close'),
				handler : function()
				{
					this.up('window').hide();
				}
			}]
		});
		me.pageBody = [me.logGrid];
		me.callParent(arguments);
	}, // end of initComponent

	onItemclick : function(view, record)
	{
		var form = this.winLog.down('form'), editBtn = this.logGrid.down('toolbar').getComponent('detail');
		form.getForm().loadRecord(record);
		editBtn.enable();
	},
	onItemdblclick : function(view, record)
	{
		var form = this.winLog.down('form');
		form.getForm().loadRecord(record);
		this.winLog.show();
	},
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		this.logStore.load();
		callback(true);
	}
}); 