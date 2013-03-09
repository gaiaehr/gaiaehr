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

Ext.define('App.model.messages.Messages',
{
    extend : 'Ext.data.Model',
    table: {
        name:'messages',
        comment:'Messages'
    },
    fields : [
    {
        name : 'id',
        type : 'int',
        dataType: 'bigint',
        len: 20,
        primaryKey : true,
        autoIncrement : true,
        allowNull : false,
        store: true,
        comment: 'Messages ID'
    },
    {
        name : 'date',
        type : 'string'
    },
    {
        name : 'body',
        type : 'string'
    },
    {
        name : 'pid',
        type : 'string'
    },
    {
        name : 'patient_name',
        type : 'string'
    },
    {
        name : 'from_user',
        type : 'string'
    },
    {
        name : 'to_user',
        type : 'string'
    },
    {
        name : 'subject',
        type : 'string'
    },
    {
        name : 'facility_id',
        type : 'string'
    },
    {
        name : 'authorized',
        type : 'string'
    },
    {
        name : 'to_id',
        type : 'string'
    },
    {
        name : 'from_id',
        type : 'string'
    },
    {
        name : 'message_status',
        type : 'string'
    },
    {
        name : 'note_type',
        type : 'string'
    }]
});