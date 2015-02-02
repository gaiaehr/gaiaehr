/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.DocumentsTemplates', {
	extend: 'Ext.data.Model',
	table: {
		name: 'documents_templates',
		comment: 'Documents Templates',
		data: 'App.data.administration.DocumentTemplates'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Documentation Templates ID'
		},
		{
			name: 'title',
			type: 'string',
			len: 50
		},
		{
			name: 'template_type',
			type: 'string',
			len: 50
		},
		{
			name: 'body',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'to be replace by created_date'
		},
		{
			name: 'created_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'updated_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'created_by_uid',
			type: 'int'
		},
		{
			name: 'updated_by_uid',
			type: 'int'
		}
	]
});