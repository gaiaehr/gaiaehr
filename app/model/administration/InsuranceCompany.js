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

Ext.define('App.model.administration.InsuranceCompany', {
	extend: 'Ext.data.Model',
	table: {
		name: 'insurance_companies',
		comment: 'Insurance Companies'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'name',
			type: 'string',
			len: 120
		},
		{
			name: 'attn',
			type: 'string',
			len: 120
		},
		{
			name: 'cms_id',
			type: 'string',
			len: 80
		},
		{
			name: 'freeb_type',
			type: 'string',
			len: 80
		},
		{
			name: 'x12_receiver_id',
			type: 'string',
			len: 80
		},
		{
			name: 'x12_default_partner_id',
			type: 'string',
			len: 80
		},
		{
			name: 'alt_cms_id',
			type: 'string',
			len: 80
		},
		{
			name: 'address',
			type: 'string',
			len: 100
		},
		{
			name: 'address_cont',
			type: 'string',
			len: 80
		},
		{
			name: 'city',
			type: 'string',
			len: 80
		},
		{
			name: 'state',
			type: 'string',
			len: 80
		},
		{
			name: 'zip',
			type: 'string',
			len: 10
		},
		{
			name: 'plus_four',
			type: 'string',
			len: 10
		},
		{
			name: 'country',
			type: 'string',
			len: 80
		},
		{
			name: 'address_full',
			type: 'string',
			store: false,
			convert: function(v, record){
				return record.data.address + ' ' +  record.data.address_cont + ' ' +  record.data.city + ' ' +  record.data.state + ', ' +  record.data.zip;
			}
		},
		{
			name: 'phone_country_code',
			type: 'string',
			len: 10
		},
		{
			name: 'phone_area_code',
			type: 'string',
			len: 10
		},
		{
			name: 'phone_prefix',
			type: 'string',
			len: 10
		},
		{
			name: 'phone_number',
			type: 'string',
			len: 10
		},
		{
			name: 'phone_full',
			type: 'string',
			store: false,
			convert: function(v, record){
				return record.data.phone_country_code + ' ' + record.data.phone_area_code + '-' + record.data.phone_prefix + '-' + record.data.phone_number;
			}
		},
		{
			name: 'fax_country_code',
			type: 'string',
			len: 10
		},
		{
			name: 'fax_area_code',
			type: 'string',
			len: 10
		},
		{
			name: 'fax_prefix',
			type: 'string',
			len: 10
		},
		{
			name: 'fax_number',
			type: 'string',
			len: 10
		},
		{
			name: 'fax_full',
			type: 'string',
			store: false,
			convert: function(v, record){
				return record.data.fax_country_code + ' ' + record.data.fax_area_code + '-' + record.data.fax_prefix + '-' + record.data.fax_number;
			}
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Insurance.getInsuranceCompanies',
			create: 'Insurance.addInsuranceCompany',
			update: 'Insurance.updateInsuranceCompany'
		}
	}
});