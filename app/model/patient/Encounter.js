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

Ext.define('App.model.patient.Encounter', {
	extend: 'Ext.data.Model',
	table: {
		name: 'encounters',
		comment: 'Encounter Data'
	},
	fields: [
		{
			name: 'eid',
			type: 'int',
			comment: 'Encounter ID'
		},
		{
			name: 'pid',
			type: 'int',
			index: true
		},
		{
			name: 'open_uid',
			type: 'int',
			index: true
		},
		{
			name: 'provider_uid',
			type: 'int',
			index: true
		},
		{
			name: 'supervisor_uid',
			type: 'int',
			index: true
		},
		{
			name: 'requires_supervisor',
			type: 'bool',
			index: true,
			defaultValue: false
		},
		{
			name: 'service_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			index: true
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
		},
		{
			name: 'priority',
			type: 'string',
			len: 60
		},
		{
			name: 'brief_description',
			type: 'string',
			len: 600
		},
		{
			name: 'visit_category',
			type: 'string',
			len: 80
		},
		{
			name: 'facility',
			type: 'int',
			len: 1,
			index: true
		},
		{
			name: 'billing_facility',
			type: 'int',
			len: 1,
			index: true
		},
		{
			name: 'billing_stage',
			type: 'int',
			len: 1,
			index: true
		},
		{
			name: 'followup_time',
			type: 'string',
			len: 25
		},
		{
			name: 'followup_facility',
			type: 'string',
			len: 80
		},
		{
			name: 'review_immunizations',
			type: 'bool'
		},
		{
			name: 'review_allergies',
			type: 'bool'
		},
		{
			name: 'review_active_problems',
			type: 'bool'
		},
		{
			name: 'review_alcohol',
			type: 'string',
			len: 40
		},
		{
			name: 'review_smoke',
			type: 'string',
			len: 40
		},
		{
			name: 'review_pregnant',
			type: 'string',
			len: 40
		},
		{
			name: 'review_surgery',
			type: 'bool'
		},
		{
			name: 'review_dental',
			type: 'bool'
		},
		{
			name: 'review_medications',
			type: 'bool'
		},
		{
			name: 'message',
			type: 'string',
			dataType: 'text'
		}
	],
	idProperty: 'eid',
	proxy: {
		type: 'direct',
		api: {
			read: 'Encounter.getEncounter',
			create: 'Encounter.createEncounter',
			update: 'Encounter.updateEncounter'
		},
		reader: {
			root: 'encounter'
		}
	},
	hasMany: [
		{
			model: 'App.model.patient.Vitals',
			name: 'vitals',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.ReviewOfSystems',
			name: 'reviewofsystems',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.ReviewOfSystemsCheck',
			name: 'reviewofsystemschecks',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.SOAP',
			name: 'soap',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.SpeechDictation',
			name: 'speechdictation',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.HCFAOptions',
			name: 'hcfaoptions',
			primaryKey: 'eid',
			foreignKey: 'eid'
		}
	]

});