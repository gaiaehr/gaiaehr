/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */


Ext.define('App.store.administration.ExternalDataLoads', {
	model: 'App.model.administration.ExternalDataLoads',
	extend: 'Ext.data.Store',
	constructor:function(config){
		var me = this;
		me.proxy = {
			type       : 'direct',
			api        : {
				read  : Codes.getCodeFiles
			},
			extraParams: {
				codeType: config.codeType
			}
		};
		me.callParent(arguments);
	},
	remoteSort: false,
	autoLoad  : false
});