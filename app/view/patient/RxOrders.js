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
		'App.ux.grid.RowFormEditing',
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
	itemId: 'RxOrderGrid',
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
			ptype: 'rowformediting',
			clicksToEdit: 2,
			items: [
				{
					xtype: 'container',
					layout: {
						type: 'hbox',
						align: 'stretch'
					},
					items: [
						{
							xtype: 'container',
							layout: 'anchor',
							items: [
								{
									xtype: 'datefield',
									fieldLabel: i18n('order_date'),
									format: 'Y-m-d',
									name: 'date_ordered',
									allowBlank: false,
									margin: '0 0 5 0'
								},
								{
									xtype: 'rxnormlivetsearch',
									itemId: 'RxNormOrderLiveSearch',
									hideLabel: false,
									fieldLabel: i18n('medication'),
									width: 700,
									name: 'STR',
									displayField: 'STR',
									valueField: 'STR',
									allowBlank: false
								},
								{
									xtype: 'container',
									margin: '5 0',
									layout: {
										type: 'hbox'
									},
									items: [
										{
											xtype: 'numberfield',
											width: 160,
											fieldLabel: i18n('dispense'),
											minValue: 1,
											name: 'dispense',
											allowBlank: false
										},
										{
											xtype: 'numberfield',
											width: 130,
											fieldLabel: i18n('refill'),
											labelAlign: 'right',
											labelWidth: 70,
											maxValue: 99,
											minValue: 0,
											name: 'refill',
											vtype: 'numeric',
											allowBlank: false
										},
										{
											xtype: 'encountericdscombo',
											itemId: 'RxEncounterDxCombo',
											fieldLabel: i18n('dx'),
											labelAlign: 'right',
											labelWidth: 70,
											width: 405,
											name: 'dxs'
										}
									]
								},
								{
									xtype: 'livesigssearch',
									width: 700,
									fieldLabel: 'Instructions',
									name: 'directions',
									maxLength: 300,
									allowBlank: false
								},
								{
									xtype: 'textfield',
									width: 700,
									fieldLabel: i18n('notes_to_Pharmacist'),
									name: 'notes'
								}
							]
						},
						{
							xtype: 'container',
							layout: 'anchor',
							items: [
								{
									xtype: 'checkboxfield',
									fieldLabel: i18n('daw'),
									tooltip: i18n('dispensed_as_written'),
									width: 95,
									labelWidth: 70,
									labelAlign: 'right',
									name: 'daw',
									margin: '25 0 5 0'
								},
								{
									xtype: 'datefield',
									fieldLabel: i18n('begin_date'),
									labelWidth: 70,
									labelAlign: 'right',
									width: 258,
									format: 'Y-m-d',
									name: 'begin_date',
									margin: '0 0 5 0',
									allowBlank: false
								},
								{
									xtype: 'datefield',
									fieldLabel: i18n('end_date'),
									labelWidth: 70,
									labelAlign: 'right',
									format: 'Y-m-d',
									width: 258,
									name: 'end_date'
								}
							]
						},
						{
							xtype: 'fieldset',
							title: i18n('active_drug_allergies'),
							html: i18n('none'),
							margin: '25 0 5 10',
							flex: 1
						}
					]
				}
			]
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
				}
			]
		},
		{
			xtype: 'datecolumn',
			header: i18n('date_ordered'),
			dataIndex: 'date_ordered',
			format: 'Y-m-d'
		},
		{
			header: i18n('medication'),
			flex: 1,
			dataIndex: 'STR'
		},
		{
			header: i18n('daw'),
			width: 40,
			dataIndex: 'daw',
			tooltip: i18n('dispensed_as_written'),
			renderer: function(v){
				return app.boolRenderer(v);
			}
		},
//		{
//			header: i18n('dose'),
//			width: 115,
//			dataIndex: 'dose'
//		},
//		{
//			header: i18n('route'),
//			width: 90,
//			dataIndex: 'route'
//		},
//		{
//			header: i18n('form'),
//			width: 70,
//			dataIndex: 'form'
//		},
		{
			header: i18n('dispense'),
			width: 60,
			dataIndex: 'dispense'
		},
		{
			header: i18n('refill'),
			width: 50,
			dataIndex: 'refill'
		},
		{
			header: i18n('instructions'),
			flex: 1,
			dataIndex: 'directions'
		},
		{
			header: i18n('related_dx'),
			width: 200,
			dataIndex: 'dxs',
			renderer: function(v){
				return v == false || v == 'false' || v[0] == false ? '' : v;
			}
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
			dataIndex: 'end_date'
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
		},
		'-',
		{
			text: i18n('clone_order'),
			iconCls: 'icoAdd',
			disabled: true,
			margin: '0 5 0 0',
			action: 'encounterRecordAdd',
			itemId: 'cloneRxOrderBtn'
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
});