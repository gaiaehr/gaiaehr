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

Ext.define('App.view.areas.FloorPlan', {
	extend: 'App.ux.RenderPanel',
	itemId: 'FloorPlanPanel',
	pageTitle: _('area_floor_plan'),
	pageBody: [
		{
			xtype: 'panel',
			title: _('floor_plans'),
			layout: 'absolute',
			itemId: 'FloorPlanPatientZonePanel',
			tbar: [
				'->',
				{
					xtype: 'floorplanareascombo',
					fieldLabel: _('area'),
					labelWidth: 40,
					itemId: 'FloorPlanAreasCombo'
				}
			],
			tools: [
				{
					type: 'refresh',
					handler: function(){
						App.app.getController('areas.FloorPlan').setZones();
					}
				}
			]
		}
	]
});