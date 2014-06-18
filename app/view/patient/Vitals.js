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

Ext.define('App.view.patient.Vitals', {
	extend: 'Ext.panel.Panel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'App.ux.form.fields.DateTime'
	],
	alias: 'widget.vitalspanel',
	title: i18n('vitals'),
	layout: 'border',
	bodyPadding: 5,
	items: [
		{
			xtype: 'container',
			height: 100,
			region: 'north',
			itemId: 'vitalsBlocks',
			layout: {
				type: 'hbox',
				align: 'stretch'
			},
			defaults: {
				xtype: 'container',
				cls: 'latest-vitals-items',
				margin: '0 5 5 5',
				width: 130
			},
			items: [
				{
					itemId: 'bpBlock',
					margin: '0 5 5 0',
					html: '<p class="title">' + i18n('bp') + '</p><p class="value">--/--</p><p class="extra">' + i18n('systolic') + '/' + i18n('diastolic') + '</p>'
				},
				{
					itemId: 'tempBlock',
					html: '<p class="title">' + i18n('temp') + '</p><p class="value">--</p><p class="extra">--</p>'
				},
				{
					itemId: 'weighBlock',
					html: '<p class="title">' + i18n('weight') + '</p><p class="value">--</p>'
				},
				{
					itemId: 'heightBlock',
					html: '<p class="title">' + i18n('height') + '</p><p class="value">--</p>'
				},
				{
					itemId: 'bmiBlock',
					html: '<p class="title">' + i18n('bmi') + '</p><p class="value">--</p><p class="extra">--</p>'
				},
				{
					itemId: 'notesBlock',
					margin: '0 5 5 5',
					html: '<p class="title">' + i18n('notes') + '</p><p class="value" style="text-align: left"> -- </p><p class="extra">--</p>',
					flex: 1
				}
			]
		},
		{
			xtype: 'grid',
			region: 'center',
			flex: 1,
			columnLines: true,
			itemId: 'historyGrid',
			multiSelect: true,
			plugins: [
				{
					ptype: 'rowediting'
				}
			],
			viewConfig: {
				getRowClass: function(record, rowIndex, rowParams, store){
					return record.data.auth_uid == 0 ? 'unsignedVital' : '';
				}
			},
			columns: [
				{
					xtype:'datecolumn',
					text: i18n('date'),
					dataIndex: 'date',
					format: 'Y-m-d g:i a',
					width: 180,
					editor:{
						xtype: 'mitos.datetime',
						timeFormat: 'g:i a'
					}
				},
				{
					text: i18n('bp'),
					columns:[
						{
							text: i18n('systolic'),
							dataIndex: 'bp_systolic',
							width: 65,
							editor: {
								xtype: 'textfield',
								vtype: 'numeric'
							}
						},
						{
							text: i18n('diastolic'),
							dataIndex: 'bp_diastolic',
							width: 65,
							editor: {
								xtype: 'textfield',
								vtype: 'numeric'
							}
						}
					]
				},
				{
					text: i18n('temp'),
					dataIndex: 'temp_f',
					width: 70,
					hidden: g('units_of_measurement') == 'metric',
					editor: {
						xtype: 'textfield',
						itemId: 'vitalTempFField',
						vtype: 'numericWithDecimal',
						enableKeyEvents: true
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v + '&deg;F'
					}
				},
				{
					text: i18n('temp'),
					dataIndex: 'temp_c',
					width: 70,
					hidden: g('units_of_measurement') != 'metric',
					editor: {
						xtype: 'textfield',
						itemId: 'vitalTempCField',
						vtype: 'numericWithDecimal',
						enableKeyEvents: true
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v + '&deg;C'
					}
				},
				{
					text: i18n('temp_location'),
					dataIndex: 'temp_location',
					editor: {
						xtype: 'gaiaehr.combo',
						list: 62
					}
				},
				{
					text: i18n('weight_lbs'),
					dataIndex: 'weight_lbs',
					width: 80,
					hidden: g('units_of_measurement') == 'metric',
					editor: {
						xtype: 'textfield',
						itemId: 'vitalWeightLbsField',
						vtype: 'numericWithSlash',
						enableKeyEvents: true
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v + ' lbs/oz'
					}
				},
				{
					text: i18n('weight'),
					dataIndex: 'weight_kg',
					width: 80,
					hidden: g('units_of_measurement') != 'metric',
					editor: {
						xtype: 'textfield',
						itemId: 'vitalWeightKgField',
						vtype: 'numericWithDecimal',
						enableKeyEvents: true
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v + ' kg'
					}
				},
				{
					text: i18n('height_in'),
					dataIndex: 'height_in',
					width: 70,
					hidden: g('units_of_measurement') == 'metric',
					editor: {
						xtype: 'textfield',
						itemId: 'vitalHeightInField',
						vtype: 'numericWithDecimal',
						enableKeyEvents: true
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v + ' in'
					}
				},
				{
					text: i18n('height_cm'),
					dataIndex: 'height_cm',
					width: 70,
					hidden: g('units_of_measurement') != 'metric',
					editor: {
						xtype: 'textfield',
						itemId: 'vitalHeightCmField',
						vtype: 'numericWithDecimal',
						enableKeyEvents: true
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v + ' cm'
					}
				},
				{
					text: i18n('pulse'),
					dataIndex: 'pulse',
					width: 60,
					editor: {
						xtype: 'textfield',
						vtype: 'numeric'
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v;
					}
				},
				{
					text: i18n('respiration'),
					dataIndex: 'respiration',
					editor: {
						xtype: 'textfield',
						vtype: 'numeric'
					},
					renderer:function(v){
						return v == 0 || v == null ? '' : v;
					}
				},
				//				{
				//					text: i18n('oxygen_saturation'),
				//					dataIndex: 'oxygen_saturation'
				//				},
				//				{
				//					text: i18n('head_circumference_in'),
				//					dataIndex: 'head_circumference_in',
				//					width: 150
				//				},
				//				{
				//					text: i18n('head_circumference_cm'),
				//					dataIndex: 'head_circumference_cm',
				//					width: 150,
				//					hidden: true
				//				},
				//				{
				//					text: i18n('waist_circumference_in'),
				//					dataIndex: 'waist_circumference_in',
				//					width: 150
				//				},
				//				{
				//					text: i18n('waist_circumference_cm'),
				//					dataIndex: 'waist_circumference_cm',
				//					width: 150,
				//					hidden: true
				//				},
				{
					text: i18n('bmi'),
					dataIndex: 'bmi',
					width: 50
				},
				//				{
				//					text: i18n('bmi_status'),
				//					dataIndex: 'bmi_status'
				//				}
				{
					text: i18n('other_notes'),
					dataIndex: 'other_notes',
					flex: 1,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					text: i18n('administer_by'),
					dataIndex: 'administer_by'
				},
				{
					text: i18n('authorized_by'),
					dataIndex: 'authorized_by'
				}
			],
			tbar: [
				'->',
				{
					text: i18n('vitals'),
					iconCls: 'icoAdd',
					itemId: 'vitalAddBtn',
					action: 'encounterRecordAdd'
				},
				'-',
				{
					text: i18n('sign'),
					icon: 'resources/images/icons/pen.png',
					//disabled: true,
					itemId: 'vitalSignBtn',
					action: 'encounterRecordAdd'
				}
			]

		}
	]
});
