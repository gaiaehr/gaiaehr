/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.account.VoucherLine', {
    extend: 'Ext.data.Model',
	table: 'accvoucherline',
//	table: {
//		name:'accvoucher',
//		engine:'InnoDB',
//		autoIncrement:1,
//		charset:'utf8',
//		collate:'utf8_bin',
//		comment:'Voucher / Receipt'
//	},
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
        {name: 'id',                    type: 'int'},
	    {name: 'createUid',             type: 'int'},
	    {name: 'createDate',            type: 'date', dateFormat:'Y-m-d H:i:s'},
	    {name: 'writeUid',              type: 'int'},
	    {name: 'writeDate',             type: 'date', dateFormat:'Y-m-d H:i:s'},

        {name: 'voucherId',             type: 'int', comment: 'Voucher'},
        {name: 'accountId',             type: 'int', comment: 'Account'},
	    {name: 'moveLineId',            type: 'int', comment: 'Journal Item'},
//      {name: 'companyId',             type: 'int', comment:'Company (Not Used)'},
//      {name: 'accountAnalyticId',     type: 'int', comment:'Analytic Account (Not Used)'},

	    {name: 'reconcile',             type: 'bool', defaultValue: false, comment: 'Full Reconcile'},

	    {name: 'code',                  type: 'string', comment: 'COPAY/CPT/HCPCS/SKU codes'},
        {name: 'name',                  type: 'string', comment: 'Description'},
	    {name: 'type',                  type: 'string', comment: 'debit/credit'},

	    {name: 'amountUnreconciled',    type: 'float', comment: 'Open Balance'},
	    {name: 'amountUntax',           type: 'float', comment: 'Untax Amount'},
	    {name: 'amountOriginal',        type: 'float', comment: 'Default Amount'},
	    {name: 'amount',                type: 'float', comment: 'Amount'}
    ],
    proxy : {
        type  : 'direct',
        api   : {
            read: AccVoucher.getVoucherLines,
            create: AccVoucher.addVoucherLine,
            update: AccVoucher.updateVoucherLine,
            destroy: AccVoucher.destroyVoucherLine
        }
    },
	associations: [
		{
			type: 'belongsTo',
			model: 'App.model.account.Voucher',
			foreignKey: 'voucherId',
			setterName:'setVoucher',
			getterName:'getVoucher'
		}
	]
});