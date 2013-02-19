/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.account.AccountType', {
	extend: 'Ext.data.Model',
	table: {
		name:'accaccount',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Account',
		data:'data/accaccount.json'
	},
	fields: [
		{name: 'id',                type: 'int'},
		{name: 'createUid',         type: 'int'},
		{name: 'createDate',        type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'writeUid',          type: 'int'},
		{name: 'writeDate',         type: 'date', dateFormat:'Y-m-d H:i:s'},

		{name: 'closeMethod',       type: 'string', comment:'Name'},
		{name: 'note',              type: 'string', comment:'Code'},
		{name: 'code',              type: 'string', comment:'Shortcut'},
		{name: 'note',              type: 'string', comment:'Internal Notes'},
		{name: 'name',              type: 'string', comment:'Outgoing Currencies Rate'},
		{name: 'reportType',        type: 'string', comment:'Internal Type'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: AccAccount.getAccount,
			create: AccAccount.addAccount,
			update: AccAccount.updateAccount,
			destroy: AccAccount.destroyAccount
		}
	}
});