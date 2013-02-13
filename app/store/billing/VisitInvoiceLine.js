/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */

Ext.define('App.store.billing.VisitInvoiceLine', {
	extend: 'Ext.data.Store',
	model     : 'App.model.billing.VisitInvoiceLine',
    remoteSort: false,
	autoLoad  : false
});