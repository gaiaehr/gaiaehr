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

Ext.define('App.model.areas.PatientPool', {
	extend: 'Ext.data.Model',
	table:{
		name:'patient_pools'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'parent_id',
			type: 'int',
			index: true
		},
		{
			name: 'appointment_id',
			type: 'int',
			index: true
		},
		{
			name: 'provider_id',
			type: 'int',
			index: true
		},
		{
			name: 'pid',
			type: 'int'
		},
		{
			name: 'uid',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int'
		},
		{
			name: 'date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'time_in',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'time_out',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'area_id',
			type: 'int'
		},
		{
			name: 'priority',
			type: 'string',
			len: 15
		},
		{
			name: 'in_queue',
			type: 'bool'
		},
		{
			name: 'checkout_timer',
			type: 'string',
			dataType: 'time'
		}
	]
});