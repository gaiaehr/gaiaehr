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

Ext.define('App.view.scanner.Window', {
	extend: 'Ext.window.Window',
	xtype: 'scannerwindow',
	itemId: 'ScannerWindow',
	autoScroll: true,
	width: 1000,
	minHeight: 500,
	maxHeight: 700,
	closeAction: 'hide'
	,	title: _('scanner'),
	layout: {
		type: 'hbox'
	},
	items: [
		{
			xtype: 'image',
			flex: 1,
			id: 'ScannerImage',
			style: 'background-color:white',
			itemId: 'ScannerImage'
		}
	],
	buttons: [
		{
			text: _('edit'),
			enableToggle: true,
			itemId: 'ScannerImageEditBtn'
		},
		'-',
		{
			xtype: 'combobox',
			itemId: 'ScannerCombo',
			editable: false,
			queryMode: 'local',
			displayField: 'Name',
			valueField: 'Name',
			flex: 1,
			store: Ext.create('Ext.data.Store', {
				fields: [
					{
						name: 'Name',
						type: 'string'
					},
					{
						name: 'Version',
						type: 'string'
					},
					{
						name: 'Checked',
						type: 'string'
					}
				]
			})
		},
		{
			text: _('scan'),
			itemId: 'ScannerScanBtn'
		},
		'-',
		{
			text: _('ok'),
			itemId: 'ScannerOkBtn'
		}
	]
});