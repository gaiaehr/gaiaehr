/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.FloorPlans', {
	extend: 'Ext.data.Model',
	table: {
		name:'floorplans',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Floor Plans'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'title', type: 'string'}
	],
    proxy :
   	{
   		type : 'direct',
   		api :
   		{
   			read : FloorPlans.getFloorPlans,
   			create : FloorPlans.createFloorPlan,
   			update : FloorPlans.updateFloorPlan,
   			destroy : FloorPlans.removeFloorPlan
   		}
   	},
});