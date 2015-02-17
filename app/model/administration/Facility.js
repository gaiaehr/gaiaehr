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
			type: 'int'
		},
		{
			name: 'code',
			type: 'string',
			len: 80
		},
		{
			name: 'name',
			type: 'string',
			len: 120,
			comment: 'Facility Name'
		},
		{
			name: 'legal_name',
			type: 'string',
			len: 180
		},
		{
			name: 'attn',
			type: 'string',
			len: 80
		},
		{
			name: 'phone',
			type: 'string',
			len: 25
		},
		{
			name: 'fax',
			type: 'string',
			len: 25
		},
		{
			name: 'address',
			type: 'string',
			len: 120
		},
		{
			name: 'address_cont',
			type: 'string',
			len: 120
		},
		{
			name: 'city',
			type: 'string',
			len: 80
		},
		{
			name: 'state',
			type: 'string',
			len: 80
		},
		{
			name: 'postal_code',
			type: 'string',
			len: 15
		},
		{
			name: 'country_code',
			type: 'string',
			len: 5
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
			name: 'pos_code',
			type: 'string',
			len: 3
		},
		{
			name: 'ssn',
			type: 'string',
			len: 15
		},
		{
			name: 'ein',
			type: 'string',
			len: 15
		},
		{
			name: 'clia',
			type: 'string',
			len: 15
		},
		{
			name: 'fda',
			type: 'string',
			len: 15
		},
		{
			name: 'npi',
			type: 'string',
			len: 15
		},
		{
			name: 'lic',
			type: 'string',
			len: 15
		},
		{
			name: 'ess',
			type: 'string',
			len: 15
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