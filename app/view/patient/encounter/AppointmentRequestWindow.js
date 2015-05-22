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

Ext.define('App.view.patient.encounter.AppointmentRequestWindow', {
	extend: 'Ext.window.Window',
	requires: [
		'App.ux.LiveSnomedProcedureSearch'
	],
	xtype: 'appointmentrequestwindow',
	itemId: 'AppointmentRequestWindow',
	closeAction: 'hide',
	layout: 'fit',
	modal: true,
	initComponent: function(){
		var me = this;

		me.items = [
			{
				xtype: 'form',
				bodyPadding: 10,
				layout: 'anchor',
				width: 670,
				itemId: 'AppointmentRequestForm',
				items: [
					{
						xtype: 'fieldcontainer',
						layout: 'hbox',
						fieldLabel: _('requested_date'),
						itemId: 'AppointmentRequestDateField',
						defaults: {
							margin: '0 5 0 0'
						},
						items: [
							{
								xtype: 'datefield',
								itemId: 'AppointmentRequestRequestedField',
								name: 'requested_date',
								allowBlank: false
							},
							{
								xtype: 'button',
								text: '+1 D',
								action: '1D'
							},
							{
								xtype: 'button',
								text: '+1 W',
								action: '1W'
							},
							{
								xtype: 'button',
								text: '+2 W',
								action: '2W'
							},
							{
								xtype: 'button',
								text: '+3 W',
								action: '3W'
							},
							{
								xtype: 'button',
								text: '+1 M',
								action: '1M'
							},
							{
								xtype: 'button',
								text: '+3 M',
								action: '3M'
							},
							{
								xtype: 'button',
								text: '+6 M',
								action: '6M'
							},
							{
								xtype: 'button',
								text: '+1 Y',
								action: '1Y'
							},
							{
								xtype: 'button',
								text: '+2 Y',
								action: '2Y'
							},
							{
								xtype: 'button',
								text: '+3 Y',
								action: '3Y'
							}
						]
					},
					{
						xtype: 'datefield',
						fieldLabel: _('approved_date'),
						name: 'approved_date',
						format: g('date_time_display_format'),
						submitFormat: 'Y-m-d H:i:s',
						readOnly: true
					},
					{
						xtype: 'textareafield',
						fieldLabel: _('notes'),
						name: 'notes',
						anchor: '100%'
					},
					{
						xtype: 'fieldset',
						title: _('procedures'),
						anchor: '100%',
						layout: 'anchor',
						itemId: 'AppointmentRequestProcedureFieldSet',
						items: [
							{
								xtype: 'snomedliveproceduresearch',
								fieldLabel: _('procedure_one'),
								valueField: 'FullySpecifiedName',
								name: 'procedure1',
								hideLabel: false,
								width: null,
								anchor: '100%'
							},
							{
								xtype: 'snomedliveproceduresearch',
								fieldLabel: _('procedure_two'),
								name: 'procedure2',
								valueField: 'FullySpecifiedName',
								hideLabel: false,
								width: null,
								anchor: '100%'
							},
							{
								xtype: 'snomedliveproceduresearch',
								fieldLabel: _('procedure_three'),
								name: 'procedure3',
								valueField: 'FullySpecifiedName',
								hideLabel: false,
								width: null,
								anchor: '100%'
							}
						]
					}
				]
			}
		];

		me.callParent();
	},
	buttons: [
		{
			text: _('save'),
			itemId: 'AppointmentRequestSaveBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoAdd'
		},
		'-',
		{
			text: _('cancel'),
			itemId: 'AppointmentRequestCancelBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoClose'
		}
	]

});