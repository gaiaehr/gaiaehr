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

Ext.define('App.view.patient.windows.UploadDocument', {
	extend: 'Ext.window.Window',
	xtype: 'patientuploaddocumentwindow',
	draggable: false,
	modal: true,
	autoShow: true,
	title: i18n('new_document'),
	items: [
		{
			xtype: 'form',
			bodyPadding: 10,
			width: 400,
			defaults:{
				xtype: 'textfield',
				anchor: '100%',
				labelWidth: 70
			},
			items: [
				{
					fieldLabel: i18n('title'),
					name: 'title'
				},
				{
					xtype: 'gaiaehr.combo',
					fieldLabel: i18n('type'),
					list: 102,
					name: 'docType',
					allowBlank: false
				},
				{
					xtype: 'fileuploadfield',
					name: 'document',
					buttonText: i18n('select_a_file') + '...',
					fieldLabel: i18n('file'),
					allowBlank: false
				},
				{
					xtype: 'checkbox',
					name: 'encrypted',
					fieldLabel: i18n('encrypted')
				},
				{
					xtype: 'textareafield',
					name: 'note',
					fieldLabel: i18n('notes')
				}
			]
		}
	],
	buttons: [
		{
			text: i18n('cancel'),
			handler: function(btn){
				btn.up('window').close();
			}
		},
		{
			text: i18n('upload'),
			itemId: 'uploadBtn'
		}
	]
});