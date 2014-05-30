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

Ext.define('App.view.administration.HL7', {
	extend: 'App.ux.RenderPanel',
	requires: [
		//		'App.ux.grid.Button',
		'Ext.grid.plugin.RowEditing'
	],
	xtype: 'hl7serverspanel',
	pageTitle: i18n('hl7'),
	pageLayout: {
		type: 'vbox',
		align: 'stretch'
	},
	pageBody: [
		{
			xtype: 'grid',
			title: i18n('hl7_servers'),
			store: this.sStore = Ext.create('App.store.administration.HL7Servers'),
			itemId: 'hl7serversgrid',
			flex: 1,
			frame: true,
			columnLines: true,
			margin: '0 0 5 0',
			padding: 0,
			columns: [
				{
					text: i18n('online'),
					dataIndex: 'online',
					width: 50,
					renderer: function(v){
						return app.boolRenderer(v);
					}
				},
				{
					text: i18n('server_name'),
					dataIndex: 'server_name',
					width: 150
				},
				{
					text: i18n('server_ip'),
					dataIndex: 'ip',
					width: 110
				},
				{
					text: i18n('port'),
					dataIndex: 'port',
					width: 70
				},
				{
					text: i18n('allowed_messages'),
					dataIndex: 'allow_messages_string',
					flex: 1
				},
				{
					text: i18n('allowed_ips'),
					dataIndex: 'allow_ips_string',
					flex: 1
				},
				{
					xtype: 'actioncolumn',
					width: 50,
					items: [
						{
							icon: 'resources/images/icons/icoDotGreen.png',
							tooltip: i18n('start'),
							margin: '0 5 0 0',
							handler: function(grid, rowIndex, colIndex, item, e, record){
								App.Current.getController('administration.HL7').serverStartHandler(record);
							}
						},
						{
							icon: 'resources/images/icons/icoDotRed.png',
							tooltip: i18n('stop'),
							handler: function(grid, rowIndex, colIndex, item, e, record){
								App.Current.getController('administration.HL7').serverStopHandler(record);
							}
						}
					]
				}
				//				{
				//					xtype:'gridbutton',
				//					width: 120,
				//					items:[
				//						{
				//							xtype:'button',
				//							text: i18n('start'),
				//							width: 50,
				//							margin: '0 5 0 0',
				//							handler: function(record){
				//								App.Current.getController('administration.HL7').serverStartHandler(record);
				//							}
				//						},
				//						{
				//							xtype:'button',
				//							text: i18n('stop'),
				//							width: 50,
				//							handler: function(record){
				//								App.Current.getController('administration.HL7').serverStopHandler(record);
				//							}
				//						}
				//					]
				//				}
			],
			plugins: [
				{
					ptype: 'rowformediting',
					clicksToEdit: 2,
					items: [
						{
							xtype: 'container',
							layout: {
								type: 'hbox'
							},
							items: [
								{
									xtype: 'fieldset',
									layout: 'anchor',
									title: i18n('general'),
									width: 300,
									margin: '0 5 0 0',
									items: [
										{
											xtype: 'textfield',
											fieldLabel: i18n('server_name'),
											name: 'server_name',
											anchor: '100%'
										},
										{
											xtype: 'textfield',
											fieldLabel: i18n('ip'),
											name: 'ip',
											anchor: '100%'
										},
										{
											xtype: 'textfield',
											fieldLabel: i18n('port'),
											name: 'port',
											anchor: '100%'
										}
									]
								},
								{
									xtype: 'fieldset',
									layout: 'anchor',
									title: i18n('allow_ips'),
									width: 200,
									margin: '0 5 0 0',
									items: [
										{
											xtype: 'multitextfield',
											numbers: false,
											name: 'allow_ips'
										}
									]
								},
								{
									xtype: 'fieldset',
									title: i18n('messages'),
									layout: 'fit',
									flex: 1,
									items: [
										{
											xtype: 'checkboxgroup',
											columns: 5,
											vertical: true,
											defaults: {
												name: 'allow_messages',
												uncheckedValue: null
											},
											items: [
												{
													xtype: 'checkbox',
													boxLabel: 'ADT_A01',
													inputValue: 'ADT_A01'
												},
												{
													xtype: 'checkbox',
													boxLabel: 'ADT_A04',
													inputValue: 'ADT_A04'
												},
												{
													boxLabel: 'ADT_A08',
													inputValue: 'ADT_A08'
												},
												{
													boxLabel: 'ADT_A09',
													inputValue: 'ADT_A09'
												},
												{
													boxLabel: 'ADT_A10',
													inputValue: 'ADT_A10'
												},
												{
													boxLabel: 'ADT_A18',
													inputValue: 'ADT_A18'
												},
												{
													boxLabel: 'ADT_A28',
													inputValue: 'ADT_A28'
												},
												{
													boxLabel: 'ADT_A29',
													inputValue: 'ADT_A29'
												},
												{
													boxLabel: 'ADT_A31',
													inputValue: 'ADT_A31'
												},
												{
													boxLabel: 'ADT_A32',
													inputValue: 'ADT_A32'
												},
												{
													boxLabel: 'ADT_A33',
													inputValue: 'ADT_A33'
												},
												{
													boxLabel: 'ADT_A39',
													inputValue: 'ADT_A39'
												},
												{
													boxLabel: 'ADT_A40',
													inputValue: 'ADT_A40'
												},
												{
													boxLabel: 'ADT_A41',
													inputValue: 'ADT_A41'
												},
												{
													boxLabel: 'ORU_R01',
													inputValue: 'ORU_R01'
												},
												{
													boxLabel: 'ORU_R21',
													inputValue: 'ORU_R21'
												},
												{
													boxLabel: 'SIU_S12',
													inputValue: 'SIU_S12'
												},
												{
													boxLabel: 'SIU_S13',
													inputValue: 'SIU_S13'
												},
												{
													boxLabel: 'SIU_S14',
													inputValue: 'SIU_S14'
												},
												{
													boxLabel: 'SIU_S15',
													inputValue: 'SIU_S15'
												},
												{
													boxLabel: 'SIU_S22',
													inputValue: 'SIU_S22'
												}
											]
										}
									]
								}
							]
						}
					]
				}
			],
			tbar: Ext.create('Ext.PagingToolbar', {
				store: this.sStore,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				emptyMsg: 'No Servers to display',
				items: [
					'-',
					{
						text: i18n('add_server'),
						iconCls: 'icoAdd',
						itemId: 'addHL7ServerBtn'
					},
					'-',
					{
						text: i18n('remove_server'),
						iconCls: 'icoDelete',
						itemId: 'removeHL7ServerBtn',
						disabled: true
					},
					'-'
				]
			})
		},
		{
			xtype: 'grid',
			title: i18n('hl7_clients'),
			store: this.cStore = Ext.create('App.store.administration.HL7Clients'),
			itemId: 'hl7clientsgrid',
			flex: 1,
			columnLines: true,
			frame: true,
			padding: 0,
			columns: [
				{
					text: i18n('active'),
					dataIndex: 'active',
					width: 50,
					renderer: function(v){
						return app.boolRenderer(v);
					},
					editor: {
						xtype: 'checkbox'
					}
				},
				{
					text: i18n('facility'),
					dataIndex: 'facility',
					flex: 1,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					text: i18n('application_name'),
					dataIndex: 'application_name',
					flex: 1,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					text: i18n('physical_address'),
					dataIndex: 'physical_address',
					flex: 1,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					text: i18n('url_ip_or_domain'),
					dataIndex: 'address',
					flex: 1,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					text: i18n('port'),
					dataIndex: 'port',
					width: 70,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					text: i18n('secret_key'),
					dataIndex: 'secret_key',
					width: 200,
					editor: {
						xtype: 'textfield'
					}
				}
			],
			plugins: [
				{
					ptype: 'rowediting',
					clicksToEdit: 2
				}
			],
			tbar: Ext.create('Ext.PagingToolbar', {
				store: this.cStore,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				emptyMsg: 'No Clients to display',
				items: [
					'-',
					{
						text: i18n('add_client'),
						iconCls: 'icoAdd',
						itemId: 'addHL7ClientBtn'
					},
					'-',
					{
						text: i18n('remove_client'),
						iconCls: 'icoDelete',
						itemId: 'removeHL7ClientBtn',
						disabled: true
					},
					'-'
				]
			})
		}
	]
}); 