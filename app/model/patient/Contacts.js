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

Ext.define('App.model.patient.Contacts', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_contacts',
		comment: 'Patient Contacts'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
            index: true
		},
		{
			name: 'pid',
			type: 'int'
		},
        {
            name: 'uid',
            type: 'int'
        },
		{
			name: 'first_name',
			type: 'string',
			len: 100
		},
        {
            name: 'middle_name',
            type: 'string',
            len: 100
        },
        {
            name: 'last_name',
            type: 'string',
            len: 100
        },
        {
            name: 'fullname',
            type: 'string',
            store: false,
            convert: function(v, record){
                return record.data.first_name + ' ' + record.data.middle_name  + ' ' + record.data.last_name;
            }
        },
		{
			name: 'relationship',
			type: 'string',
            len: 20
		},
		{
			name: 'street_mailing_address',
			type: 'string',
            len: 200
		},
        {
            name: 'city',
            type: 'string',
            len: 70
        },
        {
            name: 'state',
            type: 'string',
            len: 70
        },
        {
            name: 'zip',
            type: 'string',
            len: 20
        },
        {
            name: 'country',
            type: 'string',
            len:20
        },
        {
            name: 'phone_use_code',
            type: 'string',
            len: 17
        },
        {
            name: 'phone_area_code',
            type: 'string',
            len: 17
        },
        {
            name: 'phone_local_number',
            type: 'string',
            len: 17
        },
        {
            name: 'phone_compiled',
            type: 'string',
            store: false,
            convert: function(v, record){
                return record.data.phone_use_code+
                '('+
                record.data.phone_area_code+
                ')'+
                record.data.phone_local_number;
            }
        },
        {
            name: 'contact_role',
            type: 'string',
            len: 17
        },
        {
            name: 'active',
            type: 'bool'
        },
        {
            name: 'created_date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s'
        }
	],
    idProperty: 'id',
	proxy: {
		type: 'direct',
		api: {
			read: 'PatientContacts.getContacts',
			create: 'PatientContacts.addContact',
			update: 'PatientContacts.updateContact'
		}
	}
});

