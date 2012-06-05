/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.poolarea.PoolArea', {
	extend: 'Ext.data.Store',
	requires: ['App.model.poolarea.PoolArea'],
	pageSize: 10,
	model   : 'App.model.poolarea.PoolArea'
});