/**
 GaiaEHR (Electronic Health Records)
 Log.js
 Copyright (C) 2013 Certun

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

Ext.define('App.model.administration.LogsModel',
{
    extend: 'Ext.data.Model',
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'date',
            type: 'string'
        },
        {
            name: 'event',
            type: 'auto'
        },
        {
            name: 'user',
            type: 'string'
        },
        {
            name: 'facility',
            type: 'string'
        },
        {
            name: 'comments',
            type: 'string'
        },
        {
            name: 'user_notes',
            type: 'string'
        },
        {
            name: 'patient_id',
            type: 'string'
        },
        {
            name: 'success',
            type: 'int'
        },
        {
            name: 'checksum',
            type: 'string'
        },
        {
            name: 'crt_user',
            type: 'string'
        }
    ]
});