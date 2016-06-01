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

Ext.define('App.view.patient.RadOrders', {
	extend: 'Ext.grid.Panel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'Ext.selection.CheckboxModel',
		'App.ux.combo.Combo',
		'App.ux.LiveRadsSearch'
	],
	xtype: 'patientradorderspanel',
	title: _('xray_ct_orders'),
	columnLines: true,
	tabConfig: {
		cls: 'order-tab'
	},
	itemId: 'RadOrders',
	store: Ext.create('App.store.patient.PatientsOrders', {
		storeId: 'RadOrderStore',
		remoteFilter: true,
		pageSize: 200,
		sorters: [
			{
				property: 'date_ordered',
				direction: 'DESC'
			}
		]
	}),
	selModel: Ext.create('Ext.selection.CheckboxModel', {
		showHeaderCheckbox: false
	}),
	plugins: [
		{
			ptype: 'rowediting',
			clicksToEdit: 2
		}
	],
	columns: [
		{
			xtype: 'actioncolumn',
			width: 20,
			items: [
				{
					icon: 'resources/images/icons/cross.png',
					tooltip: _('remove')
				}
			]
		},
		{
			header: _('order#'),
			width: 60,
			dataIndex: 'id'
		},
		{
			header: _('status'),
			width: 75,
			dataIndex: 'status',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 40
			},
			renderer: function(v){
				return app.getController('patient.RadOrders').radOrdersGridStatusColumnRenderer(v)
			}
		},
		{
			xtype: 'datecolumn',
			header: _('date_ordered'),
			width: 100,
			dataIndex: 'date_ordered',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield'
			}
		},
		{
			header: _('code'),
			width: 100,
			dataIndex: 'code'
		},
		{
			header: _('description'),
			flex: 1,
			dataIndex: 'description',
			editor: {
				xtype: 'radslivetsearch',
				itemId: 'radOrderliveSearch'
			}
		},
		{
			header: _('notes'),
			flex: 1,
			dataIndex: 'note',
			editor: {
				xtype: 'textfield'
			}
		},
		{
			header: _('priority'),
			width: 100,
			dataIndex: 'priority',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 98
			}
		},
		{
			xtype: 'datecolumn',
			header: _('date_collected'),
			width: 100,
			dataIndex: 'date_collected',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield'
			}
		}
	],
	tbar: [
		{
			text: _('eRad'),
			iconCls: 'icoSend',
			itemId: 'electronicRadOrderBtn'
		},
		'-',
		'->',
		'-',
		{
			xtype: 'button',
			text: _('new_order'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd',
			itemId: 'newRadOrderBtn'
		},
		'-',
		{
			text: _('print'),
			iconCls: 'icoPrint',
			disabled: true,
			margin: '0 5 0 0',
			itemId: 'printRadOrderBtn'
		}
	]
});
