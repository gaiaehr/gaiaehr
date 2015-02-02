/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.Lists', {
	extend: 'Ext.data.Model',
	table: {
		name: 'combo_lists',
		comment: 'Combo List'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'List Options ID'
		},
		{
			name: 'title',
			type: 'string',
			comment: 'Title of the combo'
		},
		{
			name: 'active',
			type: 'bool',
			comment: 'Active?'
		},
		{
			name: 'in_use',
			type: 'bool',
			persist: false
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Lists.getLists',
			create: 'Lists.addList',
			update: 'Lists.updateList',
			destroy: 'Lists.deleteList'
		}
	}
});