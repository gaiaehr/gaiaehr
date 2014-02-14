/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.ListOptions', {
	extend: 'Ext.data.Model',
	table: {
		name: 'combo_lists_options',
		comment: 'Combo List Options'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'List Options ID'
		},
		{
			name: 'list_id',
			type: 'int',
			comment: 'List ID'
		},
		{
			name: 'option_value',
			type: 'string',
			comment: 'Value'
		},
		{
			name: 'option_name',
			type: 'string',
			comment: 'Name'
		},
		{
			name: 'code',
			type: 'string',
			len: 15,
			index: true,
			comment: 'value code'
		},
		{
			name: 'code_type',
			type: 'string',
			len: 10,
			comment: 'CPT4 LOINC SNOMEDCT ICD9 ICD10 RXNORM'
		},
		{
			name: 'seq',
			type: 'int',
			comment: 'Sequence'
		},
		{
			name: 'notes',
			type: 'string',
			comment: 'Notes'
		},
		{
			name: 'active',
			type: 'bool',
			comment: 'Active?'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Lists.getOptions',
			create: 'Lists.addOption',
			update: 'Lists.updateOption'
		}
	}
});