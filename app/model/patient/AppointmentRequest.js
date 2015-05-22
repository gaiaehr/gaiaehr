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

Ext.define('App.model.patient.AppointmentRequest', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_appointment_requests'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int'
		},
		{
			name: 'appointment_id',
			type: 'int'
		},
		{
			name: 'requested_uid',
			type: 'int'
		},
		{
			name: 'approved_uid',
			type: 'int'
		},
		{
			name: 'is_approved',
			type: 'bool',
			persist: false,
			convert: function(v, record){
				return record.data.approved_uid > 1;
			}
		},
		{
			name: 'requested_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'approved_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'notes',
			type: 'string'
		},
		{
			name: 'procedure1',
			type: 'string',
			store: false
		},
		{
			name: 'procedure1_code',
			type: 'string',
			len: 10
		},
		{
			name: 'procedure1_code_type',
			type: 'string',
			len: 10
		},
		{
			name: 'procedure2',
			type: 'string',
			store: false
		},
		{
			name: 'procedure2_code',
			type: 'string',
			len: 10
		},
		{
			name: 'procedure2_code_type',
			type: 'string',
			len: 10
		},
		{
			name: 'procedure3',
			type: 'string',
			store: false
		},
		{
			name: 'procedure3_code',
			type: 'string',
			len: 10
		},
		{
			name: 'procedure3_code_type',
			type: 'string',
			len: 10
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'update_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'create_uid',
			type: 'int'
		},
		{
			name: 'update_uid',
			type: 'int'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AppointmentRequest.getAppointmentRequests',
			create: 'AppointmentRequest.addAppointmentRequest',
			update: 'AppointmentRequest.updateAppointmentRequest',
			destroy: 'AppointmentRequest.deleteAppointmentRequest'
		}
	}
});
