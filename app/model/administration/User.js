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
			type: 'int',
			comment: 'User Account ID'
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
			comment: 'username'
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
			comment: 'title (Mr. Mrs.)'
		},
		{
			name: 'fname',
			type: 'string',
			comment: 'first name'
		},
		{
			name: 'mname',
			type: 'string',
			comment: 'middle name'
		},
		{
			name: 'lname',
			type: 'string',
			comment: 'last name'
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
			comment: 'pin number'
		},
		{
			name: 'npi',
			type: 'string',
			comment: 'National Provider Identifier'
		},
		{
			name: 'fedtaxid',
			type: 'string',
			comment: 'federal tax id'
		},
		{
			name: 'feddrugid',
			type: 'string',
			comment: 'federal drug id'
		},
		{
			name: 'notes',
			type: 'string',
			comment: 'notes'
		},
		{
			name: 'email',
			type: 'string',
			comment: 'email'
		},
		{
			name: 'specialty',
			type: 'string',
			comment: 'specialty'
		},
		{
			name: 'taxonomy',
			type: 'string',
			comment: 'taxonomy',
			defaultValue: '207Q00000X'
		},
		{
			name: 'warehouse_id',
			type: 'int',
			comment: 'default warehouse'
		},
		{
			name: 'facility_id',
			type: 'int',
			comment: 'default facility'
		},
		{
			name: 'role_id',
			type: 'int',
			comment: 'acl_user_roles relation'
		},
		{
			name: 'calendar',
			type: 'bool',
			comment: 'has calendar? 0=no 1=yes'
		},
		{
			name: 'authorized',
			type: 'bool'
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'User.getUsers',
			create: 'User.addUser',
			update: 'User.updateUser'
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
