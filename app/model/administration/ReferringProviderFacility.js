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

Ext.define('App.model.administration.ReferringProviderFacility', {
	extend: 'Ext.data.Model',
	table: {
		name: 'referring_providers_facilities'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'referring_provider_id',
			type: 'int',
			index: true
		},
		{
			name: 'name',
			type: 'string',
			len: 80
		},
		{
			name: 'address',
			type: 'string',
			len: 35
		},
		{
			name: 'address_cont',
			type: 'string',
			len: 35
		},
		{
			name: 'city',
			type: 'string',
			len: 35
		},
		{
			name: 'state',
			type: 'string',
			len: 35
		},
		{
			name: 'postal_code',
			type: 'string',
			len: 15
		},
		{
			name: 'country',
			type: 'string',
			len: 10
		},
		{
			name: 'taxonomy',
			type: 'string',
			len: 40,
			comment: 'taxonomy',
			defaultValue: '207Q00000X'
		},
		{
			name: 'accept_mc',
			type: 'bool',
			comment: 'Accepts Medicare'
		},
		{
			name: 'email',
			type: 'string',
			len: 180
		},
		{
			name: 'direct_address',
			type: 'string',
			len: 180
		},
		{
			name: 'phone_number',
			type: 'string',
			len: 25
		},
		{
			name: 'fax_number',
			type: 'string',
			len: 25
		},
		{
			name: 'notes',
			type: 'string',
			len: 600
		},
		{
			name: 'is_default',
			type: 'bool'
		},
		{
			name: 'active',
			type: 'bool'
		},
		{
			name: 'create_uid',
			type: 'int'
		},
		{
			name: 'update_uid',
			type: 'int'
		},
		{
			name: 'create_date',
			type: 'date',
			comment: 'create date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'update_date',
			type: 'date',
			comment: 'last update date',
			dateFormat: 'Y-m-d H:i:s'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'ReferringProviders.getReferringProviderFacilities',
			create: 'ReferringProviders.addReferringProviderFacility',
			update: 'ReferringProviders.updateReferringProviderFacility'
		}
	}
});
