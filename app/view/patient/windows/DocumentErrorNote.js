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

Ext.define('App.view.patient.windows.DocumentErrorNote', {
	extend: 'Ext.window.Window',
	xtype: 'patientdocumenterrornotewindow',
	draggable: false,
	modal: true,
	autoShow: true,
	title: _('error_note'),
	items: [
		{
			xtype: 'form',
			bodyPadding: 10,
			width: 400,
			items: [
				{

					xtype: 'textareafield',
					anchor: '100%',
					labelWidth: 70,
					labelAlign: 'top',
					name: 'error_note',
					allowBlank: false
				}
			]
		}
	],
	buttons: [
		{
			text: _('cancel'),
			itemId: 'DocumentErrorNoteCancelBtn',
			handler: function(btn){
				btn.up('window').close();
			}
		},
		{
			text: _('save'),
			itemId: 'DocumentErrorNoteSaveBtn'
		}
	]
});