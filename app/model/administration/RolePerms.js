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
 * You should have received a copy of the GNU General Public  License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.RolePerms', {
	extend: 'Ext.data.Model',
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'perm_name',
			type: 'string'
		},
		{
			name: 'perm_key',
			type: 'string'
		},
		{
			name: 'perm_cat',
			type: 'string'
		},
		{
			name: 'role-front_office',
			type: 'bool'
		},
		{
			name: 'role-auditor',
			type: 'bool'
		},
		{
			name: 'role-clinician',
			type: 'bool'
		},
		{
			name: 'role-physician',
			type: 'bool'
		},
		{
			name: 'role-emergencyaccess',
			type: 'bool'
		},
		{
			name: 'role-administrator',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Roles.getRolePerms',
			update: 'Roles.updateRolePerm'
		},
		reader: {
			type: 'json',
			root: 'data'
		}

	}
});
