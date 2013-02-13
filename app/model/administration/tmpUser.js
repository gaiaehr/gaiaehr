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

Ext.define( 'App.model.administration.User',
{
	extend : 'Ext.data.Model',
	table: 'users',
	fields : [
	{
		name : 'id',
		type : 'int',
		dataType : 'bigint',
		len: 20,
		primaryKey : true,
		autoIncrement : true,
		allowNull : true,
		store: true
	},
	{
		name : 'username',
		type : 'string',
		dataType : 'varchar',
		len : 25,
		allowNull : false,
		store: true
	},
	{
		name : 'password',
		type : 'string',
		dataType : 'blob',
		allowNull : false,
		store: true
	},
	{
		name : 'authorized',
		type : 'bool',
		dataType : 'tinyint',
		len : 1,
		defaultValue : 0,
		store: true
	},
	{
		name : 'active',
		type : 'bool',
		dataType : 'tynyint',
		store: true
	}, 
	{
		name : 'info',
		type : 'string'
		dataType: 'longtex',
		store: true
	},
	{
		name : 'source',
		type : 'int',
		dataType : 'tynyint',
		len: 4,
		store: true
	},
	{
		name : 'fname',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	}, 
	{
		name : 'mname',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'lname',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'fullname',
		type : 'string',
		store: false
	},
	{
		name : 'federaltaxid',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'federaldrugid',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'upin',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'facility',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'facility_id',
		type : 'int',
		dataType : 'int',
		len: 11,
		store: true
	},
	{
		name : 'see_auth',
		type : 'bool',
		dataType : 'tynyint',
		len: 1,
		store: true
	},
	{
		name : 'npi',
		type : 'string',
		dataType : 'varchar',
		len: 15,
		store: true
	},
	{
		name : 'title',
		type : 'string',
		dataType : 'varchar',
		len: 30,
		store: true
	},
	{
		name : 'specialty',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'cal_ui',
		type : 'string',
		dataType : 'tynyint',
		len: 4,
		store: true
	},
	{
		name : 'taxonomy',
		type : 'string',
		dataType : 'varchar',
		len: 30,
		store: true
	},
	{
		name : 'calendar',
		type : 'bool',
		dataType : 'tynyint',
		len: 1,
		store: true
	},
	{
		name : 'abook_type',
		type : 'string',
		dataType : 'varchar',
		len: 31,
		store: true
	},
	{
		name : 'default_warehouse',
		type : 'string',
		dataType : 'varchar',
		len: 31,
		store: true
	},
	{
		name : 'role_id',
		type : 'int',
		store: false
	}]
} );
