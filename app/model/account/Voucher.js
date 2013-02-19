/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.account.Voucher', {
	extend: 'Ext.data.Model',
	table: {
		name:'accvoucher',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Voucher / Receipt'
	},
	fields: [
		{name: 'id',                        type: 'int'},
		{name: 'createUid',                 type: 'int'},
		{name: 'createDate',                type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'writeUid',                  type: 'int'},
		{name: 'writeDate',                 type: 'date', dateFormat:'Y-m-d H:i:s'},

		{name: 'dateDue',                   type: 'date', dateFormat:'Y-m-d H:i:s', comment:'Due Date'},
		{name: 'date',                      type: 'date', dateFormat:'Y-m-d H:i:s', comment:'Date'},
		{name: 'encounterId',               type: 'int', comment:'Encounter'},
		{name: 'accountId',                 type: 'int', comment:'Account'},
		{name: 'journalId',                 type: 'int', comment:'Journal'},
		{name: 'moveId',                    type: 'int', comment:'Account Entry'},
//		{name: 'taxId',                     type: 'int', comment:'Tax ID'},
//		{name: 'companyId',                 type: 'int', comment:'Company'},
//		{name: 'partnerId',                 type: 'int', comment:'Partner'},
//		{name: 'paymentRateCurrencyId',     type: 'int', comment:'Payment Rate Currency (Not Used)'},
//		{name: 'writeOffAccId',             type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},
//		{name: 'analyticId',                type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},

		{name: 'active',                    type: 'bool', defaultValue:true, comment:'Active?'},
//		{name: 'preLine',                   type: 'bool', comment:'Previous Payments? (Not Used)'},
//		{name: 'isMultiCurrency',           type: 'bool', defaultValue:false, comment:'(Not Used)'},

		{name: 'comment',                   type: 'string', comment:'Comment'},
		{name: 'reference',                 type: 'string', comment:'Ref'},
		{name: 'number',                    type: 'string', comment:'Number'},
		{name: 'notes',                     type: 'string', mapping:'narration',  comment:'Notes'},
		{name: 'status',                    type: 'string', mapping:'state',      comment:'Status'},
//		{name: 'memo',                      type: 'string', mapping:'name', comment:'Memo (Not Used)'},
		{name: 'type',                      type: 'string', comment:'visit/product/office'},
//		{name: 'payment_option',            type: 'string', comment:'Payment Difference (Not Used)'},
//		{name: 'payNow',                    type: 'string', comment:'Payment (Not Used)'},

		{name: 'amount',                    type: 'float', defaultValue:0.00, comment:'Total Amount'}
//		{name: 'taxAmount',                 type: 'float', comment:'Tax Amount'},
//		{name: 'paymentRate',               type: 'float', comment:'Exchange Rate (Not Used)'}
//		{name: 'voucherlines',              type: 'auto', store:false} // use to get voucherlines in same call

	],
	proxy: {
		type: 'direct',
		api: {
			read: AccVoucher.getVoucher,
			create: AccVoucher.addVoucher,
			update: AccVoucher.updateVoucher,
			destroy: AccVoucher.destroyVoucher
		}
	},
	hasMany: [
		{model: 'App.model.account.VoucherLine', name: 'voucherlines', foreignKey: 'voucherId'}
	]
});