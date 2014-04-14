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
		'Ext.grid.feature.Grouping',
		'Ext.selection.CheckboxModel',
		'App.ux.combo.Combo',
		'App.ux.LiveRadiologySearch'
	],
	xtype: 'patientradorderspanel',
	title: i18n('xray_ct_orders'),
	store: Ext.create('App.store.patient.PatientsOrders', {
		groupField: 'date_ordered',
		remoteFilter: true,
		filters: [
			{
				property: 'order_type',
				value: 'rad'
			}
		],
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
	features: [
		{
			ftype: 'grouping'
		}
	],
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
					tooltip: i18n('remove')
//					scope: me,
//					handler: me.onRemoveClick
				}
			]
		},
		{
			header: i18n('status'),
			width: 75,
			dataIndex: 'status',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 40
			}
//			renderer: me.statusRenderer
		},
		{
			xtype: 'datecolumn',
			header: i18n('date_ordered'),
			width: 100,
			dataIndex: 'date_ordered',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield'
			}
		},
		{
			header: i18n('code'),
			width: 100,
			dataIndex: 'code'
		},
		{
			header: i18n('description'),
			flex: 1,
			dataIndex: 'description',
			editor: {
				xtype: 'radiologylivetsearch'
//				listeners: {
//					scope: me,
//					select: me.onLoincSearchSelect
//				}
			}
		},
		{
			header: i18n('notes'),
			flex: 1,
			dataIndex: 'note',
			editor: {
				xtype: 'textfield'
			}
		},
		{
			header: i18n('priority'),
			width: 100,
			dataIndex: 'priority',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 98
			}
		},
		{
			xtype: 'datecolumn',
			header: i18n('date_collected'),
			width: 100,
			dataIndex: 'date_collected',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield'
			}
		}
	],
	tbar: [
//		me.eRadBtn =
		{
			text: i18n('eRad'),
			iconCls: 'icoSend'
//			scope: me,
//			handler: function(){
//				alert('TODO...');
//			}
		},
		'-',
		'->',
		'-',
		{
			xtype: 'button',
			text: i18n('new_order'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd'
//			scope: me,
//			handler: me.onAddOrder
		},
		'-',
//		me.radPrintBtn =
		{
			text: i18n('print'),
			iconCls: 'icoPrint',
			disabled: true,
			margin: '0 5 0 0'
//			scope: this,
//			handler: me.onPrintOrder
		}
	]
//	listeners: {
//		scope: me,
//		selectionchange: me.onSelectionChange
//	}
});