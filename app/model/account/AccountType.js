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

Ext.define('App.model.account.AccountType', {
	extend: 'Ext.data.Model',
	table: {
		name: 'accaccounttype',
		comment: 'Account',
		data: 'App.data.account.AccountType'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'createUid', type: 'int'},
		{name: 'createDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'writeUid', type: 'int'},
		{name: 'writeDate', type: 'date', dateFormat: 'Y-m-d H:i:s'},

		{name: 'closeMethod', type: 'string', comment: 'Name'},
		{name: 'note', type: 'string', comment: 'Code'},
		{name: 'code', type: 'string', comment: 'Shortcut'},
		{name: 'note', type: 'string', comment: 'Internal Notes'},
		{name: 'name', type: 'string', comment: 'Outgoing Currencies Rate'},
		{name: 'reportType', type: 'string', comment: 'Internal Type'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'AccAccount.getAccount',
			create: 'AccAccount.addAccount',
			update: 'AccAccount.updateAccount',
			destroy: 'AccAccount.destroyAccount'
		}
	}
});