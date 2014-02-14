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

Ext.define('App.model.patient.Encounters', {
	extend: 'Ext.data.Model',
	fields: [
		{
			name: 'eid',
			type: 'int',
			comment: 'Encounter ID'
		},
		{
			name: 'pid',
			type: 'int',
			comment: 'Patient ID'
		},
		{
			name: 'open_uid',
			type: 'string'
		},
		{
			name: 'close_uid',
			type: 'string'
		},
		{
			name: 'brief_description',
			type: 'string'
		},
		{
			name: 'visit_category',
			type: 'string'
		},
		{
			name: 'facility',
			type: 'string'
		},
		{
			name: 'billing_facility',
			type: 'string'
		},
		{
			name: 'sensitivity',
			type: 'string'
		},
		{
			name: 'service_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'close_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'onset_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		}
	],
	idProperty: 'eid',
	proxy: {
		type: 'direct',
		api: {
			read: 'Encounter.getEncounters'
		},
		reader: {
			root: 'encounter'
		}
	}
});