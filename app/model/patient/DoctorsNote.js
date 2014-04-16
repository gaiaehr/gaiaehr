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

Ext.define('App.model.patient.DoctorsNote', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_doctors_notes'
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
			name: 'template_id',
			type: 'int'
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'order_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'from_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'to_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'restrictions',
			type: 'array'
		},
		{
			name: 'comments',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'string_restrictions',
			type: 'string',
			store: false,
			convert:function(v, record){
				return (record.data.restrictions.join) ? record.data.restrictions.join(', ') : record.data.restrictions;
			}
		},
		{
			name: 'group_date',
			type: 'date',
			dateFormat: 'Y-m-d',
			store: false,
			convert: function(v, record){
				return Ext.Date.format(record.data.date, 'Y-m-d');
			}
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'DoctorsNotes.getDoctorsNotes',
			create: 'DoctorsNotes.addDoctorsNote',
			update: 'DoctorsNotes.updateDoctorsNote',
			destroy: 'DoctorsNotes.destroyDoctorsNote'
		}
	}
});