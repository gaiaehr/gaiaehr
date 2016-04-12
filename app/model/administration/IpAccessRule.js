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

Ext.define('App.model.administration.IpAccessRule', {
	extend: 'Ext.data.Model',
	table: {
		name: 'ip_access_rules'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'ip',
			type: 'string',
			len: 40
		},
		{
			name: 'country_code',
			type: 'string',
			len: 50
		},
        {
            name: 'country_name',
            type: 'string',
            len: 100
        },
        {
            name: 'country',
            type: 'string',
            store: false,
            convert: function (v, record) {
                return record.data.country_name + '('+record.data.country_code+')';
            }
        },
		{
			name: 'rule',
			type: 'string',
			len: 10
		},
		{
			name: 'weight',
			type: 'int',
			index: true
		},
		{
			name: 'active',
			type: 'bool',
			index: true
		},
		{
			name: 'create_uid',
			type: 'int'
		},
		{
			name: 'update_uid',
			type: 'int'
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
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
			read: 'IpAccessRules.getIpAccessRules',
			create: 'IpAccessRules.createIpAccessRule',
			update: 'IpAccessRules.updateIpAccessRule'
		},
        writer: {
            writeAllFields: true
        }
	}
});
