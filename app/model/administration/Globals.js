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

Ext.define('App.model.administration.Globals',
    {
        extend: 'Ext.data.Model',
        table: {
            name:'globals',
            comment:'Global Settings',
            data: 'App.data.administration.Globals'
        },
        fields: [
            {
                name: 'id',
                type: 'int',
                comment: 'Global Settings ID'
            },
            {
                name: 'gl_name',
                type: 'string',
                comment: 'Global Setting Unique Name'
            },
            {
                name: 'gl_value',
                type: 'string',
                comment: 'Global Setting Value'
            },
            {
                name: 'gl_index',
                type: 'int',
                comment: 'Global Setting Index'
            }
        ]
    });