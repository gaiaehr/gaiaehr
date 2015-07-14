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

Ext.define('App.model.patient.CognitiveAndFunctionalStatus', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_cognitive_functional_status',
		comment: 'Patient Cognitive Functional Status'
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
			name: 'category',
			type: 'string',
			len: 20
		},
		{
			name: 'category_code',
			type: 'string',
			len: 20
		},
		{
			name: 'category_code_type',
			type: 'string',
			len: 20
		},
		{
			name: 'code',
			type: 'string',
			len: 20
		},
		{
			name: 'code_text',
			type: 'string',
			len: 300
		},
		{
			name: 'code_type',
			type: 'string',
			len: 15
		},
		{
			name: 'status',
			type: 'string',
			len: 20
		},
		{
			name: 'status_code',
			type: 'string',
			len: 40
		},
		{
			name: 'status_code_type',
			type: 'string',
			len: 15
		},
		{
			name: 'note',
			type: 'string',
			len: 500
		},
		{
			name: 'begin_date',
			type: 'date',
			dateFormat: 'Y-m-d',
			dataType: 'date'
		},
		{
			name: 'end_date',
			type: 'date',
			dateFormat: 'Y-m-d',
			dataType: 'date'
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
			read: 'CognitiveAndFunctionalStatus.getPatientCognitiveAndFunctionalStatuses',
			create: 'CognitiveAndFunctionalStatus.addPatientCognitiveAndFunctionalStatus',
			update: 'CognitiveAndFunctionalStatus.updateCognitiveAndFunctionalStatus',
			destroy: 'CognitiveAndFunctionalStatus.destroyCognitiveAndFunctionalStatus'
		}
	}
});