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

Ext.define('App.view.patient.encounter.Snippets', {
	extend: 'Ext.window.Window',
	xtype: 'snippetswindow',
	requires: [

	],
	itemId: 'SnippetWindow',
	title: _('snippet'),
	closable: false,
	items: [
		{
			xtype: 'form',
			itemId: 'SnippetForm',
			fieldDefaults: {
				labelAlign: 'top',
				width: 600,
				margin: 5
			},
			items: [
				{
					xtype: 'textfield',
					fieldLabel: _('title'),
					name: 'title'
				},
				{
					xtype: 'textareafield',
					fieldLabel: _('snippet'),
					allowBlank: false,
					itemId: 'SnippetFormTextField',
					name: 'text'
				}
			]
		}
	],
	buttons:[
		{
			text: _('delete'),
			itemId: 'SnippetDeleteBtn'
		},
		'->',
		{
			text: _('cancel'),
			itemId: 'SnippetCancelBtn'
		},
		{
			text: _('save'),
			itemId: 'SnippetSaveBtn'
		}
	]
});