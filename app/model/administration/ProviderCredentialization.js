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

Ext.define('App.model.administration.ProviderCredentialization', {
	extend: 'Ext.data.Model',
	table: {
		name: 'provider_credentializations'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'provider_id',
			type: 'int',
			index: true
		},
		{
			name: 'insurance_company_id',
			type: 'int',
			index: true
		},
		{
			name: 'insurance_company_name',
			type: 'string',
			store: false
		},
		{
			name: 'start_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d',
			index: true
		},
		{
			name: 'end_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d',
			index: true
		},
		{
			name: 'credentialization_notes',
			type: 'string'
		},
		{
			name: 'active',
			type: 'bool',
			store: false,
			convert: function(v, record){
				var now = new Date();

				return record.data.start_date <= now && record.data.end_date >= now
			}
		},
		{
			name: 'create_uid',
			type: 'int'
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'update_uid',
			type: 'int'
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
			read: 'Providers.getProviderCredentializations',
			create: 'Providers.addProviderCredentialization',
			update: 'Providers.updateProviderCredentialization',
			destroy: 'Providers.deleteProviderCredentialization'
		},
		reader: {
			root: 'data'
		}
	}
});