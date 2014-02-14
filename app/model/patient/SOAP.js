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

Ext.define('App.model.patient.SOAP', {
	extend: 'Ext.data.Model',
	table: {
		name: 'encounter_soap',
		comment: 'SOAP Data'
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
			name: 'date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'subjective',
			type: 'string',
			dataType: 'longtext'
		},
		{
			name: 'objective',
			type: 'string',
			dataType: 'longtext'
		},
		{
			name: 'assessment',
			type: 'string',
			dataType: 'longtext'
		},
		{
			name: 'plan',
			type: 'string',
			dataType: 'longtext'
		},
		{
			name: 'icdxCodes',
			store: false
		}
	],
	proxy: {
		type: 'direct',
		api: {
			update: 'Encounter.updateSoapById'
		}
	},
	belongsTo: {
		model: 'App.model.patient.Encounter',
		foreignKey: 'eid'
	}
});