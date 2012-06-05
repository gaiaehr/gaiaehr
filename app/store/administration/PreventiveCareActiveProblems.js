/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */


Ext.define('App.store.administration.PreventiveCareActiveProblems', {
	model: 'App.model.administration.PreventiveCareActiveProblems',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : PreventiveCare.getGuideLineActiveProblems,
			create: PreventiveCare.addGuideLineActiveProblems,
			destroy: PreventiveCare.removeGuideLineActiveProblems
		}
	},
	remoteSort: false,
	autoLoad  : false
});