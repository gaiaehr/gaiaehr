/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.FloorPlanZones', {
	extend: 'Ext.data.Model',
	table: {
		name:'floorplanzones',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Floor Plan Zones'
	},
	fields: [
		{name: 'id', type: 'int'},
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