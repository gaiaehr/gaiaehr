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

Ext.define('App.view.administration.HL7Servers', {
	extend: 'App.ux.RenderPanel',
	requires:[
		'App.ux.grid.Button'
	],
	xtype: 'hl7serverspanel',
	pageTitle: i18n('hl7_servers'),
	pageBody: [
		{
			xtype:'grid',
			store: Ext.create('App.store.administration.HL7Servers'),
			itemId: 'hl7serversgrid',
			columns:[
				{
					text: i18n('server_name'),
					dataIndex: 'server_name',
					flex: 1
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
					text: i18n('port'),
					dataIndex: 'port'
				},
				{
					text: i18n('online'),
					dataIndex: 'online',
					width: 50,
					renderer: app.boolRenderer
				},
				{
					xtype:'gridbutton',
					width: 150,
					items:[
						{
							xtype:'button',
							text: i18n('start'),
							width: 50,
							margin: '0 5 0 0',
							handler: function(record){
								App.Current.getController('administration.HL7Servers').serverStartHandler(record);
							}
						},
						{
							xtype:'button',
							text: i18n('stop'),
							width: 50,
							handler: function(record){
								App.Current.getController('administration.HL7Servers').serverStopHandler(record);
							}
						}
					]
				}
			],
			plugins: [
				{
					ptype: 'rowformediting',
					clicksToEdit: 2,
					formItems: [
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
									margin : '0 5 0 0',
									items: [
										{
											xtype: 'textfield',
											fieldLabel: i18n('server_name'),
											name: 'server_name'
										},
										{
											xtype: 'textfield',
											fieldLabel: i18n('port'),
											name: 'port'
										},
										{
											xtype: 'multitextfield',
											fieldLabel: i18n('allow_ips'),
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
									items:[
										{
											xtype: 'checkboxgroup',
											columns: 3,
											vertical: true,
											defaults:{
												name: 'allow_messages',
												uncheckedValue: null
											},
											items: [
												{
													xtype: 'checkbox',
													boxLabel: 'ADT_A01 (Admit/Visit Notification)',
													inputValue: 'ADT_A01'
												},
												{
													xtype: 'checkbox',
													boxLabel: 'ADT_A04 (Register a Patient)',
													inputValue: 'ADT_A04'
												},
												{
													boxLabel: 'ADT_A08 (Update Patient Information)',
													inputValue: 'ADT_A08'
												},
												{
													boxLabel: 'ADT_A09 (Patient Departing - Tracking)',
													inputValue: 'ADT_A09'
												},
												{
													boxLabel: 'ADT_A10 (Patient Arriving - Tracking)',
													inputValue: 'ADT_A10'
												},
												{
													boxLabel: 'ADT_A18 (Merge Patient Information)',
													inputValue: 'ADT_A18'
												},
												{
													boxLabel: 'ADT_A28 (Add Person or Patient Information)',
													inputValue: 'ADT_A28'
												},
												{
													boxLabel: 'ADT_A29 (Delete Person Information)',
													inputValue: 'ADT_A29'
												},
												{
													boxLabel: 'ADT_A31 (Update Person Information)',
													inputValue: 'ADT_A31'
												},
												{
													boxLabel: 'ADT_A32 (Cancel Patient Arriving)',
													inputValue: 'ADT_A32'
												},
												{
													boxLabel: 'ADT_A33 (Cancel Patient Departing)',
													inputValue: 'ADT_A33'
												},
												{
													boxLabel: 'ADT_A39 (Merge Person - Patient ID)',
													inputValue: 'ADT_A39'
												},
												{
													boxLabel: 'ADT_A40 (Merge Patient - Patient Identifier List)',
													inputValue: 'ADT_A40'
												},
												{
													boxLabel: 'ADT_A41 (Merge Account - Patient Account Number)',
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
			tbar:[
				'->',
				{
					text: i18n('add_server'),
					iconCls:'icoAdd',
					itemId: 'addHL7ServerBtn'
				},
				{
					text: i18n('remove_server'),
					iconCls:'icoDelete',
					itemId: 'removeHL7ServerBtn'
				}
			]
		}
	]
}); 