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

Ext.define('App.model.administration.Pharmacies', {
	extend: 'Ext.data.Model',
	table: {
		name: 'pharmacies',
		comment: 'Pharmacies'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'name',
			type: 'string'
		},
		{
			name: 'transmit_method',
			type: 'string'
		},
		{
			name: 'email',
			type: 'string'
		},
		{
			name: 'address_id',
			type: 'int'
		},
		{
			name: 'line1',
			type: 'string',
			store: false
		},
		{
			name: 'line2',
			type: 'string',
			store: false
		},
		{
			name: 'city',
			type: 'string',
			store: false
		},
		{
			name: 'state',
			type: 'string',
			store: false
		},
		{
			name: 'zip',
			type: 'string',
			store: false
		},
		{
			name: 'plus_four',
			type: 'string',
			store: false
		},
		{
			name: 'country',
			type: 'string',
			store: false
		},
		{
			name: 'address_full',
			type: 'string',
			store: false
		},
		{
			name: 'phone_id',
			type: 'int'
		},
		{
			name: 'phone_country_code',
			type: 'string',
			store: false
		},
		{
			name: 'phone_area_code',
			type: 'string',
			store: false
		},
		{
			name: 'phone_prefix',
			type: 'string',
			store: false
		},
		{
			name: 'phone_number',
			type: 'string',
			store: false
		},
		{
			name: 'phone_full',
			type: 'string',
			store: false
		},
		{
			name: 'fax_id',
			type: 'int'
		},
		{
			name: 'fax_country_code',
			type: 'string',
			store: false
		},
		{
			name: 'fax_area_code',
			type: 'string',
			store: false
		},
		{
			name: 'fax_prefix',
			type: 'string',
			store: false
		},
		{
			name: 'fax_number',
			type: 'string',
			store: false
		},
		{
			name: 'fax_full',
			type: 'string',
			store: false
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Practice.getPharmacies',
			create: 'Practice.addPharmacy',
			update: 'Practice.updatePharmacy'
		}
	}
});