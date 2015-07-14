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

Ext.define('App.view.patient.windows.ArchiveDocument', {
	extend: 'Ext.window.Window',
	xtype: 'patientarchivedocumentwindow',
	draggable: false,
	modal: true,
	autoShow: true,
	title: _('archive_document'),
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
					name: 'id',
					hidden: true
				},
				{
					fieldLabel: _('title'),
					name: 'title'
				},
				{
					xtype: 'gaiaehr.combo',
					fieldLabel: _('type'),
					list: 102,
					name: 'docType',
					allowBlank: false
				},
				{
					xtype: 'checkbox',
					name: 'encrypted',
					fieldLabel: _('encrypted')
				},
				{
					xtype: 'textareafield',
					name: 'note',
					fieldLabel: _('notes')
				}
			]
		}
	],
	buttons: [
		{
			text: _('cancel'),
			handler: function(btn){
				btn.up('window').close();
			}
		},
		{
			text: _('archive'),
			itemId: 'archiveBtn'
		}
	]
});