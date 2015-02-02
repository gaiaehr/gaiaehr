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

Ext.define('App.model.administration.InsuranceNumber', {
	extend: 'Ext.data.Model',
	table: {
		name: 'insurance_numbers',
		comment: 'Insurance Numbers'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'provider_id',
			type: 'int'
		},
		{
			name: 'insurance_company_id',
			type: 'int'
		},
		{
			name: 'provider_id_text',
			type: 'string',
			store: false
		},
		{
			name: 'insurance_company_id_text',
			type: 'string',
			store: false
		},
		{
			name: 'provider_number',
			type: 'string',
			len: 20
		},
		{
			name: 'rendering_provider_number',
			type: 'string',
			len: 20
		},
		{
			name: 'group_number',
			type: 'string',
			len: 20
		},
		{
			name: 'provider_number_type',
			type: 'string',
			len: 4
		},
		{
			name: 'rendering_provider_number_type',
			type: 'string',
			len: 4
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Insurance.getInsuranceNumbers',
			create: 'Insurance.addInsuranceNumber',
			update: 'Insurance.updateInsuranceNumber'
		}
	}
});