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

Ext.define('App.view.patient.encounter.AppointmentRequestGrid', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.grid.DeleteColumn'
	],
	xtype: 'appointmentrequestgrid',
	itemId: 'AppointmentRequestGrid',
	frame: true,
	store: Ext.create('App.store.patient.AppointmentRequests'),
	initComponent: function(){
		var me = this;

		me.columns = [
			{
				xtype: 'griddeletecolumn',
				width: 25
			},
			{
				xtype: 'datecolumn',
				text: _('requested_date'),
				dataIndex: 'requested_date',
				width: 120,
				format: g('date_display_format')
			},
			{
				xtype: 'datecolumn',
				text: _('approved_date'),
				dataIndex: 'approved_date',
				width: 150,
				format: g('date_display_format')
			},
			{
				text: _('notes'),
				dataIndex: 'notes',
				flex: 1
			}
		];

		me.callParent();
	},
	tbar: [
		_('appointment_requests'),
		'->',
		{
			text: _('appointment_request'),
			itemId: 'AppointmentRequestAddBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoAdd'
		}
	]


});