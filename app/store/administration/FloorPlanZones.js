/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.administration.FloorPlanZones', {
	model: 'App.model.administration.FloorPlanZones',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : FloorPlans.getFloorPlanZones,
			create: FloorPlans.createFloorPlanZone,
			update: FloorPlans.updateFloorPlanZone
		}
	},
    autoSync  : true,
	autoLoad  : false
});