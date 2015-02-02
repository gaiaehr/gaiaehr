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

Ext.define('App.model.patient.SmokeStatus', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_smoke_status',
		comment: 'Patient Smoke status'
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
			name: 'status',
			type: 'string',
			len: 80
		},
		{
			name: 'status_code',
			type: 'string',
			len: 20
		},
		{
			name: 'status_code_type',
			type: 'string',
			len: 20
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
			read: 'SocialHistory.getSmokeStatus',
			create: 'SocialHistory.addSmokeStatus',
			update: 'SocialHistory.updateSmokeStatus'
		}
	}
});