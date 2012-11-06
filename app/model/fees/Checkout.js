/*
 GaiaEHR (Electronic Health Records)
 Payments.js
 New payments Forms
 Copyright (C) 2012 Certun, Inc

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
Ext.define('App.model.fees.Checkout',
{
	extend : 'Ext.data.Model',
	fields : [
	{
		name : 'id',
		type : 'int'
	},
	{
		name : 'time',
		type : 'string'
	},
	{
		name : 'follow_up_facility',
		type : 'string'
	},
	{
		name : 'note',
		type : 'string'
	},
	{
		name : 'reminder',
		type : 'string'
	},
	{
		name : 'patient_name',
		type : 'string'
	},
	{
		name : 'encounter_number',
		type : 'int'
	},
	{
		name : 'transaction_facility',
		type : 'string'
	},
	{
		name : 'transaction_number',
		type : 'int'
	},
	{
		name : 'transaction_date',
		type : 'date',
		dateFormat : 'Y-m-d H:i:s'
	},
	{
		name : 'payment_amount',
		type : 'string'
	},
	{
		name : 'paying_entity',
		type : 'string'
	},
	{
		name : 'post_to_date',
		type : 'date',
		dateFormat : 'Y-m-d H:i:s'
	},
	{
		name : 'check_number',
		type : 'int'
	}],
	proxy :
	{
		type : 'direct',
		api :
		{
			read : Fees.getPaymentsBySearch
		},
		reader :
		{
			root : 'rows',
			totalProperty : 'totals'
		}
	}
}); 