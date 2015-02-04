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


Ext.define('App.view.administration.HL7MessageViewer', {
	xtype: 'hl7messageviewer',
	extend: 'Ext.window.Window',
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	title: _('hl7_viewer'),
	width: 800,
	height: 450,
	bodyPadding: 10,
	maximizable: true,
	bodyStyle: 'background-color:white',
	defaults: {
		xtype: 'textareafield',
		labelAlign: 'top'
	},
	items: [
		{
			fieldLabel: _('message'),
			action: 'message',
			flex: 1
		},
		{
			fieldLabel: _('acknowledge'),
			action: 'acknowledge',
			flex: 1
		}
	]
}); 