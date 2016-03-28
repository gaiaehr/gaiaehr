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
Ext.define('App.model.administration.ReferringProviderGroup', {
	extend: 'Ext.data.Model',
	table: {
		name: 'referring_providers_groups'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'code',
			type: 'string',
			len: 40
		},
		{
			name: 'description',
			type: 'string',
			len: 180
		},
		{
			name: 'active',
			type: 'bool',
			index: true
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
			read: 'ReferringProviders.getReferringProviderGroups',
			create: 'ReferringProviders.addReferringProviderGroup',
			update: 'ReferringProviders.updateReferringProviderGroup'
		},
		reader: {
			root: 'data'
		}
	},
	hasMany: [
		{
			model: 'App.model.administration.ReferringProviderFacility',
			name: 'facilities',
			foreignKey: 'referring_provider_id'
		}
	]
});