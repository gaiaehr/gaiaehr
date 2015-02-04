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

Ext.define('App.model.patient.HCFAOptions', {
	extend: 'Ext.data.Model',
	table: {
		name: 'encounter_1500_options'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int'
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
			name: 'employment_related',
			type: 'bool'
		},
		{
			name: 'auto_accident',
			type: 'bool'
		},
		{
			name: 'state',
			type: 'string',
			len: 80
		},
		{
			name: 'other_accident',
			type: 'bool'
		},
		{
			name: 'similar_illness_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'unable_to_work_from',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'unable_to_work_to',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'hops_date_to',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'out_lab_used',
			type: 'bool'
		},
		{
			name: 'amount_charges',
			type: 'string',
			len: 10
		},
		{
			name: 'medicaid_resubmission_code',
			type: 'string',
			len: 15
		},
		{
			name: 'medicaid_original_reference_number',
			type: 'string',
			len: 60
		},
		{
			name: 'prior_authorization_number',
			type: 'string',
			len: 60
		},
		{
			name: 'replacement_claim',
			type: 'bool'
		},
		{
			name: 'notes',
			type: 'string',
			dataType: 'text'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			update: 'Encounter.updateHCFA'
		}
	},
	belongsTo: {
		model: 'App.model.patient.Encounter',
		foreignKey: 'eid'
	}
});