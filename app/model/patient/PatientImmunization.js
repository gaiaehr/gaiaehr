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
		name:'patient_immunizations',
		comment:'Patient Immunization'
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
			type: 'int',
			comment: 'vaccine code (CVX)'
		},
		{
			name: 'code_type',
			type: 'string',
			defaultValue: 'CVX'
		},
		{
			name: 'vaccine_name',
			type: 'string'
		},
		{
			name: 'lot_number',
			type: 'string'
		},
		{
			name: 'administer_amount',
			type: 'string'
		},
		{
			name: 'administer_units',
			type: 'string'
		},
		{
			name: 'administered_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'administered_by',
			type: 'string'
		},
		{
			name: 'manufacturer',
			type: 'string'
		},
		{
			name: 'education_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'note',
			type: 'string'
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
		}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getPatientImmunizations,
			create: Medical.addPatientImmunization,
			update: Medical.updatePatientImmunization
		}
	}
});