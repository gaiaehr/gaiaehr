/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

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

Ext.define('App.model.administration.FloorPlanZones', {
	extend: 'Ext.data.Model',
	table: {
		name:'floorplanzones',
		comment:'Floor Plan Zones'
	},
	fields: [
		{name: 'id', type: 'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Floor Plan Zones ID'},
		{name: 'floor_plan_id', type: 'int'},
		{name: 'title', type: 'string'},
		{name: 'type', type: 'string'},
		{name: 'bg_color', type: 'string', useNull:true},
		{name: 'border_color', type: 'string', useNull:true},
		{name: 'scale', type: 'string', defaultValue:'medium'},
		{name: 'width', type: 'int', useNull:true},
		{name: 'height', type: 'int', useNull:true},
		{name: 'x', type: 'int'},
		{name: 'y', type: 'int'},
		{name: 'show_priority_color', type: 'int'},
		{name: 'show_patient_preview', type: 'int'},
		{name: 'active', type: 'int'}
	],
    proxy :{
        type : 'direct',
        api :
        {
            read : FloorPlans.getFloorPlanZones,
            create : FloorPlans.createFloorPlanZone,
            update : FloorPlans.updateFloorPlanZone,
            destroy : FloorPlans.removeFloorPlanZone
        }
    }
});