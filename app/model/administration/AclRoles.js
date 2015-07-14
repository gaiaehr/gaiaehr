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

Ext.define('App.model.administration.AclRoles', {
	extend: 'Ext.data.Model',
	table: {
		name: 'acl_roles',
		comment: 'Access Control List Roles'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'role_name',
			type: 'string'
		},
		{
			name: 'group_id',
			type: 'int',
			index: true
		},
		{
			name: 'seq',
			type: 'int',
			index: true
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
			read: 'ACL.getAclRoles',
			create: 'ACL.addAclRole',
			update: 'ACL.updateAclRole'
		},
		reader: {
			root: 'data'
		}
	}
});