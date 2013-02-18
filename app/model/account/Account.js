/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.account.Account', {
	extend: 'Ext.data.Model',
	table: 'accaccount',
//	table: {
//		name:'accaccount',
//		engine:'InnoDB',
//		autoIncrement:1,
//		charset:'utf8',
//		collate:'utf8_bin',
//		comment:'Account'
//	},
	fields: [
		{name: 'id',                type: 'int'},
		{name: 'createUid',         type: 'int'},
		{name: 'writeUid',          type: 'int'},
		{name: 'createDate',        type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'writeDate',         type: 'date', dateFormat:'Y-m-d H:i:s'},

		{name: 'parentId',          type: 'int', comment:'Parent Account'},
		{name: 'companyId',         type: 'int', comment:'Company'},
		{name: 'currencyId',        type: 'int', comment:'Account'},
		{name: 'level',             type: 'int', comment:'Level'},
		{name: 'accountType',       type: 'int', comment:'Account Type'},

		{name: 'active',            type: 'bool', defaultValue:true, comment:'Active?'},
		{name: 'reconcile',         type: 'bool', defaultValue:false, comment:'Allow Reconciliation?'},

		{name: 'name',              type: 'string', comment:'Name'},
		{name: 'code',              type: 'string', comment:'Code'},
		{name: 'shortcut',          type: 'string', comment:'Shortcut'},
		{name: 'note',              type: 'string', comment:'Internal Notes'},
		{name: 'currencyMode',      type: 'string', comment:'Outgoing Currencies Rate'},
		{name: 'type',              type: 'string', comment:'Internal Type'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: AccAccount.getAccount,
			create: AccAccount.addAccount,
			update: AccAccount.updateAccount,
			destroy: AccAccount.destroyAccount
		}
	},
	associations: [
		{
			type: 'hasOne',
			model: 'App.model.account.AccountType',
			foreignKey: 'accountType',
			setterName:'setVoucher',
			getterName:'getVoucher'
		}
	]
});