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
	title: _('vitals'),
	layout: 'border',
	bodyPadding: 5,
	items: [

		// VITALS HEADER BLOCKS
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
					html: '<p class="title">' + _('bp') + '</p><p class="value">--/--</p><p class="extra">' + _('systolic') + '/' + _('diastolic') + '</p>'
				},
				{
					itemId: 'tempBlock',
					html: '<p class="title">' + _('temp') + '</p><p class="value">--</p><p class="extra">--</p>'
				},
				{
					itemId: 'weighBlock',
					html: '<p class="title">' + _('weight') + '</p><p class="value">--</p>'
				},
				{
					itemId: 'heightBlock',
					html: '<p class="title">' + _('height') + '</p><p class="value">--</p>'
				},
				{
					itemId: 'bmiBlock',
					html: '<p class="title">' + _('bmi') + '</p><p class="value">--</p><p class="extra">--</p>'
				},
				{
					itemId: 'notesBlock',
					margin: '0 5 5 5',
					html: '<p class="title">' + _('notes') + '</p><p class="value" style="text-align: left"> -- </p><p class="extra">--</p>',
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
					return record.data.auth_uid === 0 ? 'unsignedVital' : '';
				}
			},
			tbar: [
				{
					xtype:'button',
					icon:'resources/images/icons/blueInfo.png',
					focusCls:'',
					handler:function(){
						App.app.getController('InfoButton').doGetInfoByUrl('https://vsearch.nlm.nih.gov/vivisimo/cgi-bin/query-meta?v%3Aproject=medlineplus&query=vitals+signs&x=0&y=0');
					}
				},
				'->',
				{
					text: _('vitals'),
					iconCls: 'icoAdd',
					itemId: 'vitalAddBtn',
					action: 'encounterRecordAdd'
				},
				'-',
				{
					text: _('sign'),
					icon: 'resources/images/icons/pen.png',
					itemId: 'vitalSignBtn',
					action: 'encounterRecordAdd'
				}
			]

		}
	],
	initComponent:function(){
		var me = this;

		var columns = [
			{
				xtype:'datecolumn',
				text: _('date'),
				dataIndex: 'date',
				format: 'Y-m-d g:i a',
				width: 180,
				editor:{
					xtype: 'mitos.datetime',
					timeFormat: 'g:i a'
				}
			},
			{
				text: _('bp'),
				columns:[
					{
						text: _('systolic'),
						dataIndex: 'bp_systolic',
						width: 65,
						editor: {
							xtype: 'textfield',
							vtype: 'numeric'
						}
					},
					{
						text: _('diastolic'),
						dataIndex: 'bp_diastolic',
						width: 65,
						editor: {
							xtype: 'textfield',
							vtype: 'numeric'
						}
					}
				]
			}
		];

		if(g('units_of_measurement') != 'metric'){
			columns.push({
				text: _('temp'),
				dataIndex: 'temp_f',
				width: 70,
				editor: {
					xtype: 'textfield',
					itemId: 'vitalTempFField',
					vtype: 'numericWithDecimal',
					enableKeyEvents: true
				},
				renderer:function(v){
					return v === 0 || v === null ? '' : v + '&deg;F'
				}
			});
		}else{
			columns.push({
				text: _('temp'),
				dataIndex: 'temp_c',
				width: 70,
				editor: {
					xtype: 'textfield',
					itemId: 'vitalTempCField',
					vtype: 'numericWithDecimal',
					enableKeyEvents: true
				},
				renderer:function(v){
					return v === 0 || v === null ? '' : v + '&deg;C'
				}
			});
		}

		columns.push({
			text: _('temp_location'),
			dataIndex: 'temp_location',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 62
			}
		});

		if(g('units_of_measurement') != 'metric'){
			columns.push({
				text: _('weight_lbs'),
				dataIndex: 'weight_lbs',
				width: 80,
				editor: {
					xtype: 'textfield',
					itemId: 'vitalWeightLbsField',
					vtype: 'numericWithSlash',
					enableKeyEvents: true
				},
				renderer:function(v){
					return v === 0 || v === null ? '' : v + ' lbs/oz'
				}
			});
			columns.push({
				text: _('height_in'),
				dataIndex: 'height_in',
				width: 70,
				editor: {
					xtype: 'textfield',
					itemId: 'vitalHeightInField',
					vtype: 'numericWithDecimal',
					enableKeyEvents: true
				},
				renderer:function(v){
					return v === 0 || v === null ? '' : v + ' in'
				}
			});
		}else{
			columns.push({
				text: _('weight'),
				dataIndex: 'weight_kg',
				width: 80,
				editor: {
					xtype: 'textfield',
					itemId: 'vitalWeightKgField',
					vtype: 'numericWithDecimal',
					enableKeyEvents: true
				},
				renderer:function(v){
					return v === 0 || v === null ? '' : v + ' kg'
				}
			});
			columns.push({
				text: _('height_cm'),
				dataIndex: 'height_cm',
				width: 70,
				editor: {
					xtype: 'textfield',
					itemId: 'vitalHeightCmField',
					vtype: 'numericWithDecimal',
					enableKeyEvents: true
				},
				renderer:function(v){
					return v === 0 || v === null ? '' : v + ' cm'
				}
			});
		}

		columns.push({
			text: _('pulse'),
			dataIndex: 'pulse',
			width: 60,
			editor: {
				xtype: 'textfield',
				vtype: 'numeric'
			},
			renderer:function(v){
				return v === 0 || v === null ? '' : v;
			}
		});

		columns.push({
			text: _('respiration'),
			dataIndex: 'respiration',
			editor: {
				xtype: 'textfield',
				vtype: 'numeric'
			},
			renderer:function(v){
				return v === 0 || v === null ? '' : v;
			}
		});

		columns.push({
			text: _('bmi'),
			dataIndex: 'bmi',
			width: 50
		});

		columns.push({
			text: _('other_notes'),
			dataIndex: 'other_notes',
			flex: 1,
			editor: {
				xtype: 'textfield'
			}
		});

		columns.push({
			text: _('administer_by'),
			dataIndex: 'administer_by'
		});

		columns.push({
			text: _('authorized_by'),
			dataIndex: 'authorized_by'
		});

		me.items[1].columns = columns;

		me.callParent();
	}
});
