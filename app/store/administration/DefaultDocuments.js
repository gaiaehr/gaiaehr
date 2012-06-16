/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.store.administration.DefaultDocuments', {
	model: 'App.model.administration.DefaultDocuments',
	extend: 'Ext.data.Store',
	proxy: {
		type       : 'direct',
		api        : {
			read  : DocumentHandler.getDefaultDocumentsTemplates,
			create: DocumentHandler.addDocumentsTemplates,
			update: DocumentHandler.updateDocumentsTemplates
		}
	},
    autoSync: true,
	autoLoad: false

});