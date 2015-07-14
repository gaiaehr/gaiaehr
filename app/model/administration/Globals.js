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

Ext.define('App.model.administration.Globals', {
	extend: 'Ext.data.Model',
	table: {
		name: 'globals',
		comment: 'Global Settings'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'gl_name',
			type: 'string',
			comment: 'Global Setting Unique Name or Key'
		},
		{
			name: 'gl_value',
			type: 'string',
			comment: 'Global Setting Value'
		},
		{
			name: 'gl_category',
			type: 'string',
			comment: 'Category'
		},
		{
			name: 'gl_index',
			type: 'int',
			comment: 'Global Setting Index'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Globals.getGlobals',
			update: 'Globals.updateGlobals'
		},
		remoteGroup: false
	}
});