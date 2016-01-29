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
			type: 'int'
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
			name: 'ref_order',
			type: 'string',
			len: 100,
			comment: 'reference order number'
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
			name: 'dxs',
			type: 'array'
		},
		{
			name: 'dose',
			type: 'string',
			len: 180
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
			name: 'directions',
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
			name: 'potency_code',
			type: 'string',
			len: 10
		},
		{
			name: 'days_supply',
			type: 'int',
			useNull: true
		},
		{
			name: 'daw',
			type: 'bool',
			useNull: true,
			comment: 'Dispensed As Written'
		},
		{
			name: 'notes',
			type: 'string',
			len: 210
		},
		{
			name: 'system_notes',
			type: 'string',
			len: 210
		},
		{
			name: 'is_compound',
			type: 'bool'
		},
		{
			name: 'is_supply',
			type: 'bool'
		},
		{
			name: 'prescription_id',
			type: 'int'
		},
		{
			name: 'referred_by',
			type: 'string',
			len: 180
		},
		{
			name: 'administered_uid',
			type: 'int'
		},
		{
			name: 'administered_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'administered_by',
			type: 'string',
			store: false,
			convert: function(v, record){
				return record.data.title + ' ' + record.data.fname + ' ' + record.data.mname + ' ' + record.data.lname;
			}
		},
		{
			name: 'title',
			type: 'string',
			store: false
		},
		{
			name: 'fname',
			type: 'string',
			store: false
		},
		{
			name: 'mname',
			type: 'string',
			store: false
		},
		{
			name: 'lname',
			type: 'string',
			store: false
		},
		{
			name: 'date_ordered',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
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
			name: 'active',
			type: 'bool',
			store: false,
			convert: function(v, record){
				return record.data.end_date === null;
			}
		},
		{
			name: 'created_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
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
        writer: {
            writeAllFields: true
        },
		remoteGroup: false
	}
});

