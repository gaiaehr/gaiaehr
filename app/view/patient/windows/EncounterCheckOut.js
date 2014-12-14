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
		'App.view.patient.SupperBill',
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
			xtype: 'superbillpanel',
			title: _('super_bill'),
			region: 'center',
			flex: 2
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