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
		'App.ux.combo.EncounterSupervisors'
	],
	title: i18n('checkout_and_signing'),
	itemId: 'EncounterSignWindow',
	closeAction: 'hide',
	modal: true,
	layout: 'border',
	width: 1000,
	height: 660,
	bodyPadding: 5,

	pid: null,
	eid: null,

	items: [
		{
			xtype: 'grid',
			title: i18n('super_bill'),
			rootVisible: false,
			region: 'center',
			flex: 2,
			plugins: [
				{
					ptype: 'treeviewdragdrop'
				}
			],
			columnLines: true,
			columns: [
				{
					xtype: 'actioncolumn',
					width: 20,
					items: [
						{
							icon: 'resources/images/icons/delete.png',
							tooltip: i18n('remove'),
							handler: function(){
								return App.app.getController('patient.encounter.EncounterSign').onRemoveService(v);
							},
							getClass: function(value, metadata, record){
								if(!record.data.leaf){
									return 'x-grid-center-icon';
								}else{
									return 'x-hide-display';
								}
							}
						}
					]
				},
				{
					text: i18n('service'),
					dataIndex: 'service',
					flex: 1
				},
				{
					header: i18n('units'),
					dataIndex: 'units',
					width: 50
				},
				{
					header: i18n('modifiers'),
					dataIndex: 'units',
					width: 100
				},
				{
					header: i18n('diagnosis'),
					dataIndex: 'diagnosis',
					width: 200
				}
			],
			dockedItems: [
				{
					xtype: 'toolbar',
					dock: 'top',
					items: [
						{
							text: i18n('service'),
							iconCls: 'icoAdd'
						}
					]
				}
			]
		},
		{
			xtype: 'documentsimplegrid',
			title: i18n('documents'),
			region: 'east',
			itemId: 'EncounterSignDocumentGrid',
			width: 200
		},
		{
			xtype: 'form',
			title: i18n('additional_info'),
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
							title: i18n('messages_notes_and_reminders'),
							defaults: {
								anchor: '100%'
							},
							items: [
								{
									xtype: 'textfield',
									name: 'message',
									fieldLabel: i18n('message')
								},
								{
									xtype: 'textfield',
									name: 'reminder',
									fieldLabel: i18n('reminder')
								},
								{
									xtype: 'textfield',
									grow: true,
									name: 'note',
									fieldLabel: i18n('note'),
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
									fieldLabel: i18n('time_interval'),
									name: 'followup_time'
								},
								{
									fieldLabel: i18n('facility'),
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
					title: i18n('warnings_alerts'),
					itemId: 'EncounterSignAlertGrid',
					items: [
						{
							xtype: 'grid',
							hideHeaders: true,
							store: Ext.create('App.store.patient.CheckoutAlertArea'),
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
			text: i18n('co_sign') + ' (' + i18n('supervisor') + ')',
			itemId: 'EncounterCoSignSupervisorBtn'
		},
		{
			text: i18n('sign'),
			itemId: 'EncounterSignBtn'
		},
		{
			text: i18n('cancel'),
			itemId: 'EncounterCancelSignBtn'
		}
	]
});