/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.billing.VisitVoucherLine', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id',                    type: 'int'},
        {name: 'voucher_id',            type: 'int', comment:'Voucher'},
        {name: 'account_id',            type: 'int', comment:'Account'},
        {name: 'patient_id',            type: 'int', comment:'Patient'},
	    {name: 'move_line_id',          type: 'int', comment:'Journal Item'},
//        {name: 'company_id',            type: 'int', comment:'Company (Not Used)'},
//        {name: 'account_analytic_id',   type: 'int', comment:'Analytic Account (Not Used)'},

	    {name: 'reconcile',             type: 'bool', defaultValue:false, comment:'Full Reconcile'},

        {name: 'name',                  type: 'string', comment:'Description'},
	    {name: 'type',                  type: 'string', comment:'debit/credit'},

        {name: 'untax_amount',          type: 'float', comment:'Untax Amount'},
	    {name: 'amount_unreconciled',   type: 'float', comment:'Open Balance'},
	    {name: 'amount_original',       type: 'float', comment:'Original Amount'},
	    {name: 'amount',                type: 'float', comment:'Amount'}
    ],
    proxy : {
        type  : 'direct',
        api   : {
            read: AccBilling.getVisitVoucherLines
        }
    },
	associations: [
		{
			type: 'belongsTo',
			model: 'App.model.billing.VisitVoucher',
			foreignKey: 'voucher_id',
			getterName:'getVisitVoucher'
		}
	]
});