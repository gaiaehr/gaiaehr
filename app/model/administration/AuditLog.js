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

Ext.define('App.model.administration.AuditLog', {
	extend: 'Ext.data.Model',
	table: {
		name: 'audit_log',
		comment: 'Audit Logs'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Audit Log ID'
		},
		{
			name: 'eid',
			type: 'int',
			comment: 'Encounter ID'
		},
		{
			name: 'event',
			type: 'string',
			comment: 'Event description'
		},
		{
			name: 'facility',
			type: 'string',
			comment: 'Witch facility'
		},
		{
			name: 'patient_id',
			type: 'int',
			comment: 'Patient ID'
		},
		{
			name: 'user_id',
			type: 'int',
			comment: 'User ID'
		},
		{
			name: 'user',
			type: 'string',
			comment: 'Username'
		},
		{
			name: 'date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'Date of the event'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AuditLog.getLogs',
			create: 'AuditLog.setLog',
			update: 'AuditLog.setLog'
		},
		reader: {
			totalProperty: 'totals',
			root: 'rows'
		}
	},
	hasMany: [
		{
			model: 'App.model.patient.Patient',
			name: 'patient',
			primaryKey: 'patient_id',
			foreignKey: 'pid'
		}
	]
});