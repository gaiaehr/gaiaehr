/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.store.administration.DocumentsTemplates', {
	model: 'App.model.administration.DocumentsTemplates',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : DocumentHandler.getDocumentsTemplates,
			create: DocumentHandler.addDocumentsTemplates,
			update: DocumentHandler.updateDocumentsTemplates
		}
	},
    autoSync: true,
	autoLoad: false

});