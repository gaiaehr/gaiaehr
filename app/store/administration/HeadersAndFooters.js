/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.store.administration.HeadersAndFooters', {
	model: 'App.model.administration.HeadersAndFooters',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : DocumentHandler.getHeadersAndFootersTemplates,
			create: DocumentHandler.addHeadersOrFootersTemplates,
			update: DocumentHandler.updateHeadersOrFootersTemplates
		}
	},
    autoSync: true,
	autoLoad: false

});