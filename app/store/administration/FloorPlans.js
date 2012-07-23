/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.administration.FloorPlans', {
	model: 'App.model.administration.FloorPlans',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : FloorPlans.getFloorPlans,
			create: FloorPlans.createFloorPlan,
			update: FloorPlans.updateFloorPlan
		}
	},
    autoSync  : true,
	autoLoad  : false
});