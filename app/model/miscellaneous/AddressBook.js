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

Ext.define('App.model.miscellaneous.AddressBook', {
	extend: 'Ext.data.Model',
	table: {
		name: 'address_book',
		comment: 'Address Book'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'title',
			type: 'string',
			len: 10
		},
		{
			name: 'fname',
			type: 'string',
			len: 80,
			index: true
		},
		{
			name: 'mname',
			type: 'string',
			len: 80,
			index: true
		},
		{
			name: 'lname',
			type: 'string',
			len: 80,
			index: true
		},
		{
			name: 'email',
			type: 'string',
			len: 100,
			index: true
		},
		{
			name: 'direct_address',
			type: 'string',
			len: 150,
			index: true
		},
		{
			name: 'url',
			type: 'string',
			len: 150
		},
		{
			name: 'organization',
			type: 'string',
			len: 160
		},
		{
			name: 'street',
			type: 'string',
			len: 180
		},
		{
			name: 'street_cont',
			type: 'string',
			len: 180
		},
		{
			name: 'city',
			type: 'string',
			len: 80,
			index: true
		},
		{
			name: 'state',
			type: 'string',
			len: 100,
			index: true
		},
		{
			name: 'zip',
			type: 'string',
			len: 15,
			index: true
		},
		{
			name: 'country',
			type: 'string',
			len: 160
		},
		{
			name: 'phone',
			type: 'string',
			len: 20,
			index: true
		},
		{
			name: 'phone2',
			type: 'string',
			len: 20
		},
		{
			name: 'mobile',
			type: 'string',
			len: 20,
			comment: 'cell phone'
		},
		{
			name: 'fax',
			type: 'string',
			len: 20
		},
		{
			name: 'notes',
			type: 'string',
			len: 600
		},
		{
			name: 'fullname',
			type: 'string',
			store: false,
			convert: function(v, record){
				return record.data.fname + ' ' + record.data.mname + ' ' + record.data.lname;
			}
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AddressBook.getContacts',
			create: 'AddressBook.addContact',
			update: 'AddressBook.updateContact',
			destroy: 'AddressBook.destroyContact'
		},
		reader: {
			totalProperty: 'totals',
			root: 'data'
		}
	}
});