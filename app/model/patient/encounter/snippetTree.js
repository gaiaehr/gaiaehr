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

Ext.define('App.model.patient.encounter.snippetTree', {
	extend: 'Ext.data.Model',
	table: {
		name: 'soap_snippets',
		comment: 'Snippet Tree'
	},
	fields: [
		{
			name: 'id',
			type: 'string'
		},
		{
			name: 'parentId',
			type: 'string',
			len: 20,
			index: true
		},
		{
			name: 'index',
			type: 'int'
		},
		{
			name: 'title',
			type: 'string',
			len: 80
		},
		{
			name: 'text',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'category',
			type: 'string',
			len: 50,
			index: true
		},
		{
			name: 'leaf',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Snippets.getSoapSnippetsByCategory',
			create: 'Snippets.addSoapSnippets',
			update: 'Snippets.updateSoapSnippets',
			destroy: 'Snippets.deleteSoapSnippets'
		}
	}
});