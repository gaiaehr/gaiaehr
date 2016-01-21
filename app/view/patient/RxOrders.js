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
		'App.ux.combo.MedicationInstructions',
		'App.ux.LiveRXNORMSearch',
		'App.ux.form.fields.plugin.HelpIcon'
	],
	xtype: 'patientrxorderspanel',
	title: _('rx_orders'),
	columnLines: true,
	tabConfig: {
		cls: 'order-tab'
	},
	itemId: 'RxOrderGrid',
	store: Ext.create('App.store.patient.RxOrders', {
		storeId: 'RxOrderStore',
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
			ptype: 'rowformediting',
			clicksToEdit: 2,
			items: [
				{
					xtype: 'container',
					layout: {
						type: 'hbox',
						align: 'stretch'
					},
					itemId: 'RxOrderGridFormContainer',
					items: [
						{
							xtype: 'container',
							layout: 'anchor',
							itemId: 'RxOrderGridFormContainerOne',
							items: [
								{
									xtype: 'datefield',
									fieldLabel: _('order_date'),
									format: 'Y-m-d',
									name: 'date_ordered',
									allowBlank: false,
									margin: '0 0 5 0'
								},
								{
									xtype: 'rxnormlivetsearch',
									itemId: 'RxNormOrderLiveSearch',
									hideLabel: false,
									fieldLabel: _('medication'),
									width: 700,
									name: 'STR',
									maxLength: 255,
									displayField: 'STR',
									valueField: 'STR',
									vtype: 'nonspecialcharactersrequired',
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
											width: 170,
											fieldLabel: _('dispense'),
											minValue: 0.001,
											maxValue: 99999,
											name: 'dispense',
											decimalPrecision: 3,
											maxLength: 10,
											allowBlank: false,
											fixPrecision: function(value){
												var me = this,
													nan = isNaN(value),
													precision = me.decimalPrecision,
                                                    num,
                                                    numArr;

												if(nan || !value){
													return nan ? '' : value;
												}else if(!me.allowDecimals || precision <= 0){
													precision = 0;
												}
												num = String(value);
												if(num.indexOf('.') !== -1){
													numArr = num.split(".");
													if(numArr.length == 1){
														return Number(num);
													}else{
														return Number(numArr[0] + "." + numArr[1].charAt(0) + numArr[1].charAt(1) + numArr[1].charAt(2));
													}
												}else{
													return Number(num);
												}
											}
										},
										{
											xtype: 'numberfield',
											width: 130,
											fieldLabel: _('days_supply'),
											labelAlign: 'right',
											labelWidth: 75,
											minValue: 1,
											maxValue: 630,
											allowDecimals: false,
											name: 'days_supply'
										},
										{
											xtype: 'numberfield',
											width: 100,
											fieldLabel: _('refill'),
											labelAlign: 'right',
											labelWidth: 40,
											maxValue: 99,
											minValue: 0,
											name: 'refill',
											vtype: 'numeric',
											allowBlank: false
										},
										{
											xtype: 'encountericdscombo',
											itemId: 'RxEncounterDxCombo',
											fieldLabel: _('dx'),
											labelAlign: 'right',
											labelWidth: 30,
											width: 295,
											name: 'dxs'
										}
									]
								},
								{
									xtype: 'medicationinstructionscombo',
									itemId: 'RxOrderMedicationInstructionsCombo',
									width: 700,
									fieldLabel: _('instructions'),
									name: 'directions',
									maxLength: 140,
									validateOnBlur: true,
									vtype: 'nonspecialcharactersrequired',
									allowBlank: false
								},
								{
									xtype: 'textfield',
									width: 680,
									fieldLabel: '*' + _('notes_to_pharmacist'),
									itemId: 'RxOrderGridFormNotesField',
									name: 'notes',
									plugins:[
										{
											ptype: 'helpicon',
											helpMsg: _('rx_notes_to_pharmacist_warning')
										}
									],
									maxLength: 210
								},
								{
									xtype: 'container',
									html: ' *' + _('rx_notes_to_pharmacist_warning'),
									margin: '0 0 0 100'
								}
							]
						},
						{
							xtype: 'container',
							layout: 'anchor',
							itemId: 'RxOrderGridFormContainerTwo',
							padding: '25 0 0 0',
							items: [
								{
									xtype: 'container',
									layout: 'hbox',
									items:[
										{
											xtype: 'checkboxfield',
											fieldLabel: _('daw'),
											tooltip: _('dispensed_as_written'),
											width: 90,
											labelWidth: 70,
											labelAlign: 'right',
											name: 'daw',
											margin: '0 0 5 0'
										},
										{
											xtype: 'checkboxfield',
											fieldLabel: _('is_comp'),
											tooltip: _('is_compound'),
											width: 85,
											labelWidth: 65,
											labelAlign: 'right',
											name: 'is_compound',
											itemId: 'RxOrderCompCheckBox',
											margin: '0 0 5 0'
										},
										{
											xtype: 'checkboxfield',
											fieldLabel: _('is_sply'),
											tooltip: _('is_supply'),
											width: 85,
											labelWidth: 65,
											labelAlign: 'right',
											name: 'is_supply',
											itemId: 'RxOrderSplyCheckBox',
											margin: '0 0 5 0'
										}
									]
								},
								{
									xtype: 'datefield',
									fieldLabel: _('begin_date'),
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
									fieldLabel: _('end_date'),
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
							title: _('active_drug_allergies'),
							html: _('none'),
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
					tooltip: _('remove')
				}
			]
		},
		{
			xtype: 'datecolumn',
			header: _('date_ordered'),
			dataIndex: 'date_ordered',
			format: 'Y-m-d'
		},
		{
			header: _('medication'),
			flex: 1,
			dataIndex: 'STR'
		},
		{
			header: _('daw'),
			width: 40,
			dataIndex: 'daw',
			tooltip: _('dispensed_as_written'),
			renderer: function(v){
				return app.boolRenderer(v);
			}
		},
		{
			header: _('dispense'),
			width: 60,
			dataIndex: 'dispense'
		},
		{
			header: _('refill'),
			width: 50,
			dataIndex: 'refill'
		},
		{
			header: _('instructions'),
			flex: 1,
			dataIndex: 'directions'
		},
		{
			header: _('related_dx'),
			width: 200,
			dataIndex: 'dxs',
			renderer: function(v){
				return v == false || v == 'false' || v[0] == false ? '' : v;
			}
		},
		{
			xtype: 'datecolumn',
			format: 'Y-m-d',
			header: _('begin_date'),
			width: 75,
			dataIndex: 'begin_date'
		},
		{
			xtype: 'datecolumn',
			header: _('end_date'),
			width: 75,
			format: 'Y-m-d',
			dataIndex: 'end_date'
		}
	],
	tbar: [
		'->',
		'-',
		{
			text: _('new_order'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd',
			itemId: 'newRxOrderBtn'
		},
		'-',
		{
			text: _('clone_order'),
			iconCls: 'icoAdd',
			disabled: true,
			margin: '0 5 0 0',
			action: 'encounterRecordAdd',
			itemId: 'cloneRxOrderBtn'
		},
		'-',
		{
			text: _('print'),
			iconCls: 'icoPrint',
			disabled: true,
			margin: '0 5 0 0',
			itemId: 'printRxOrderBtn'
		}
	]
});
