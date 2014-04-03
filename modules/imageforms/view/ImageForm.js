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

Ext.define('Modules.imageforms.view.ImageForm', {
	extend: 'Ext.form.Panel',
	requires: [
		'Modules.imageforms.view.FormBackgroundImagesCombo'
	],
	style: 'float:left',
	margin: '0 5 5 0',
	resizable: true,
	maximizable: true,
	layout: 'absolute',
	frame: true,
	bodyStyle: {
		'background-color': 'white'
	},
	bodyBorder: true,
	items: [
		{
			xtype: 'box',
			x: 0,
			y: 0,
			action:'image',
			autoEl: {
				tag: 'canvas'
			}
		},
		{
			xtype: 'box',
			x: 0,
			y: 0,
			action:'drawing',
			autoEl: {
				tag: 'canvas'
			}
		},
	],

	dockedItems: [
		{
			xtype: 'toolbar',
			dock: 'top',
			items: [
				{
					xtype: 'imageformdefaultscombo',
					itemId: 'imageFormDefaultsCombo'
				},
				'->',
				{
					text: 'Upload Image',
					itemId: 'imageFormUploadBtn'
				},
				'-',
				{
					xtype: 'tool',
					type: 'close',
					itemId: 'imageFormRemoveBtn'
				}
			]
		},
		{
			xtype: 'toolbar',
			dock: 'bottom',
			ui: 'footer',
			items: [
				{
					xtype: 'button',
					icon: 'resources/images/icons/edit.png',
					enableToggle: true,
					itemId: 'imageFormEditBtn',
					toggleGroup: 'imageFormEditBtn'
				},
				{
					xtype: 'button',
					text: i18n('color'),
					itemId: 'imageFormColorBtn'
				},
				'->',
				{
					xtype: 'button',
					itemId: 'imageFormResetBtn',
					text: i18n('reset'),
					minWidth: 70
				},
				'-',
				{
					xtype: 'button',
					itemId: 'imageFormSaveBtn',
					text: i18n('save'),
					minWidth: 70
				}
			]
		},
		{
			xtype: 'toolbar',
			dock: 'bottom',
			padding: '5 0 0 0',
			items: [
				{
					xtype: 'textareafield',
					name: 'notes',
					grow: true,
					flex: 1,
					emptyText: i18n('notes')
				}
			]
		}
	]
});