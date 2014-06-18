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

Ext.define('App.model.administration.CPT', {
	extend: 'Ext.data.Model',
	table: {
		name: 'cpt_codes'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'ConceptID',
			type: 'int',
			dataType: 'bigint'
		},
		{
			name: 'code',
			type: 'string',
			len: 50
		},
		{
			name: 'code_text',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'code_text_medium',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'code_text_short',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'code_type',
			type: 'string',
			store: false
		},
		{
			name: 'isRadiology',
			type: 'bool'
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'CPT.getCPTs',
			create: 'CPT.addCPT',
			update: 'CPT.updateCPT',
			destroy: 'CPT.deleteCPT'
		},
		reader: {
			root: 'data'
		}
	}
});