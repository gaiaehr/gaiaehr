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

Ext.define('App.model.administration.ReferringProvider', {
	extend: 'Ext.data.Model',
	table: {
		name: 'referring_providers'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Referring Provider ID'
		},
		{
			name: 'create_uid',
			type: 'int',
			comment: 'create user ID'
		},
		{
			name: 'update_uid',
			type: 'int',
			comment: 'update user ID'
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
		},
		{
			name: 'title',
			type: 'string',
			len: 10,
			comment: 'title (Mr. Mrs.)'
		},
		{
			name: 'fname',
			type: 'string',
			len: 80,
			comment: 'first name'
		},
		{
			name: 'mname',
			type: 'string',
			len: 80,
			comment: 'middle name'
		},
		{
			name: 'lname',
			type: 'string',
			len: 120,
			comment: 'last name'
		},
		{
			name: 'upin',
			type: 'string',
			len: 25,
			comment: 'Carrier Claim Referring Physician UPIN Number'
		},
		{
			name: 'lic',
			type: 'string',
			len: 25,
			comment: 'Licence Number'
		},
		{
			name: 'npi',
			type: 'string',
			len: 25,
			comment: 'National Provider Identifier'
		},
		{
			name: 'ssn',
			type: 'string',
			len: 25,
			comment: 'federal tax id'
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
			name: 'notes',
			type: 'string',
			comment: 'notes'
		},
		{
			name: 'email',
			type: 'string',
			len: 180,
			comment: 'email'
		},
		{
			name: 'direct_address',
			type: 'string',
			len: 180,
			comment: 'direct_address'
		},
		{
			name: 'phone_number',
			type: 'string',
			len: 25,
			comment: 'phone number'
		},
		{
			name: 'fax_number',
			type: 'string',
			len: 25,
			comment: 'fax number'
		},
		{
			name: 'cel_number',
			type: 'string',
			len: 25,
			comment: 'cell phone number'
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'ReferringProviders.getReferringProviders',
			create: 'ReferringProviders.addReferringProvider',
			update: 'ReferringProviders.updateReferringProvider'
		}
	}
});
