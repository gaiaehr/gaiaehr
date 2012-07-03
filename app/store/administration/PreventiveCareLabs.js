/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */


Ext.define('App.store.administration.PreventiveCareLabs', {
	model: 'App.model.administration.PreventiveCareLabs',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : PreventiveCare.getGuideLineLabs,
			create: PreventiveCare.addGuideLineLabs,
			destroy: PreventiveCare.removeGuideLineLabs,
			update: PreventiveCare.updateGuideLineLabs
		}
	},
	remoteSort: false,
	autoLoad  : false
});