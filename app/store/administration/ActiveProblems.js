/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.store.administration.ActiveProblems', {
	model: 'App.model.administration.ActiveProblems',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : Services.getActiveProblems,
			create: Services.addActiveProblems,
			destroy: Services.removeActiveProblems
		},
		reader     : {
			totalProperty: 'totals',
			root         : 'rows'
		}
	},
	autoLoad  : false
});