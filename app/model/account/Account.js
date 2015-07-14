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

Ext.define('App.model.account.Account', {
	extend: 'Ext.data.Model',
	table: {
		name: 'accaccount',
		comment: 'Account',
		data: 'App.data.account.Account'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'createUid', type: 'int'},
		{name: 'writeUid', type: 'int'},
		{name: 'createDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'writeDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},

		{name: 'parentId', type: 'int', comment: 'Parent Account'},
		{name: 'companyId', type: 'int', comment: 'Company'},
		{name: 'currencyId', type: 'int', comment: 'Account'},
		{name: 'level', type: 'int', comment: 'Level'},
		{name: 'accountType', type: 'int', comment: 'Account Type'},

		{name: 'active', type: 'bool', defaultValue: true, comment: 'Active?'},
		{name: 'reconcile', type: 'bool', defaultValue: false, comment: 'Allow Reconciliation?'},

		{name: 'name', type: 'string', comment: 'Name'},
		{name: 'code', type: 'string', comment: 'Code'},
		{name: 'shortcut', type: 'string', comment: 'Shortcut'},
		{name: 'note', type: 'string', comment: 'Internal Notes'},
		{name: 'currencyMode', type: 'string', comment: 'Outgoing Currencies Rate'},
		{name: 'type', type: 'string', comment: 'Internal Type'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AccAccount.getAccount',
			create: 'AccAccount.addAccount',
			update: 'AccAccount.updateAccount',
			destroy: 'AccAccount.destroyAccount'
		}
	},
	associations: [
		{
			type: 'hasOne',
			model: 'App.model.account.AccountType',
			foreignKey: 'accountType',
			setterName: 'setVoucher',
			getterName: 'getVoucher'
		}
	]
});