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
	items: [
		{
			xtype: 'miframe',
			style: 'background-color:white',
			autoMask: true,
			itemId: 'patientDocumentViewerFrame'
		}
	],
	tbar: [
		{
			xtype: 'patientEncounterCombo',
			itemId: 'PatientCcdPanelEncounterCmb',
			margin: '0 5 5 5',
			width: 300,
			fieldLabel: _('filter_encounter'),
			hideLabel: false,
			labelAlign: 'top'
		},
		'-',
		{
			xtype: 'checkboxgroup',
			fieldLabel: _('exclude'),
			// Arrange checkboxes into two columns, distributed vertically
			columns: 5,
			vertical: true,
			labelWidth: 60,
			itemId: 'PatientCcdPanelExcludeCheckBoxGroup',
			flex: 1,
			items: [
				{boxLabel: _('procedures'), name: 'exclude', inputValue: 'procedures'},
				{boxLabel: _('vitals'), name: 'exclude', inputValue: 'vitals'},
				{boxLabel: _('immunizations'), name: 'exclude', inputValue: 'immunizations'},
				{boxLabel: _('medications'), name: 'exclude', inputValue: 'medications'},
				{boxLabel: _('meds_administered'), name: 'exclude', inputValue: 'administered'},
				{boxLabel: _('plan_of_care'), name: 'exclude', inputValue: 'planofcare'},
				{boxLabel: _('problems'), name: 'exclude', inputValue: 'problems'},
				{boxLabel: _('allergies'), name: 'exclude', inputValue: 'allergies'},
				{boxLabel: _('social'), name: 'exclude', inputValue: 'social'},
				{boxLabel: _('results'), name: 'exclude', inputValue: 'results'}
			]
		},
		'-',
		{
			xtype: 'button',
			text: _('refresh'),
			margin: '0 0 5 0',
			itemId: 'viewCcdBtn',
			icon: 'resources/images/icons/refresh.png'
		},
		'-',
		{
			text: _('download'),
			margin: '0 0 5 0',
			itemId: 'exportCcdBtn',
			icon: 'resources/images/icons/download.png'
		},
		'-',
		{
			text: _('archive'),
			margin: '0 0 5 0',
			itemId: 'archiveCcdBtn',
			icon: 'resources/images/icons/archive_16.png'
		},
		'-',
		{
			text: 'Print',
			iconCls: 'icon-print',
			itemId: 'printCcdBtn'
		}
	]

});