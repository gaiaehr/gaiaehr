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

Ext.define('App.model.patient.PatientZone', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_zone',
		comment: 'Patients Zones'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int',
			index: true,
			comment: 'patient ID'
		},
		{
			name: 'provider_id',
			type: 'int',
			index: true
		},
		{
			name: 'uid',
			type: 'int',
			comment: 'user ID who assigned the patient to this zone'
		},
		{
			name: 'zone_id',
			type: 'int',
			index: true
		},
		{
			name: 'time_in',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'time_out',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			index: true
		}
	]
});