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

Ext.define('App.view.administration.practice.Practice', {
	extend: 'App.ux.RenderPanel',
	xtype: 'practicepanel',
	pageTitle: _('practice_settings'),
	requires: [
		'App.view.administration.practice.Facilities',
		'App.view.administration.practice.FacilityConfig',
		'App.view.administration.practice.Laboratories',
		'App.view.administration.practice.Pharmacies',
		'App.view.administration.practice.ProviderNumbers',
		'App.view.administration.practice.ReferringProviders',
//		'App.view.administration.practice.Specialties'
	],
	pageBody: [
		{
			xtype: 'tabpanel',
			activeTab: 0,
			items: [
				{
					xtype: 'pharmaciespanel'
				},
				{
					xtype: 'laboratoriespanel'
				},
				{
					xtype: 'insurancecompaniespanel'
				},
				{
					xtype: 'providersnumberspanel'
				},
				{
					xtype: 'referringproviderspanel'
				},
//				{
//					xtype: 'specialtiespanel'
//				},
				{
					xtype: 'facilitiespanel'
				},
				{
					xtype: 'facilityconfigpanel'
				}
			]
		}
	]
});
