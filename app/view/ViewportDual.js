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

Ext.define('App.view.ViewportDual', {
    extend: 'Ext.Viewport',
    requires: [


	    'App.view.patient.Immunizations',
	    'App.view.patient.Allergies',
	    'App.view.patient.ActiveProblems',
	    'App.view.patient.Medications',
	    'App.view.patient.SocialHistory',
	    'App.view.patient.Results',
	    'App.view.patient.Referrals'
    ],
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	itemId:'dualViewport',
	style: {
		'background-color': '#DFE8F6'
	},
	items:[
		{
			xtype: 'container',
			cls: 'RenderPanel-header',
			itemId: 'RenderPanel-header',
			region: 'north',
			height: 33
		},
		{
			xtype:'tabpanel',
			activeTab: 0,
			frame: true,
			margin: '0 5 5 5',
			flex: 1,
			items:[
				{
					xtype:'patientimmunizationspanel'
				},
				{
					xtype:'patientallergiespanel'
				},
				{
					xtype:'patientactiveproblemspanel'
				},
				{
					xtype:'patientmedicationspanel'
				},
				{
					xtype:'patientsocialhistorypanel'
				},
				{
					xtype:'patientresultspanel'
				},
				{
					xtype:'patientreferralspanel'
				}
			]
		}
	]
});
