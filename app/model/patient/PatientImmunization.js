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

Ext.define('App.model.patient.PatientImmunization', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_immunizations',
		comment: 'Patient Immunization'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Patient Immunization ID'
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
			name: 'uid',
			type: 'int'
		},
		{
			name: 'code',
			type: 'string',
			len: 10,
			comment: 'vaccine code (CVX)'
		},
		{
			name: 'code_type',
			type: 'string',
			defaultValue: 'CVX',
			len: 15
		},
		{
			name: 'vaccine_name',
			type: 'string',
			len: 300
		},
		{
			name: 'lot_number',
			type: 'string',
			len: 60
		},
		{
			name: 'administer_amount',
			type: 'string',
			len: 40
		},
		{
			name: 'administer_units',
			type: 'string',
			len: 40
		},
		{
			name: 'administered_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'exp_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'administered_uid',
			type: 'int'
		},
		{
			name: 'administered_by',
			type: 'string',
			store: false,
			convert: function(v, record){
				return record.data.administered_title + ' ' +
					record.data.administered_fname + ' ' +
					(record.data.administered_mname || '') + ' ' +
					record.data.administered_lname;
			}
		},
		{
			name: 'administered_title',
			type: 'string',
			store: false
		},
		{
			name: 'administered_fname',
			type: 'string',
			store: false
		},
		{
			name: 'administered_mname',
			type: 'string',
			store: false
		},
		{
			name: 'administered_lname',
			type: 'string',
			store: false
		},
		{
			name: 'route',
			type: 'string',
			len: 40
		},
		{
			name: 'administration_site',
			type: 'string',
			len: 40
		},
		{
			name: 'manufacturer',
			type: 'string',
			len: 180
		},
		{
			name: 'education_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'note',
			type: 'string',
			len: 300
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
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
			name: 'is_error',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Immunizations.getPatientImmunizations',
			create: 'Immunizations.addPatientImmunization',
			update: 'Immunizations.updatePatientImmunization'
		}
	}
});