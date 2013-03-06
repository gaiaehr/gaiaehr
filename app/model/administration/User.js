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

Ext.define( 'App.model.administration.User',
{
	extend : 'Ext.data.Model',
	table: {
		name:'users',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'User accounts'
	},
	fields : [
	{
		name : 'id',
		type : 'int',
		dataType : 'bigint',
		len: 20,
		primaryKey : true,
		autoIncrement : true,
		allowNull : false,
		store: true,
        comment: 'Users ID'
	},
	{
		name : 'username',
		type : 'string',
		dataType : 'varchar',
		len : 25,
		allowNull : false,
		store: true,
        comment: 'Username'
	},
	{
		name : 'password',
		type : 'string',
		dataType : 'blob',
		allowNull : false,
		store: true,
        comment: 'User Password'
	},
    {
        name: 'pwd_history1',
        type : 'string',
        dataType : 'blob',
        allowNull : false,
        store: true,
        comment: 'User Password History 1'
    },
    {
        name: 'pwd_history2',
        type : 'string',
        dataType : 'blob',
        allowNull : false,
        store: true,
        comment: 'User Password History 2'
    },
	{
		name : 'authorized',
		type : 'bool',
		dataType : 'tinyint',
        allowNull : true,
		len : 1,
		defaultValue : 0,
		store: true,
        comment: 'User is authorized'
	},
	{
		name : 'active',
		type : 'bool',
		store: true,
        comment: 'User is active'
	},
	{
		name : 'info',
		type : 'string',
		dataType: 'longtext',
        len: 255,
        allowNull : true,
		store: true,
        comment: 'Information'
	},
	{
		name : 'source',
		type : 'int',
		dataType : 'tinyint',
        allowNull : true,
		len: 4,
		store: true,
        comment: 'Source'
	},
	{
		name : 'fname',
		type : 'string',
		dataType : 'varchar',
		len: 255,
        allowNull : true,
		store: true,
        comment: 'First person name'
	}, 
	{
		name : 'mname',
		type : 'string',
		dataType : 'varchar',
        allowNull : true,
		len: 255,
		store: true,
        comment: 'Middle person name'
	},
	{
		name : 'lname',
		type : 'string',
		dataType : 'varchar',
        allowNull : true,
		len: 255,
		store: true,
        comment: 'Last person name'
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
        allowNull : true,
		len: 255,
        comment: 'Federal TAX Id'
	},
    {
        name: 'email',
        type: 'string',
        comment: 'User Email Address'
    },
	{
		name : 'federaldrugid',
		type : 'string',
        comment: 'Federal DRUG Id'
	},
	{
		name : 'upin',
		type : 'string',
        comment: 'UPIN'
	},
	{
		name : 'facility_id',
		type : 'int',
        comment: 'Facility ID'
	},
	{
		name : 'see_auth',
		type : 'bool',
        comment: 'See Authorization'
	},
	{
		name : 'npi',
		type : 'string',
        comment: 'NPI'
	},
	{
		name : 'title',
		type : 'string',
        comment: 'Title'
	},
	{
		name : 'specialty',
		type : 'string',
        comment: 'Speciality'
	},
	{
		name : 'cal_ui',
		type : 'string',
        comment: 'CAL UI'
	},
	{
		name : 'taxonomy',
		type : 'string',
        comment: 'Taxonomy'
	},
	{
		name : 'calendar',
		type : 'bool',
        comment: 'Calendar'
	},
	{
		name : 'abook_type',
		type : 'string',
        comment: 'Address Book Type'
	},
	{
		name : 'default_warehouse',
		type : 'string',
        comment: 'Default Warehouse'
	},
	{
		name : 'role_id',
		type : 'int',
        comment: 'Role ID'
	}],
    associations: [
    {
        type: 'hasOne',
        model: 'App.model.administration.AclUserRoles',
        primaryKey: 'role_id',
        foreignKey: 'id'
    }
    ]
} );
