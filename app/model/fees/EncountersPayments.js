/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 *
 * @namespace Fees.EncountersPayment
 *
 */

Ext.define('App.model.fees.EncountersPayments', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'id', type: 'int'},
        {name: 'paying_entity', type: 'string'},
        {name: 'payment_from', type: 'string'},
        {name: 'no', type: 'int'},
        {name: 'payment_method', type: 'string'},
        {name: 'pay_to', type: 'string'},
        {name: 'amount', type: 'string'},
        {name: 'date_from', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'date_to', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'note', type: 'string'}
	],
    proxy : {
        type: 'direct',
        api : {
            read  : Fees.getEncountersByPayment
        },
        reader     : {
            type: 'json'
        }
    }
});