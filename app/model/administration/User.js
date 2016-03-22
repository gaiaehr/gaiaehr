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

Ext.define('App.model.administration.User', {
	extend: 'Ext.data.Model',
	table: {
		name: 'users',
		comment: 'User accounts'
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
			name: 'providerCode',
			type: 'string',
			len: 40
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
			name: 'username',
			type: 'string',
			comment: 'username',
			len: 20,
			index: true
		},
		{
			name: 'password',
			type: 'string',
			comment: 'password',
			dataType: 'blob',
			encrypt: true
		},
		{
			name: 'pwd_history1',
			type: 'string',
			comment: 'first password history backwards',
			dataType: 'blob',
			encrypt: true
		},
		{
			name: 'pwd_history2',
			type: 'string',
			comment: 'second password history backwards',
			dataType: 'blob',
			encrypt: true
		},
		{
			name: 'title',
			type: 'string',
			comment: 'title (Mr. Mrs.)',
			len: 10
		},
		{
			name: 'fname',
			type: 'string',
			comment: 'first name',
			len: 80,
			index: true
		},
		{
			name: 'mname',
			type: 'string',
			comment: 'middle name',
			len: 80,
			index: true
		},
		{
			name: 'lname',
			type: 'string',
			comment: 'last name',
			len: 120,
			index: true
		},
		{
			name: 'fullname',
			type: 'string',
			comment: 'title full name',
			store: false
		},
		{
			name: 'shortname',
			type: 'string',
			comment: 'title and last name',
			store: false
		},
		{
			name: 'pin',
			type: 'string',
			comment: 'pin number',
			len: 10
		},
		{
			name: 'is_attending',
			type: 'bool',
			index: true
		},
		{
			name: 'npi',
			type: 'string',
			comment: 'National Provider Identifier',
			len: 15,
			index: true
		},
		{
			name: 'lic',
			type: 'string',
			len: 80
		},
		{
			name: 'ess',
			type: 'string',
			len: 80
		},
		{
			name: 'upin',
			type: 'string',
			len: 80
		},
		{
			name: 'fedtaxid',
			type: 'string',
			comment: 'federal tax id',
			len: 80
		},
		{
			name: 'feddrugid',
			type: 'string',
			comment: 'federal drug id',
			len: 80
		},
		{
			name: 'notes',
			type: 'string',
			len: 300
		},
		{
			name: 'email',
			type: 'string',
			len: 150,
			index: true
		},
		{
			name: 'phone',
			type: 'string',
			len: 80
		},
		{
			name: 'mobile',
			type: 'string',
			len: 80
		},
		{
			name: 'direct_address',
			type: 'string',
			comment: 'direct_address',
			len: 150,
			index: true
		},
		{
			name: 'specialty',
			type: 'array',
			comment: 'specialty',
			len: 80
		},
		{
			name: 'taxonomy',
			type: 'string',
			comment: 'taxonomy',
			defaultValue: '207Q00000X',
			len: 40,
			index: true
		},
		{
			name: 'warehouse_id',
			type: 'int',
			comment: 'default warehouse'
		},
		{
			name: 'facility_id',
			type: 'int',
			comment: 'default facility',
			index: true
		},
		{
			name: 'department_id',
			type: 'int',
			comment: 'default department',
			index: true
		},
		{
			name: 'role_id',
			type: 'int',
			comment: 'acl_user_roles relation'
		},
		{
			name: 'calendar',
			type: 'bool',
			comment: 'has calendar? 0=no 1=yes',
			index: true
		},
		{
			name: 'street',
			type: 'string',
			len: 55
		},
		{
			name: 'street_cont',
			type: 'string',
			len: 55
		},
		{
			name: 'city',
			type: 'string',
			len: 55
		},
		{
			name: 'state',
			type: 'string',
			len: 55
		},
		{
			name: 'postal_code',
			type: 'string',
			len: 15
		},
		{
			name: 'country_code',
			type: 'string',
			len: 15
		},
		{
			name: 'authorized',
			type: 'bool'
		},
		{
			name: 'active',
			type: 'bool',
			index: true
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'User.getUsers',
			create: 'User.addUser',
			update: 'User.updateUser'
		},
		reader: {
			root: 'data'
		}
	},
	hasMany: [
		{
			model: 'App.model.Phones',
			name: 'phones',
			primaryKey: 'id',
			foreignKey: 'use_id'
		},
		{
			model: 'App.model.Address',
			name: 'address',
			primaryKey: 'id',
			foreignKey: 'use_id'
		}
	]
});
