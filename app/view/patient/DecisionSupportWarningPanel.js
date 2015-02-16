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

Ext.define('App.view.patient.DecisionSupportWarningPanel', {
	extend: 'Ext.panel.Panel',
	xtype: 'decisionsupportwarningpanel',
	cls: 'decisionSupportWarning',
	header: false,
	collapsible: true,
	collapsed: true,
	hidden: true,
	margin: 0,
	dockedItems:[
		{
			xtype: 'toolbar',
			dock: 'right',
			ui: 'plain',
			items: [
				{
					xtype: 'button',
					icon: 'resources/images/icons/close_exit.png',
					itemId: 'DecisionSupportWarningPanelCloseBtn'
				}
			]
		}
	]
});