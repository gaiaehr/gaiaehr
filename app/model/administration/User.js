/**
 GaiaEHR (Electronic Health Records)
 User.js
 User Model
 Copyright (C) 2012 Certun, inc.

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

Ext.define('App.model.administration.User', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id',               type: 'int',     dataType: 'int', primary: true, ai: true}, // these should be defaults for IDs
        {name: 'username',         type: 'string',  dataType: 'varchar', len: 25, allowNull: false},
        {name: 'password',         type: 'string',  dataType: 'blob', allowNull: false},
        {name: 'authorized',       type: 'bool',    dataType: 'tinyint', len:1, defaultValue:0},
        {name: 'active',           type: 'bool'},   //default values for 'bool' = dataType: 'tinyint', len:1, defaultValue:0
        {name: 'info',             type: 'string'},
        {name: 'source',           type: 'int'},
        {name: 'fname',            type: 'string'}, //default values for string = dataType: 'varchar', len: 255, allowNull: true, defaultValue:null
        {name: 'mname',            type: 'string'},
        {name: 'lname',            type: 'string'},
        {name: 'fullname',         type: 'string'},
        {name: 'federaltaxid',     type: 'string'},
        {name: 'federaldrugid',    type: 'string'},
        {name: 'upin',             type: 'string'},
        {name: 'facility_id',      type: 'int'},
        {name: 'see_auth',         type: 'bool'},
        {name: 'active',           type: 'bool'},
        {name: 'npi',              type: 'string'},
        {name: 'title',            type: 'string'},
        {name: 'specialty',        type: 'string'},
        {name: 'cal_ui',           type: 'string'},
        {name: 'taxonomy',         type: 'string'},
        {name: 'calendar',         type: 'bool'},
        {name: 'abook_type',       type: 'string'},
        {name: 'default_warehouse',type: 'string'},
        {name: 'role_id',          type: 'int'}
    ]
});
