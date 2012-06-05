/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/19/12
 * Time: 1:01 PM
 */
Ext.define('App.model.navigation.Navigation', {
	extend   : 'Ext.data.Model',
	fields   : [
		{name: 'text', type: 'string'},
		{name: 'disabled', type: 'bool', defaultValue: false}
	],
	proxy    : {
		type: 'direct',
		api : {
			read: Navigation.getNavigation
		}
	}
});