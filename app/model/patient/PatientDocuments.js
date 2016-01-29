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

Ext.define('App.model.patient.PatientDocuments', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_documents',
		comment: 'Patient Documents Storage'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'code',
			type: 'string',
			len: 120,
			comment: 'external reference id',
			index: true
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
			type: 'int',
			index: true
		},
		{
			name: 'docType',
			type: 'string',
			index: true
		},
		{
			name: 'docTypeCode',
			type: 'string',
			len: 20,
			index: true
		},
		{
			name: 'name',
			type: 'string'
		},
		{
			name: 'date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			index: true
		},
		{
			name: 'url',
			type: 'string'
		},
		{
			name: 'note',
			type: 'string',
			dataType: 'MEDIUMTEXT'
		},
		{
			name: 'title',
			type: 'string'
		},
		{
			name: 'hash',
			type: 'string'
		},
		{
			name: 'encrypted',
			type: 'bool',
			defaultValue: 0
		},
		{
			name: 'entered_in_error',
			type: 'bool',
			defaultValue: 0
		},
		{
			name: 'error_note',
			type: 'string',
			len: 300
		},
		{
			name: 'groupDate',
			type: 'date',
			store: false,
			convert: function(v, record){
				var date = Ext.clone(record.get('date'));
				if(!date) return null;
				date.setHours(0);
				date.setMinutes(0);
				date.setSeconds(0);
				date.setMilliseconds(0);
				return date;
			}
		},
		{
			name: 'document_instance',
			type: 'string',
			len: 10
		},
		{
			name: 'document_id',
			type: 'int'
		},
		{
			name: 'document',
			type: 'string',
			dataType: 'longblob'
		},
		{
			name: 'site',
			type: 'string',
			store: false,
			useNull: true
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'DocumentHandler.getPatientDocuments',
			create: 'DocumentHandler.addPatientDocument',
			update: 'DocumentHandler.updatePatientDocument'
		},
		reader: {
			root: 'data'
		},
		remoteGroup: false
	}
});

