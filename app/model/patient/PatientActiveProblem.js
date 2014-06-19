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

Ext.define('App.model.patient.PatientActiveProblem', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_active_problems',
		comment: 'Active Problems'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Medical Issues ID'
		},
		{
			name: 'pid',
			type: 'int',
			index: true
		},
		{
			name: 'eid',
			type: 'int',
			index: true
		},
		{
			name: 'code',
			type: 'string'
		},
		{
			name: 'code_text',
			type: 'string'
		},
		{
			name: 'code_type',
			type: 'string'
		},
		{
			name: 'begin_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'end_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'occurrence',
			type: 'string'
		},
		{
			name: 'referred_by',
			type: 'string'
		},
		{
			name: 'outcome',
			type: 'string'
		},
		{
			name: 'active',
			type: 'bool',
			store: false,
			convert: function(v, record){
				return record.data.end_date == '' || record.data.end_date == null
			}
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
			dateFormat: 'Y-m-d H:i:s'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'ActiveProblems.getPatientActiveProblems',
			create: 'ActiveProblems.addPatientActiveProblem',
			update: 'ActiveProblems.updatePatientActiveProblem',
			destroy: 'ActiveProblems.destroyPatientActiveProblem'
		}
	}
});

