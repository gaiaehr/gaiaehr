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

Ext.define('App.model.administration.Facility', {
	extend: 'Ext.data.Model',
	table: {
		name: 'facility',
		comment: 'Facilities'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Facility ID'
		},
		{
			name: 'name',
			type: 'string',
			comment: 'Facility Name'
		},
		{
			name: 'attn',
			type: 'string'
		},
		{
			name: 'phone',
			type: 'string'
		},
		{
			name: 'fax',
			type: 'string'
		},
		{
			name: 'street',
			type: 'string'
		},
		{
			name: 'city',
			type: 'string'
		},
		{
			name: 'state',
			type: 'string'
		},
		{
			name: 'postal_code',
			type: 'string'
		},
		{
			name: 'country_code',
			type: 'string'
		},
		{
			name: 'ssn',
			type: 'string'
		},
		{
			name: 'ein',
			type: 'string'
		},
		{
			name: 'service_location',
			type: 'bool'
		},
		{
			name: 'billing_location',
			type: 'bool'
		},
		{
			name: 'accepts_assignment',
			type: 'bool'
		},
		{
			name: 'pos_code',
			type: 'string'
		},
		{
			name: 'x12_sender_id',
			type: 'string'
		},
		{
			name: 'clia',
			type: 'string'
		},
		{
			name: 'fda',
			type: 'string'
		},
		{
			name: 'npi',
			type: 'string'
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Facilities.getFacilities',
			create: 'Facilities.addFacility',
			update: 'Facilities.updateFacility',
			destroy: 'Facilities.deleteFacility'
		}
	},
	reader: {
		totalProperty: 'total',
		root: 'data'
	}
});