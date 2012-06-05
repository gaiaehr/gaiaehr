/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.fees.Checkout', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'id', type: 'int'},
        {name: 'time', type: 'string'},
        {name: 'follow_up_facility', type: 'string'},
        {name: 'note', type: 'string'},
        {name: 'reminder', type: 'string'},
        {name: 'patient_name', type: 'string'},
        {name: 'encounter_number', type: 'int'},
        {name: 'transaction_facility', type: 'string'},
        {name: 'transaction_number', type: 'int'},
        {name: 'transaction_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'payment_amount', type: 'string'},
        {name: 'paying_entity', type: 'string'},
        {name: 'post_to_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'check_number', type: 'int'}
	],
	proxy : {
		type: 'direct',
		api : {

		}
	}
});