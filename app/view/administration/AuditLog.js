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

Ext.define('App.view.administration.AuditLog', {
	extend: 'App.ux.RenderPanel',
	pageTitle: _('audit_log'),
	itemId: 'AuditLogPanel',

	initComponent: function(){
		var me = this;

		me.store = Ext.create('App.store.administration.TransactionLogs',{
			remoteFilter: true,
			remoteSort: true
		});

		me.pageBody = [

			Ext.create('Ext.grid.Panel', {
				itemId: 'AuditLogGrid',
				store: me.store,
				columns: [
					{
						width: 130,
						text: _('date'),
						dataIndex: 'date',
						renderer: Ext.util.Format.dateRenderer('Y-m-d g:i a')
					},
					{
						width: 200,
						text: _('user'),
						dataIndex: 'user_name'
					},
					{
						width: 200,
						text: _('patient'),
						dataIndex: 'patient_name'
					},
					{
						width: 100,
						text: _('event'),
						dataIndex: 'event'
					},
					{
						width: 200,
						text: _('table'),
						dataIndex: 'table_name'
					},
					{
						flex: 1,
						text: _('sql'),
						dataIndex: 'sql_string'
					},
					{
						flex: 1,
						text: _('data'),
						dataIndex: 'data'
					},
					{
						width: 60,
						text: _('valid'),
						dataIndex: 'is_valid',
						renderer: app.boolRenderer
					}
				],
				tbar: Ext.create('Ext.PagingToolbar', {
					store: me.store,
					displayInfo: true,
					plugins: Ext.create('Ext.ux.SlidingPager'),
					items: [
						'-',
						{
							xtype: 'datefield',
							name: 'from',
							itemId: 'AuditLogGridFromDateField',
							labelWidth: 35,
							width: 150,
							fieldLabel: _('from'),
							labelAlign: 'right',
							format: 'Y-m-d',
							allowBlank: false,
							value: new Date()  // defaults to today
						},
						{
							xtype: 'datefield',
							name: 'to',
							itemId: 'AuditLogGridToDateField',
							labelWidth: 30,
							width: 150,
							fieldLabel: _('to'),
							format: 'Y-m-d',
							labelAlign: 'right',
							allowBlank: false,
							value: new Date()  // defaults to today
						},
						{
							xtype: 'patienlivetsearch',
							itemId: 'AuditLogGridPatientLiveSearch',
							emptyText: _('patient_live_search') + '...',
							width: app.fullMode ? 300 : 250
						},
						{
							xtype: 'button',
							text: _('filter'),
							itemId: 'AuditLogGridFilterBtn'
						},
						{
							xtype: 'button',
							text: _('reset'),
							itemId: 'AuditLogGridResetBtn'
						}
					]
				})
			})
		];

		me.callParent(arguments);
	}

}); 