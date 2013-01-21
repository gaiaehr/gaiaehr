/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.areas.PoolDropAreas', {
	extend: 'Ext.data.Store',
	requires: ['App.model.areas.PoolDropAreas'],
	pageSize: 10,
	model   : 'App.model.areas.PoolDropAreas'
});