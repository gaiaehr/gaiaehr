/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.FloorPlans', {
	extend: 'Ext.data.Model',
	table: {
		name: 'floor_plans',
		comment: 'Floor Plans',
		data: 'App.data.administration.FloorPlans'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Floor Plans ID'
		},
		{
			name: 'title',
			type: 'string',
			comment: 'Floor Title'
		},
		{
			name: 'facility_id',
			type: 'int',
			comment: 'facility ID'
		},
		{
			name: 'active',
			type: 'bool',
			comment: 'Active Floor Plan?'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'FloorPlans.getFloorPlans',
			create: 'FloorPlans.createFloorPlan',
			update: 'FloorPlans.updateFloorPlan',
			destroy: 'FloorPlans.removeFloorPlan'
		}
	}
});