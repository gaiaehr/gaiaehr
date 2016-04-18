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

Ext.define('App.model.administration.Phone', {
    extend: 'Ext.data.Model',
    table: {
        name: 'phones',
        comment: 'User/Contacts phones'
    },
    fields: [
        {
            name: 'id',
            type: 'int',
            comment: 'User Contact Phone ID'
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
            name: 'country_code',
            type: 'string',
            len: 4
        },
        {
            name: 'area_code',
            type: 'string',
            len: 5
        },
        {
            name: 'prefix',
            type: 'string',
            len: 5
        },
        {
            name: 'number',
            type: 'string',
            len: 10
        },
        {
            name: 'number_type',
            type: 'string'
        },
        {
            name: 'foreign_type',
            type: 'string'
        },
        {
            name: 'foreign_id',
            type: 'int'
        },
        {
            name: 'fullnumber',
            type: 'string',
            store: false,
            convert: function (v, record) {
                return Ext.String.trim(
                    record.data.country_code + ' ' +
                    record.data.area_code + '-' +
                    record.data.prefix + '-' +
                    record.data.number
                );
            }
        },
        {
            name: 'active',
            type: 'bool'
        }
    ]
});
