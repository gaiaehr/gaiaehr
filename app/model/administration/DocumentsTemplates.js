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

Ext.define('App.model.administration.DocumentsTemplates', {
	extend: 'Ext.data.Model',
	table: {
		name:'documentstemplates',
		comment:'Documents Templates',
        data: 'App.data.administration.DocumentTemplates'
	},
	fields: [
		{name: 'id', type:'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Documentation Templates ID'},
        {name: 'title', type:'string' },
		{name: 'body', type:'string' },
		{name: 'template_type', type:'string' },
		{name: 'date', type:'date', dateFormat:'Y-m-d H:i:s' }

	]
});