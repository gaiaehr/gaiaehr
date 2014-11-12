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

Ext.define('App.view.patient.windows.EncounterCheckOut', {
	extend: 'App.ux.window.Window',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'App.ux.combo.EncounterSupervisors',
		'App.ux.LiveCPTSearch'
	],
	title: _('checkout_and_signing'),
	itemId: 'EncounterSignWindow',
	closeAction: 'hide',
	modal: true,
	layout: 'border',
	width: 1200,
	height: 660,
	bodyPadding: 5,

	pid: null,
	eid: null,

	items: [
		{
			xtype: 'grid',
			title: _('super_bill'),
			rootVisible: false,
			region: 'center',
			itemId: 'EncounterSignSuperBillGrid',
			store: Ext.create('App.store.patient.EncounterServices'),
			flex: 2,
			columnLines: true,
			plugins: [
				{
					ptype: 'rowediting',
					errorSummary: false
				}
			],
			columns: [
				{
					xtype: 'actioncolumn',
					width: 20,
					items: [
						{
							icon: 'resources/images/icons/delete.png',
							tooltip: _('remove'),
							handler: function(){
								return App.app.getController('patient.encounter.EncounterSign').onRemoveService(v);
							}
						}
					]
				},
				{
					text: _('service'),
					dataIndex: 'code_text',
					flex: 1,
					editor: {
						xtype: 'livecptsearch',
						itemId: 'EncounterSignSuperCptSearchCmb',
						valueField: 'code_text_medium',
						allowBlank: false
					}
				},
				{
					header: _('units'),
					dataIndex: 'units',
					width: 50,
					editor: {
						xtype: 'numberfield',
						minValue: 1,
						allowBlank: false
					}
				},
				{
					header: _('modifiers'),
					dataIndex: 'modifiers',
					width: 100,
					editor: {
						xtype: 'textfield'
					}
				},
				{
					header: _('diagnosis'),
					dataIndex: 'dx_pointers',
					width: 250,
					editor: {
						xtype: 'textfield',
						allowBlank: false
					}
				},
				{
					header: _('status'),
					dataIndex: 'status'
				}
			],
			dockedItems: [
				{
					xtype: 'toolbar',
					dock: 'top',
					items: [
						'->',
						{
							text: _('service'),
							iconCls: 'icoAdd',
							itemId: 'EncounterSignSuperBillServiceAddBtn'
						}
					]
				}
			]
		},
		{
			xtype: 'documentsimplegrid',
			title: _('documents'),
			region: 'east',
			itemId: 'EncounterSignDocumentGrid',
			width: 200
		},
		{
			xtype: 'form',
			title: _('additional_info'),
			region: 'south',
			split: true,
			height: 245,
			layout: 'column',
			defaults: {
				xtype: 'fieldset',
				padding: 8
			},
			items: [
				{
					xtype: 'container',
					columnWidth: .5,
					defaults: {
						xtype: 'fieldset',
						padding: 8,
						margin: '5 1 5 5'
					},
					padding: 0,
					layout: {
						type: 'vbox',
						align: 'stretch'
					},
					items: [
						{
							title: _('messages_notes_and_reminders'),
							defaults: {
								anchor: '100%'
							},
							items: [
								{
									xtype: 'textfield',
									name: 'message',
									fieldLabel: _('message')
								},
								{
									xtype: 'textfield',
									name: 'reminder',
									fieldLabel: _('reminder')
								},
								{
									xtype: 'textfield',
									grow: true,
									name: 'note',
									fieldLabel: _('note'),
									margin: 0
								}
							]
						},
						{
							title: 'Follow Up',
							flex: 1,
							defaults: {
								anchor: '100%'
							},
							items: [
								{
									xtype: 'mitos.followupcombo',
									fieldLabel: _('time_interval'),
									name: 'followup_time'
								},
								{
									fieldLabel: _('facility'),
									xtype: 'activefacilitiescombo',
									name: 'followup_facility',
									margin: 0
								}
							]
						}
					]
				},
				{
					xtype: 'fieldset',
					margin: 5,
					padding: 8,
					columnWidth: .5,
					layout: 'fit',
					height: 208,
					title: _('warnings_alerts'),
					items: [
						{
							xtype: 'grid',
							hideHeaders: true,
							store: Ext.create('App.store.patient.CheckoutAlertArea'),
							itemId: 'EncounterSignAlertGrid',
							border: false,
							rowLines: false,
							header: false,
							viewConfig: {
								stripeRows: false,
								disableSelection: true
							},
							columns: [
								{
									dataIndex: 'alertType',
									width: 30,
									renderer: function(v){
										return App.app.getController('patient.encounter.EncounterSign').alertIconRenderer(v);
									}
								},
								{
									dataIndex: 'alert',
									flex: 1
								}
							]
						}
					]
				}
			]
		}
	],
	buttons: [
		{
			xtype: 'encountersupervisorscombo',
			itemId: 'EncounterCoSignSupervisorCombo',
			allowBlank: false
		},
		{
			text: _('co_sign') + ' (' + _('supervisor') + ')',
			itemId: 'EncounterCoSignSupervisorBtn'
		},
		{
			text: _('sign'),
			itemId: 'EncounterSignBtn'
		},
		{
			text: _('cancel'),
			itemId: 'EncounterCancelSignBtn'
		}
	]
});