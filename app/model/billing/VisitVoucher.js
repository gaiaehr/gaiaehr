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
		{name: 'date_due',                  type: 'date', dateFormat:'Y-m-d H:i:s', comment:'Date'},
		{name: 'date',                      type: 'date', dateFormat:'Y-m-d H:i:s', comment:'Due Date'},
		{name: 'patient_id',                type: 'int', comment:'Patient'},
		{name: 'account_id',                type: 'int', comment:'Account'},
		{name: 'journal_id',                type: 'int', comment:'Journal'},
		{name: 'move_id',                   type: 'int', comment:'Account Entry'},
		{name: 'tax_id',                    type: 'int', comment:'Tax ID'},
//		{name: 'company_id',                type: 'int', comment:'Company'},
//		{name: 'partner_id',                type: 'int', comment:'Partner'},
//		{name: 'payment_rate_currency_id',  type: 'int', comment:'Payment Rate Currency (Not Used)'},
//		{name: 'writeoff_acc_id',           type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},
//		{name: 'analytic_id',               type: 'int', comment:'Write-Off Analytic Acc (Not Used)'},

		{name: 'active',                    type: 'bool', defaultValue:true, comment:'Active?'},
//		{name: 'pre_line',                  type: 'bool', comment:'Previous Payments? (Not Used)'},
//		{name: 'is_multi_currency',         type: 'bool', defaultValue:false, comment:'(Not Used)'},

		{name: 'comment',                   type: 'string', comment:'Comment'},
		{name: 'reference',                 type: 'string', comment:'Ref #'},
		{name: 'number',                    type: 'string', comment:'Number'},
		{name: 'notes',                     type: 'string', mapping:'narration', comment:'Notes'},
		{name: 'status',                    type: 'string', mapping:'state',     comment:'Status'},
//		{name: 'memo',                      type: 'string', mapping:'name', comment:'Memo (Not Used)'},
//		{name: 'type',                      type: 'string', comment:'Default Type (Not Used)'},
//		{name: 'payment_option',            type: 'string', comment:'Payment Difference (Not Used)'},
//		{name: 'pay_now',                   type: 'string', comment:'Payment (Not Used)'},

		{name: 'amount',                    type: 'float', defaultValue:0.00, comment:'Total Amount'}
//		{name: 'tax_amount',                type: 'float', comment:'Tax Amount'},
//		{name: 'payment_rate',              type: 'float', comment:'Exchange Rate (Not Used)'}

	],
	proxy: {
		type: 'direct',
		api: {
			read: AccBilling.getVisitVoucherLines
		}
	},
	hasMany: [
		{model: 'App.model.billing.VisitInvoiceLine', name: 'VisitInvoiceLine.js'}
	]
});