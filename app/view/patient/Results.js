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

Ext.define('App.view.patient.Results', {
	extend: 'Ext.panel.Panel',
	requires:[
		'Ext.grid.plugin.CellEditing',
		'App.store.patient.PatientsOrders'
	],
	title: i18n('results'),
	xtype: 'patientresultspanel',
	layout: 'border',
	items: [
		{
			xtype: 'grid',
			action: 'orders',
			region: 'center',
			split: true,
			columnLines: true,
			store: Ext.create('App.store.patient.PatientsOrders', {
			    remoteFilter: true
		    }),
			columns: [
				{
					xtype: 'actioncolumn',
					width: 25,
					items: [
						{
							icon: 'resources/images/icons/blueInfo.png',  // Use a URL in the icon config
							tooltip: 'Get Info',
							handler: function(grid, rowIndex, colIndex, item, e, record){
								App.app.getController('InfoButton').doGetInfo(record.data.code, record.data.code_type, record.data.description);
							}
						}
					]
				},
				{
					header: i18n('orders'),
					dataIndex: 'description',
					menuDisabled: true,
					resizable: false,
					flex: 1
				},
				{
					header: i18n('status'),
					dataIndex: 'status',
					menuDisabled: true,
					resizable: false,
					width: 60
				}
			]
		},
		{
			xtype: 'form',
			title: i18n('order_result'),
			region: 'south',
			height: 450,
			frame: true,
			split: true,
			layout: {
				type: 'border'
			},
			tools: [
				{
					xtype: 'button',
					text: i18n('view_document'),
					action: 'orderDocumentViewBtn'
				}
			],
			items: [
				{
					xtype: 'panel',
					title: i18n('report_info'),
					region: 'west',
					collapsible: true,
					autoScroll: true,
					width: 260,
					bodyPadding: 5,
					split: true,
					layout: {
						type: 'vbox',
						align: 'stretch'
					},
					items: [
						{
							xtype: 'fieldset',
							title: i18n('report_info'),
							defaults: {
								xtype: 'textfield',
								anchor: '100%'
							},
							layout: 'anchor',
							items: [
								{
									xtype: 'datefield',
									fieldLabel: i18n('report_date'),
									name: 'result_date',
									format: 'Y-m-d'
								},
								{
									fieldLabel: i18n('report_number'),
									name: 'lab_order_id'
								},
								{
									fieldLabel: i18n('status'),
									name: 'result_status'
								},
								{
									fieldLabel: i18n('observation_date'),
									name: 'observation_date'
								},
								{
									fieldLabel: i18n('specimen'),
									name: 'specimen_text'
								},
								{
									xtype: 'textareafield',
									fieldLabel: i18n('specimen_notes'),
									name: 'specimen_notes',
									height: 50
								},
								{
									xtype: 'filefield',
									labelAlign: 'top',
									fieldLabel: i18n('upload_document'),
									action: 'orderresultuploadfield',
									submitValue: false
								}
							]
						},
						{
							xtype: 'fieldset',
							title: i18n('laboratory_info'),
							defaults: {
								xtype: 'textfield',
								anchor: '100%'
							},
							layout: 'anchor',
							margin: 0,
							collapsible: true,
							collapsed: true,
							items: [
								{
									fieldLabel: i18n('name'),
									name: 'lab_name'
								},
								{
									xtype: 'textareafield',
									fieldLabel: i18n('address'),
									name: 'lab_address',
									height: 50
								}
							]
						}
					]
				},
				{
					xtype: 'grid',
					action: 'observations',
					flex: 1,
					region: 'center',
					split: true,
					columnLines: true,
					plugins: [
						{
							ptype: 'cellediting',
							clicksToEdit: 1
						}
					],
					columns: [
						{
							xtype: 'actioncolumn',
							width: 25,
							items: [
								{
									icon: 'resources/images/icons/blueInfo.png',  // Use a URL in the icon config
									tooltip: 'Get Info',
									handler: function(grid, rowIndex, colIndex, item, e, record){
										App.app.getController('InfoButton').doGetInfo(record.data.code, record.data.code_type, record.data.code_text);
									}
								}
							]
						},
						{
							text: i18n('name'),
							menuDisabled: true,
							dataIndex: 'code_text',
							width: 350
						},
						{
							text: i18n('value'),
							menuDisabled: true,
							dataIndex: 'value',
							width: 180,
							editor: {
								xtype: 'textfield'
							},
							renderer: function(v, meta, record){
								var red = ['LL', 'HH', '>', '<', 'AA', 'VS'],
									orange = ['L', 'H', 'A', 'W', 'MS'],
									blue = ['B', 'S', 'U', 'D', 'R', 'I'],
									green = ['N'];

								if(Ext.Array.contains(green, record.data.abnormal_flag)){
									return '<span style="color:green;">' + v + '</span>';
								}else if(Ext.Array.contains(blue, record.data.abnormal_flag)){
									return '<span style="color:blue;">' + v + '</span>';
								}else if(Ext.Array.contains(orange, record.data.abnormal_flag)){
									return '<span style="color:orange;">' + v + '</span>';
								}else if(Ext.Array.contains(red, record.data.abnormal_flag)){
									return '<span style="color:red;">' + v + '</span>';
								}else{
									return v;
								}
							}
						},
						{
							text: i18n('units'),
							menuDisabled: true,
							dataIndex: 'units',
							width: 75,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							text: i18n('abnormal'),
							menuDisabled: true,
							dataIndex: 'abnormal_flag',
							width: 75,
							editor: {
								xtype: 'textfield'
							},
							renderer: function(v, attr){
								var red = ['LL', 'HH', '>', '<', 'AA', 'VS'],
									orange = ['L', 'H', 'A', 'W', 'MS'],
									blue = ['B', 'S', 'U', 'D', 'R', 'I'],
									green = ['N'];

								if(Ext.Array.contains(green, v)){
									return '<span style="color:green;">' + v + '</span>';
								}else if(Ext.Array.contains(blue, v)){
									return '<span style="color:blue;">' + v + '</span>';
								}else if(Ext.Array.contains(orange, v)){
									return '<span style="color:orange;">' + v + '</span>';
								}else if(Ext.Array.contains(red, v)){
									return '<span style="color:red;">' + v + '</span>';
								}else{
									return v;
								}
							}
						},
						{
							text: i18n('range'),
							menuDisabled: true,
							dataIndex: 'reference_rage',
							width: 150,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							text: i18n('notes'),
							menuDisabled: true,
							dataIndex: 'notes',
							width: 300,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							text: i18n('status'),
							menuDisabled: true,
							dataIndex: 'observation_result_status',
							width: 60,
							editor: {
								xtype: 'textfield'
							}
						}
					]
				}
			],
			buttons: [
				{
					text: i18n('reset'),
					action: 'orderResultResetBtn'
				},
				{
					text: i18n('save'),
					action: 'orderResultSaveBtn'
				}
			]
		}
	]
});