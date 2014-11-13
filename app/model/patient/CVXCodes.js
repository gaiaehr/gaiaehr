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


Ext.define('App.model.patient.CVXCodes', {
	extend: 'Ext.data.Model',
	table: {
		name: 'cvx_codes',
		comment: 'Immunizations  CVX'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Immunization ID'
		},
		{
			name: 'cvx_code',
			type: 'int',
			len: 10
		},
		{
			name: 'name',
			type: 'string'
		},
		{
			name: 'description',
			type: 'string',
			dataType: 'text'
		},

		{
			name: 'note',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'status',
			type: 'string',
			len: 25
		},
		{
			name: 'update_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Immunizations.getImmunizationsList'
		}
	}
});