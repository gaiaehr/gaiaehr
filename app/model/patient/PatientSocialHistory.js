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

Ext.define('App.model.patient.PatientSocialHistory', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_social_history',
		comment: 'Patient Social History'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int',
			index: true,
			comment: 'encounter id'
		},
		{
			name: 'pid',
			type: 'int',
			index: true,
			comment: 'patient ID'
		},
		{
			name: 'category_code',
			type: 'string',
			len: 25
		},
		{
			name: 'category_code_type',
			type: 'string',
			defaultValue: 'SNOMEDCT',
			len: 20
		},
		{
			name: 'category_code_text',
			type: 'string'
		},
		{
			name: 'notes',
			type: 'string',
			comment:'clinical notes for this history'
		},
		{
			name: 'start_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'same as CCD low time'
		},
		{
			name: 'end_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'same as CCD high time'
		},
		{
			name: 'create_uid',
			type: 'int',
			comment: 'user ID who created the record'
		},
		{
			name: 'update_uid',
			type: 'int',
			comment: 'user ID who updated the record'
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
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: SocialHistory.getSocialHistories,
			create: SocialHistory.addSocialHistory,
			update: SocialHistory.updateSocialHistory,
			destroy: SocialHistory.destroySocialHistory
		},
		remoteGroup: false
	}
});