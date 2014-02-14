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

Ext.define('App.model.administration.Address', {
	extend: 'Ext.data.Model',
	table: {
		name: 'addresses',
		comment: 'Users/Contacts addresses'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'User/Contact address ID'
		},
		{
			name: 'create_uid',
			type: 'int',
			comment: 'create user ID'
		},
		{
			name: 'write_uid',
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
			name: 'line1',
			type: 'string'
		},
		{
			name: 'line2',
			type: 'string'
		},
		{
			name: 'city',
			type: 'string'
		},
		{
			name: 'state',
			type: 'string'
		},
		{
			name: 'zip',
			type: 'string'
		},
		{
			name: 'plus_four',
			type: 'string'
		},
		{
			name: 'country',
			type: 'string'
		},
		{
			name: 'address_type',
			type: 'string'
		},
		{
			name: 'foreign_id',
			type: 'int'
		},
		{
			name: 'fulladdress',
			type: 'string',
			store: false,
			convert: function(v, record){
				return Ext.String.trim(
					record.data.line1 + ' ' +
						record.data.line2 + ' ' +
						record.data.city + ', ' +
						record.data.state + ' ' +
						record.data.zip + '-' +
						record.data.plus_four + ' ' +
						record.data.country
				).replace('  ', ' ');
			}
		}
	]
});
