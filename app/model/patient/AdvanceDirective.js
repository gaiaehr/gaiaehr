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

Ext.define('App.model.patient.AdvanceDirective', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_advance_directives'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int',
			index: true
		},
		{
			name: 'pid',
			type: 'int',
			index: true
		},
		{
			name: 'code',
			type: 'string',
			len: 80
		},
		{
			name: 'code_text',
			type: 'string',
			len: 160
		},
		{
			name: 'code_type',
			type: 'string',
			len: 20
		},
		{
			name: 'status_code',
			type: 'string',
			len: 80
		},
		{
			name: 'status_code_text',
			type: 'string',
			len: 160
		},
		{
			name: 'status_code_type',
			type: 'string',
			len: 20
		},
		{
			name: 'notes',
			len: 300,
			type: 'string'
		},
		{
			name: 'start_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'end_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'verified_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'verified_uid',
			type: 'int'
		},
		{
			name: 'created_uid',
			type: 'int'
		},
		{
			name: 'updated_uid',
			type: 'int'
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'update_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			defaultValue: 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AdvanceDirective.getPatientAdvanceDirectives',
			create: 'AdvanceDirective.addPatientAdvanceDirective',
			update: 'AdvanceDirective.updatePatientAdvanceDirective'
		}
	}
});