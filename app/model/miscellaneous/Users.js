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

Ext.define('App.model.miscellaneous.Users',
{
    extend : 'Ext.data.Model',
    table: {
        name:'users',
        comment:'Users'
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
        comment: 'Users ID'
    },
    {
        name : 'title',
        type : 'string'
    },
    {
        name : 'fname',
        type : 'string'
    },
    {
        name : 'mname',
        type : 'string'
    },
    {
        name : 'lname',
        type : 'string'
    },
    {
        name : 'username',
        type : 'string'
    },
    {
        name : 'password',
        type : 'string'
    },
    {
        name : 'oPassword',
        type : 'string'
    },
    {
        name : 'nPassword',
        type : 'string'
    },
    {
        name : 'facility_id',
        type : 'int'
    },
    {
        name : 'see_auth',
        type : 'string'
    },
    {
        name : 'taxonomy',
        type : 'string'
    },
    {
        name : 'federaltaxid',
        type : 'string'
    },
    {
        name : 'federaldrugid',
        type : 'string'
    },
    {
        name : 'upin',
        type : 'string'
    },
    {
        name : 'npi',
        type : 'string'
    },
    {
        name : 'specialty',
        type : 'string'
    }],
    proxy :
    {
        type : 'direct',
        api :
        {
            read : User.getCurrentUserData,
            update : User.getCurrentUserData
        }
    }
});