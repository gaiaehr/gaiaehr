/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */


Ext.define('App.store.administration.PreventiveCareMedications', {
	model: 'App.model.administration.PreventiveCareMedications',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : PreventiveCare.getGuideLineMedications,
			create: PreventiveCare.addGuideLineMedications,
			destroy: PreventiveCare.removeGuideLineMedications
		}
	},
	remoteSort: false,
	autoLoad  : false
});