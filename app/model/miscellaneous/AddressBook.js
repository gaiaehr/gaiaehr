/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

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

Ext.define('App.model.miscellaneous.AddressBook', {
    extend: 'Ext.data.Model',
    table: {
        name:'addressbook',
        comment:'Address Book'
    },
    fields: [
        {
            name: 'id',
            type: 'int',
            dataType: 'bigint',
            len: 20,
            primaryKey : true,
            autoIncrement : true,
            allowNull : false,
            store: true,
            comment: 'Address Book ID'
        },
        {
            name: 'username',
            type: 'string'
        },
        {
            name: 'password',
            type: 'string'
        },
        {
            name: 'authorized',
            type: 'string'
        },
        {
            name: 'info',
            type: 'string'
        },
        {
            name: 'source',
            type: 'int'
        },
        {
            name: 'fname',
            type: 'string'
        },
        {
            name: 'mname',
            type: 'string'
        },
        {
            name: 'lname',
            type: 'string'
        },
        {
            name: 'fullname',
            type: 'string'
        },
        {
            name: 'federaltaxid',
            type: 'string'
        },
        {
            name: 'federaldrugid',
            type: 'string'
        },
        {
            name: 'upin',
            type: 'string'
        },
        {
            name: 'facility',
            type: 'string'
        },
        {
            name: 'facility_id',
            type: 'int'
        },
        {
            name: 'see_auth',
            type: 'int'
        },
        {
            name: 'active',
            type: 'int'
        },
        {
            name: 'npi',
            type: 'string'
        },
        {
            name: 'title',
            type: 'string'
        },
        {
            name: 'specialty',
            type: 'string'
        },
        {
            name: 'billname',
            type: 'string'
        },
        {
            name: 'email',
            type: 'string'
        },
        {
            name: 'url',
            type: 'string'
        },
        {
            name: 'assistant',
            type: 'string'
        },
        {
            name: 'organization',
            type: 'string'
        },
        {
            name: 'valedictory',
            type: 'string'
        },
        {
            name: 'fulladdress',
            type: 'string'
        },
        {
            name: 'street',
            type: 'string'
        },
        {
            name: 'streetb',
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
            name: 'street2',
            type: 'string'
        },
        {
            name: 'streetb2',
            type: 'string'
        },
        {
            name: 'city2',
            type: 'string'
        },
        {
            name: 'state2',
            type: 'string'
        },
        {
            name: 'zip2',
            type: 'string'
        },
        {
            name: 'phone',
            type: 'string'
        },
        {
            name: 'fax',
            type: 'string'
        },
        {
            name: 'phonew1',
            type: 'string'
        },
        {
            name: 'phonew2',
            type: 'string'
        },
        {
            name: 'phonecell',
            type: 'string'
        },
        {
            name: 'notes',
            type: 'string'
        },
        {
            name: 'cal_ui',
            type: 'string'
        },
        {
            name: 'taxonomy',
            type: 'string'
        },
        {
            name: 'ssi_relayhealth',
            type: 'string'
        },
        {
            name: 'calendar',
            type: 'int'
        },
        {
            name: 'abook_type',
            type: 'string'
        },
        {
            name: 'pwd_expiration_date',
            type: 'string'
        },
        {
            name: 'pwd_history1',
            type: 'string'
        },
        {
            name: 'pwd_history2',
            type: 'string'
        },
        {
            name: 'default_warehouse',
            type: 'string'
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: AddressBook.getAddresses,
            create: AddressBook.addContact,
            update: AddressBook.updateAddress
        },
        reader: {
            totalProperty: 'totals',
            root: 'rows'
        }
    }
});