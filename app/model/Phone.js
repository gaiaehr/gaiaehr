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

Ext.define('App.model.administration.Phone',{
	extend : 'Ext.data.Model',
	table: {
		name: 'phones',
		comment: 'User/Contacts phones'
	},
	fields: [
		{name: 'id',                type: 'int'},
		{name: 'create_uid',        type: 'int',    comment:'create user ID'},
		{name: 'write_uid',         type: 'int',    comment:'update user ID'},
		{name: 'create_date',       type: 'date',   comment:'create date', dateFormat:'Y-m-d H:i:s'},
		{name: 'update_date',       type: 'date',   comment:'last update date', dateFormat:'Y-m-d H:i:s'},

	]
});
