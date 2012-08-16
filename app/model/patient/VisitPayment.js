/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 4/13/12
 * Time: 10:37 PM
 */

Ext.define('App.model.patient.VisitPayment', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'id', type: 'int'},
        {name: 'no', type: 'int'},
        {name: 'date', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'facility', type: 'string'},
        {name: 'received_from', type: 'string'},
        {name: 'amount', type: 'string'},
        {name: 'for_payment_of', type: 'string'},
        {name: 'paid_by', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'next_appointment', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'accounted_amount', type: 'string'},
        {name: 'payment_amount', type: 'string'},
        {name: 'balance_due', type: 'string'}
	],
    proxy : {
        type: 'direct',
        api : {
            read  : Encounter.Checkout
        },
        reader     : {
            type: 'json'
        }
    }
});