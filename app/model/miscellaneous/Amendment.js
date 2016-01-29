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

Ext.define('App.model.miscellaneous.Amendment', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_amendments'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'portal_id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int'
		},
		{
			name: 'amendment_type',
			type: 'string',
			len: 1,
			comment: 'P = patient or D = Doctor or O = organization'
		},
		{
			name: 'amendment_data',
			type: 'array',
			dataType: 'mediumtext'
		},
		{
			name: 'amendment_message',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'amendment_status',
			type: 'string',
			len: 1,
			comment: 'W = waiting or A = approved or D = denied or C = canceled'
		},
		{
			name: 'response_message',
			type: 'string',
			len: 500,
			comment: 'denial or approval reason'
		},
		{
			name: 'is_read',
			type: 'bool'
		},
		{
			name: 'is_viewed',
			type: 'bool'
		},
		{
			name: 'is_synced',
			type: 'bool'
		},
		{
			name: 'assigned_to_uid',
			type: 'int'
		},
		{
			name: 'create_uid',
			type: 'int'
		},
		{
			name: 'update_uid',
			type: 'int'
		},
		{
			name: 'response_uid',
			type: 'int'
		},
		{
			name: 'approved_by',
			type: 'string',
			len: 80
		},
		{
			name: 'assigned_date',
			type: 'date',
			comment: 'Assigned date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'response_date',
			type: 'date',
			comment: 'create date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'cancel_date',
			type: 'date',
			comment: 'create date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'cancel_by',
			type: 'string',
			len: 15,
			comment: 'U for user P patient and ID'
		},
		{
			name: 'create_date',
			type: 'date',
			comment: 'create date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'update_date',
			type: 'date',
			comment: 'last update date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'responded_by',
			type: 'string',
			store: false,
			convert: function(v, record){
				if(record.data.amendment_status === 'A'){
					return record.data.response_title + ' ' + record.data.response_fname + ' ' + record.data.response_mname + ' ' + record.data.response_lname;
				}else{
					return '';
				}
			}
		},
		{
			name: 'response_title',
			type: 'string',
			store: false
		},
		{
			name: 'response_fname',
			type: 'string',
			store: false
		},
		{
			name: 'response_mname',
			type: 'string',
			store: false
		},
		{
			name: 'response_lname',
			type: 'string',
			store: false
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Amendments.getAmendments',
			create: 'Amendments.addAmendment',
			update: 'Amendments.updateAmendment'
		},
		reader: {
			root: 'data'
		}
	}
});