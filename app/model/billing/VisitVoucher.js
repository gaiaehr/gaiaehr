/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.billing.VisitVoucher', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id',                        type: 'int'},
		{name: 'dateDue',                   type: 'date', dateFormat:'Y-m-d H:i:s', comment:'Date'},
		{name: 'date',                      type: 'date', dateFormat:'Y-m-d H:i:s', comment:'Due Date'},
		{name: 'encounterId',               type: 'int', comment:'Encounter'},
		{name: 'accountId',                 type: 'int', comment:'Account'},
		{name: 'journalId',                 type: 'int', comment:'Journal'},
		{name: 'moveId',                    type: 'int', comment:'Account Entry'},
		{name: 'taxId',                     type: 'int', comment:'Tax ID'},
//		{name: 'companyId',                 type: 'int', comment:'Company'},
//		{name: 'partnerId',                 type: 'int', comment:'Partner'},
//		{name: 'paymentRateCurrencyId',     type: 'int', comment:'Payment Rate Currency (Not Used)'},
//		{name: 'writeOffAccId',             type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},
//		{name: 'analyticId',                type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},

		{name: 'active',                    type: 'bool', defaultValue:true, comment:'Active?'},
//		{name: 'preLine',                   type: 'bool', comment:'Previous Payments? (Not Used)'},
//		{name: 'isMultiCurrency',           type: 'bool', defaultValue:false, comment:'(Not Used)'},

		{name: 'comment',                   type: 'string', comment:'Comment'},
		{name: 'reference',                 type: 'string', comment:'Ref #'},
		{name: 'number',                    type: 'string', comment:'Number'},
		{name: 'notes',                     type: 'string', mapping:'narration',  comment:'Notes'},
		{name: 'status',                    type: 'string', mapping:'state',      comment:'Status'},
//		{name: 'memo',                      type: 'string', mapping:'name', comment:'Memo (Not Used)'},
//		{name: 'type',                      type: 'string', comment:'Default Type (Not Used)'},
//		{name: 'payment_option',            type: 'string', comment:'Payment Difference (Not Used)'},
//		{name: 'payNow',                    type: 'string', comment:'Payment (Not Used)'},

		{name: 'amount',                    type: 'float', defaultValue:0.00, comment:'Total Amount'}
//		{name: 'taxAmount',                 type: 'float', comment:'Tax Amount'},
//		{name: 'paymentRate',               type: 'float', comment:'Exchange Rate (Not Used)'}

	],
	proxy: {
		type: 'direct',
		api: {
			read: AccBilling.getVisitVoucher
		}
	},
	hasMany: [
		{model: 'App.model.billing.VisitInvoiceLine', name: 'VisitInvoiceLine.js'}
	]
});