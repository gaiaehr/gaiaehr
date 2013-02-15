<?php

$text = <<<EOF
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
    fields: [
        {name: 'id',                    type: 'int'},
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
EOF;


$text =  preg_replace("(((/\*(.|\n)*\*/|//(.*))|Ext.define(.*) |\);)|(\"| |)proxy(.|\n)*},)", '', $text); //clean coments
print '1) '.$text;
print '<br>';
print '<br>';
$text =  preg_replace("/(,|{|\t|\n|\r|  )( |)(\w*):/", "$1$2\"$3\":", $text);
print '2) '.$text;
print '<br>';
print '<br>';
$text =  preg_replace("/([0-9]+\.[0-9]+)/", "\"$1\"", $text);
print '3) '.$text;
print '<br>';
print '<br>';
$text =  preg_replace("(')", '"', $text);
print '4) '.$text;
