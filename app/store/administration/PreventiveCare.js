/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */


Ext.define('App.store.administration.PreventiveCare', {
	model: 'App.model.administration.PreventiveCare',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : PreventiveCare.getGuideLinesByCategory,
			create: PreventiveCare.addGuideLine,
			update: PreventiveCare.updateGuideLine
		},
		reader     : {
			totalProperty: 'totals',
			root         : 'rows'
		},
		extraParams: {
			code_type: this.code_type,
			query    : this.query,
			active   : this.active
		}
	},
    autoSync  : true,
	remoteSort: true,
	autoLoad  : false
});