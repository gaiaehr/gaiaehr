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

Ext.define('App.view.patient.RxOrders', {
	extend: 'Ext.grid.Panel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'Ext.grid.feature.Grouping',
		'Ext.selection.CheckboxModel',
		'App.ux.combo.PrescriptionHowTo',
		'App.ux.combo.PrescriptionTypes',
		'App.ux.combo.EncounterICDS',
		'App.ux.LiveSigsSearch',
		'App.ux.LiveRXNORMSearch'
	],
	xtype: 'patientrxorderspanel',
	title: i18n('rx_orders'),
	columnLines: true,
	store: Ext.create('App.store.patient.Medications', {
		groupField: 'date_ordered',
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
			xtype: 'datecolumn',
			header: i18n('date_ordered'),
			dataIndex: 'date_ordered',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield',
				format: 'Y-m-d'
			}
		},
		{
			header: i18n('medication'),
			flex: 1,
			dataIndex: 'STR',
			editor: {
				xtype: 'rxnormlivetsearch',
				itemId: 'RxNormOrderLiveSearch'
				//				listeners: {
				//					scope: me,
				//					select: me.onRxnormLiveSearchSelect
				//				}
			}
		},
		{
			header: i18n('dose'),
			width: 125,
			dataIndex: 'dose',
			editor: {
				xtype: 'textfield'
			}
		},
		{
			header: i18n('route'),
			width: 100,
			dataIndex: 'route',
			editor: {
				xtype: 'mitos.prescriptionhowto'
			}
		},
		{
			header: i18n('form'),
			width: 75,
			dataIndex: 'form',
			editor: {
				xtype: 'mitos.prescriptiontypes'
			}
		},
		{
			header: i18n('instructions') + ' (Sig)',
			width: 150,
			dataIndex: 'prescription_when',
			editor: {
				xtype: 'livesigssearch'
			}
		},
		{
			header: i18n('dispense'),
			width: 60,
			dataIndex: 'dispense',
			editor: {
				xtype: 'numberfield'
			}
		},
		{
			header: i18n('refill'),
			width: 50,
			dataIndex: 'refill',
			editor: {
				xtype: 'numberfield'
			}
		},
		{
			header: i18n('related_dx'),
			width: 150,
			dataIndex: 'ICDS',
			editor: {
				xtype: 'encountericdscombo',
				itemId: 'rxEncounterDxLiveSearch'
			} //me.encounderIcdsCodes
		},
		{
			xtype: 'datecolumn',
			format: 'Y-m-d',
			header: i18n('begin_date'),
			width: 75,
			dataIndex: 'begin_date'
		},
		{
			xtype: 'datecolumn',
			header: i18n('end_date'),
			width: 75,
			format: 'Y-m-d',
			dataIndex: 'end_date',
			editor: {
				xtype: 'datefield',
				format: 'Y-m-d'
			}
		}

	],
	tbar: [
		'->',
		'-',
		{
			text: i18n('new_order'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd',
			itemId: 'newRxOrderBtn'
			//			scope: me,
			//			handler: me.onNewPrescription

		},
		'-',
		{
			text: i18n('clone_order'),
			iconCls: 'icoAdd',
			disabled: true,
			margin: '0 5 0 0',
			action: 'encounterRecordAdd',
			itemId: 'cloneRxOrderBtn'
			//			scope: me,
			//			handler: me.onClonePrescriptions
		},
		'-',
		{
			text: i18n('print'),
			iconCls: 'icoPrint',
			disabled: true,
			margin: '0 5 0 0',
			itemId: 'printRxOrderBtn'
		}
	]
	//	listeners: {
	//		scope: me,
	//		selectionchange: me.onSelectionChange
	//	}
});