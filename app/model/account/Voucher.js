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

Ext.define('App.model.account.Voucher', {
	extend: 'Ext.data.Model',
	table: {
		name: 'accvoucher',
		comment: 'Voucher / Receipt'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'createUid', type: 'int'},
		{name: 'createDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'writeUid', type: 'int'},
		{name: 'writeDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},

		{name: 'dateDue', type: 'date', dateFormat: 'Y-m-d H:i:s', comment: 'Due Date'},
		{name: 'date', type: 'date', dateFormat: 'Y-m-d H:i:s', comment: 'Date'},
		{name: 'encounterId', type: 'int', comment: 'Encounter'},
		{name: 'accountId', type: 'int', comment: 'Account'},
		{name: 'journalId', type: 'int', comment: 'Journal'},
		{name: 'moveId', type: 'int', comment: 'Account Entry'},
		//		{name: 'taxId',                     type: 'int', comment:'Tax ID'},
		//		{name: 'companyId',                 type: 'int', comment:'Company'},
		//		{name: 'partnerId',                 type: 'int', comment:'Partner'},
		//		{name: 'paymentRateCurrencyId',     type: 'int', comment:'Payment Rate Currency (Not Used)'},
		//		{name: 'writeOffAccId',             type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},
		//		{name: 'analyticId',                type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},

		{name: 'active', type: 'bool', defaultValue: true, comment: 'Active?'},
		//		{name: 'preLine',                   type: 'bool', comment:'Previous Payments? (Not Used)'},
		//		{name: 'isMultiCurrency',           type: 'bool', defaultValue:false, comment:'(Not Used)'},

		{name: 'comment', type: 'string', comment: 'Comment'},
		{name: 'reference', type: 'string', comment: 'Ref'},
		{name: 'number', type: 'string', comment: 'Number'},
		{name: 'notes', type: 'string', mapping: 'narration', comment: 'Notes'},
		{name: 'status', type: 'string', mapping: 'state', comment: 'Status'},
		//		{name: 'memo',                      type: 'string', mapping:'name', comment:'Memo (Not Used)'},
		{name: 'type', type: 'string', comment: 'visit/product/office'},
		//		{name: 'payment_option',            type: 'string', comment:'Payment Difference (Not Used)'},
		//		{name: 'payNow',                    type: 'string', comment:'Payment (Not Used)'},

		{name: 'amount', type: 'float', defaultValue: 0.00, comment: 'Total Amount'}
		//		{name: 'taxAmount',                 type: 'float', comment:'Tax Amount'},
		//		{name: 'paymentRate',               type: 'float', comment:'Exchange Rate (Not Used)'}
		//		{name: 'voucherlines',              type: 'auto', store:false} // use to get voucherlines in same call

	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AccVoucher.getVoucher',
			create: 'AccVoucher.addVoucher',
			update: 'AccVoucher.updateVoucher',
			destroy: 'AccVoucher.destroyVoucher'
		}
	},
	hasMany: [
		{model: 'App.model.account.VoucherLine', name: 'voucherlines', foreignKey: 'voucherId'}
	]
});