/**
 * Created by JetBrains PhpStorm.
 * User: Plushy
 * Date: 3/26/12
 * Time: 10:18 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.store.fees.EncountersPayments', {
	extend    : 'Ext.data.Store',
	model     : 'App.model.fees.EncountersPayments',
    pageSize: 10,
	autoLoad  : false
});
