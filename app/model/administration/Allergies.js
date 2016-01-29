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

Ext.define('App.model.administration.Allergies', {
	extend: 'Ext.data.Model',
	table: {
		name: 'allergies',
		comment: 'Allergies'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'allergy',
			type: 'string',
			len: 500,
			comment: 'Allergy Name'
		},
		{
			name: 'allergy_term',
			type: 'string'
		},
		{
			name: 'allergy_code',
			type: 'string',
			len: 20
		},
		{
			name: 'allergy_code_type',
			type: 'string',
			len: 15
		},
		{
			name: 'allergy_type',
			type: 'string',
			len: 5,
			comment: 'PT = Preferred Term, SN = Systematic Name, SY = Synonym, CD = Code, TR = Trade'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Allergies.searchAllergiesData'
		},
		reader: {
			root: 'data'
		}
	}
});