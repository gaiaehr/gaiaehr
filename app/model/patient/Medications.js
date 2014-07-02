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

Ext.define('App.model.patient.Medications', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_medications',
		comment: 'Patient Medications'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Medications ID'
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
			name: 'prescription_id',
			type: 'int'
		},
		{
			name: 'STR',
			type: 'string',
			len: 180
		},
		{
			name: 'CODE',
			type: 'string',
			len: 40
		},
		{
			name: 'RXCUI',
			type: 'string',
			len: 40
		},
		{
			name: 'NDC',
			type: 'string',
			len: 40
		},
		{
			name: 'ICDS',
			type: 'string'
		},
		{
			name: 'dose',
			type: 'string',
			len: 180
		},
		{
			name: 'take_pills',
			type: 'int'
		},
		{
			name: 'form',
			type: 'string',
			len: 80
		},
		{
			name: 'route',
			type: 'string',
			len: 80
		},
		{
			name: 'prescription_often',
			type: 'string',
			len: 80
		},
		{
			name: 'prescription_when',
			type: 'string'
		},
		{
			name: 'dispense',
			type: 'string',
			len: 80
		},
		{
			name: 'refill',
			type: 'string',
			len: 80
		},
		{
			name: 'date_ordered',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'created_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
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
			name: 'outcome',
			type: 'string',
			len: 180
		},
		{
			name: 'ocurrence',
			type: 'string',
			len: 180
		},
		{
			name: 'referred_by',
			type: 'string',
			len: 180
		},
		{
			name: 'active',
			type: 'bool',
			store: false,
			convert: function(v, record){
				return record.data.end_date == null;
			}
		}

	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Medications.getPatientMedications',
			create: 'Medications.addPatientMedication',
			update: 'Medications.updatePatientMedication',
			destroy: 'Medications.destroyPatientMedication'
		},
		remoteGroup: false
	}
});

