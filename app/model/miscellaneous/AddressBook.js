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
			lan: 10
		},
		{
			name: 'fname',
			type: 'string',
			lan: 80
		},
		{
			name: 'mname',
			type: 'string',
			lan: 80
		},
		{
			name: 'lname',
			type: 'string',
			lan: 80
		},
		{
			name: 'email',
			type: 'string',
			lan: 80
		},
		{
			name: 'url',
			type: 'string'
		},
		{
			name: 'organization',
			type: 'string',
			lan: 160
		},
		{
			name: 'street',
			type: 'string',
			lan: 180
		},
		{
			name: 'street_cont',
			type: 'string',
			lan: 180
		},
		{
			name: 'city',
			type: 'string',
			lan: 80
		},
		{
			name: 'state',
			type: 'string',
			lan: 100
		},
		{
			name: 'zip',
			type: 'string',
			lan: 15
		},
		{
			name: 'country',
			type: 'string',
			lan: 160
		},
		{
			name: 'phone',
			type: 'string',
			lan: 20
		},
		{
			name: 'phone2',
			type: 'string',
			lan: 20
		},
		{
			name: 'mobile',
			type: 'string',
			lan: 20,
			comment: 'cell phone'
		},
		{
			name: 'fax',
			type: 'string',
			lan: 20
		},
		{
			name: 'notes',
			type: 'string',
			lan: 600
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