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

Ext.define('App.model.administration.LayoutTree', {
	extend: 'Ext.data.Model',
	fields: [
		{
			name: 'id',
			type: 'string'
		},
		{
			name: 'parentId',
			type: 'string'
		},
		{
			name: 'text',
			type: 'string'
		},
		{
			name: 'index',
			type: 'int'
		},
		{
			name: 'parentId',
			type: 'string'
		},
		{
			name: 'text',
			type: 'string'
		},
		{
			name: 'index',
			type: 'int'
		},
		{
			name: 'xtype',
			type: 'string'
		},
		{
			name: 'form_id',
			type: 'int'
		},
		{
			name: 'title',
			type: 'string'
		},
		{
			name: 'fieldLabel',
			type: 'string'
		},
		{
			name: 'emptyText',
			type: 'string'
		},
		{
			name: 'labelWidth',
			type: 'string'
		},
		{
			name: 'hideLabel',
			type: 'bool',
			useNull: true,
			defaultValue: null
		},
		{
			name: 'layout',
			type: 'string'
		},
		{
			name: 'width',
			type: 'string'
		},
		{
			name: 'height',
			type: 'string'
		},
		{
			name: 'anchor',
			type: 'string'
		},
		{
			name: 'margin',
			type: 'string'
		},
		{
			name: 'flex',
			type: 'string'
		},
		{
			name: 'collapsible',
			type: 'bool',
			useNull: true,
			defaultValue: null
		},
		{
			name: 'checkboxToggle',
			type: 'bool',
			useNull: true,
			defaultValue: null
		},
		{
			name: 'collapsed',
			type: 'bool',
			useNull: true,
			defaultValue: null
		},
		{
			name: 'inputValue',
			type: 'string'
		},
		{
			name: 'allowBlank',
			type: 'string'
		},
		{
			name: 'value',
			type: 'string'
		},
		{
			name: 'minLength',
			type: 'string'
		},
		{
			name: 'maxLength',
			type: 'string'
		},
		{
			name: 'maxValue',
			type: 'string'
		},
		{
			name: 'minValue',
			type: 'string'
		},
		{
			name: 'boxLabel',
			type: 'string'
		},
		{
			name: 'grow',
			type: 'bool',
			useNull: true,
			defaultValue: null
		},
		{
			name: 'growMin',
			type: 'string'
		},

		{
			name: 'growMax',
			type: 'string'
		},
		{
			name: 'increment',
			type: 'string'
		},
		{
			name: 'code',
			type: 'string'
		},
		{
			name: 'name',
			type: 'string'
		},
		{
			name: 'list_id',
			type: 'string'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'FormLayoutBuilder.getFormFieldsTree',
			create: 'FormLayoutBuilder.createFormField',
			update: 'FormLayoutBuilder.updateFormField',
			destroy: 'FormLayoutBuilder.removeFormField'
		}
	}
});