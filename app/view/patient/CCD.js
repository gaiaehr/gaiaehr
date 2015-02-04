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

Ext.define('App.view.patient.CCD', {
	extend: 'Ext.panel.Panel',
	requires: [
		'Ext.ux.IFrame',
		'App.ux.ManagedIframe'
	],
	xtype: 'patientccdpanel',
	title: _('ccd'),
	columnLines: true,
	itemId: 'CcdPanel',
	layout: 'fit',
	items:[
		{
			xtype: 'miframe',
			style: 'background-color:white',
			autoMask: true,
			itemId: 'patientDocumentViewerFrame'
		}
	],
	tbar: [
		{
			xtype: 'button',
			text: _('view_ccr'),
			margin: '0 0 5 0',
			itemId: 'viewCcdBtn'
		},
		'-',
		{
			text: _('export_ccr'),
			margin: '0 0 5 0',
			itemId: 'exportCcdBtn'
		},
		'-',
		{
			xtype: 'container',
			layout: 'vbox',
			items: [
				{
					xtype: 'patientEncounterCombo',
					name: 'filterEncounter',
					margin: 5,
					fieldLabel: _('filter_encounter'),
					hideLabel: false
				}
			]
		},
		'-',
		{
			text: 'Print',
			iconCls: 'icon-print',
			handler: function(){
				//                           	trg.focus();
				//                           	trg.print();
			}
		}
	]

});