/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez
 * Date: 3/26/12
 * Time: 10:18 PM
 */
Ext.define('App.store.fees.Billing', {
	extend    : 'Ext.data.Store',
	model     : 'App.model.fees.Billing',
	autoLoad  : false
});