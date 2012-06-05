/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/19/12
 * Time: 1:03 PM
 */
Ext.define('App.store.navigation.Navigation', {
	extend  : 'Ext.data.TreeStore',
	requires: ['App.model.navigation.Navigation'],
	model   : 'App.model.navigation.Navigation'
});