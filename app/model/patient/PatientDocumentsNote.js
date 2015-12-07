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

Ext.define('App.model.patient.PatientDocumentsNote', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_documents_notes'
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
			name: 'site',
			type: 'string',
			store: false,
			useNull: true
		},
		{
			name: 'document_id',
			type: 'int',
			index: true
		},
		{
			name: 'pid',
			type: 'int',
			index: true
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
			read: 'DocumentNotes.getPatientDocumentNotes',
			create: 'DocumentNotes.addPatientDocumentNote',
			update: 'DocumentNotes.updatePatientDocumentNote'
		},
		reader: {
			root: 'data'
		},
		remoteGroup: false
	}
});