/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.patient.VisitPayment', {
	extend: 'Ext.data.Model',
	table: {
		name:'visitpayment',
		comment:'Visit Payment'
	},
	fields: [
        {name: 'id', type: 'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Visit Payment ID'},
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