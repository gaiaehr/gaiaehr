/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.CheckoutAlertArea', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'alert', type: 'string'},
        {name: 'alertType', type: 'int'}

	],
	proxy : {
		type: 'direct',
		api : {
			read: Encounter.checkoutAlerts
		}
	}
});