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

Ext.define('App.view.patient.Results', {
    extend: 'Ext.panel.Panel',
	xtype:'patientresultspanel',
	layout:'border',
	border:false,
	items:[
		{
			xtype: 'grid',
			region: 'center',
			action:'orders',
			store: Ext.create('App.store.patient.PatientsOrders', {
				remoteFilter:true
			}),
			tbar:[
				'->',
				{
					xtype:'combobox'
				}
			],
			split: true,
			columns: [
				{
					header: i18n('orders'),
					dataIndex: 'label',
					menuDisabled:true,
					resizable:false,
					flex: 1
				},
				{
					header: i18n('status'),
					dataIndex: 'status',
					menuDisabled:true,
					resizable:false,
					width: 60
				}
			]
		},
		{
			xtype: 'grid',
			action: 'results',
//			title: i18n('order_observations_results'),
			region: 'south',
			split: true,
//			height:350,
			tbar:[
				i18n('observations_results'),
				'->',
				{
					xtype:'button',
					text:i18n('view_document')
				}
			],
			columns:[
				{
					text:i18n('status'),
					menuDisabled:true,
					width:60
				},
				{
					text:i18n('name'),
					menuDisabled:true,
					flex:1
				},
				{
					text:i18n('value'),
					menuDisabled:true,
					width:60
				},
				{
					text:i18n('units'),
					menuDisabled:true,
					width:60
				},
				{
					text:i18n('range'),
					menuDisabled:true,
					width:60
				},
				{
					text:i18n('abnormal'),
					menuDisabled:true,
					width:75
				},
				{
					text:i18n('notes'),
					menuDisabled:true,
					flex:1
				}
			]
		}
	]
});