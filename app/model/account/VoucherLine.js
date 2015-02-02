/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.model.account.VoucherLine', {
	extend: 'Ext.data.Model',
	table: {
		name: 'accvoucherline',
		comment: 'Voucher / Receipt'
	},
	//	triggers:[
	//		{
	//			name: 'onVoucherLineDelete',
	//			time: 'after',
	//			event: 'delete',
	//			definition:'UPDATE accvoucher SET `status` = \'changed\' WHERE id = {voucherId} AND date = [new Date()]'
	//		},
	//		{
	//			name: 'onVoucherLineInsert',
	//			time: 'AFTER',
	//			event: 'INSERT',
	//			definition:'UPDATE accvoucher SET `status` = \'changed\' WHERE id = {voucherId}'
	//		}
	//	],
	fields: [
		{name: 'id', type: 'int'},
		{name: 'createUid', type: 'int'},
		{name: 'createDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'writeUid', type: 'int'},
		{name: 'writeDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},

		{name: 'voucherId', type: 'int', comment: 'Voucher'},
		{name: 'accountId', type: 'int', comment: 'Account'},
		{name: 'moveLineId', type: 'int', comment: 'Journal Item'},
		//      {name: 'companyId',             type: 'int', comment:'Company (Not Used)'},
		//      {name: 'accountAnalyticId',     type: 'int', comment:'Analytic Account (Not Used)'},

		{name: 'reconcile', type: 'bool', defaultValue: false, comment: 'Full Reconcile'},

		{name: 'code', type: 'string', comment: 'COPAY/CPT/HCPCS/SKU codes'},
		{name: 'name', type: 'string', comment: 'Description'},
		{name: 'type', type: 'string', comment: 'debit/credit'},

		{name: 'amountUnreconciled', type: 'float', comment: 'Open Balance'},
		{name: 'amountUntax', type: 'float', comment: 'Untax Amount'},
		{name: 'amountOriginal', type: 'float', comment: 'Default Amount'},
		{name: 'amount', type: 'float', comment: 'Amount'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AccVoucher.getVoucherLines',
			create: 'AccVoucher.addVoucherLine',
			update: 'AccVoucher.updateVoucherLine',
			destroy: 'AccVoucher.destroyVoucherLine'
		}
	},
	associations: [
		{
			type: 'belongsTo',
			model: 'App.model.account.Voucher',
			foreignKey: 'voucherId',
			setterName: 'setVoucher',
			getterName: 'getVoucher'
		}
	]
});