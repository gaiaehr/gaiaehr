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

Ext.define('App.model.administration.Logs',
{
    extend: 'Ext.data.Model',
    table: {
        name:'audit',
        comment:'Audit Logs'
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
            comment: 'Audit Log ID'
        },
        {
            name: 'created',
            type: 'date',
            comment: 'Created record date'
        },
        {
            name: 'modify',
            type: 'date',
            comment: 'Modify record date'
        },
        {
            name: 'deleted',
            type: 'date',
            comment: 'Delete or Hide record date'
        },
        {
            name: 'access',
            type: 'date',
            comment: 'Access record date'
        },
        {
            name: 'event',
            type: 'auto',
            comment: 'Event description'
        },
        {
            name: 'facility',
            type: 'string',
            comment: 'Witch facility'
        },
        {
            name: 'patient_id',
            type: 'string',
            comment: 'Patient ID'
        },
        {
            name: 'user_id',
            type: 'int',
            comment: 'User ID'
        },
        {
            name: 'checksum',
            type: 'string',
            comment: 'Checksum of SQL statement'
        },
        {
            name: 'crt_user',
            type: 'string'
        }
    ]
});