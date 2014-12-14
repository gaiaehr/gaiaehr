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

Ext.define('App.model.administration.HL7Server', {
	extend: 'Ext.data.Model',
	table: {
		name: 'hl7_servers'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'server_name',
			type: 'string'
		},
		{
			name: 'allow_messages',
			type: 'array',
			dataType: 'longtext'
		},
		{
			name: 'allow_ips',
			type: 'array',
			dataType: 'longtext'
		},
		{
			name: 'ip',
			type: 'string',
			len: 40
		},
		{
			name: 'port',
			type: 'string',
			len: 10
		},
		{
			name: 'allow_messages_string',
			type: 'string',
			store: false,
			convert: function(v, record){
				return Ext.isArray(record.data.allow_messages) ? record.data.allow_messages.join(', ') : record.data.allow_messages;
			}
		},
		{
			name: 'allow_ips_string',
			type: 'string',
			store: false,
			convert: function(v, record){
				return Ext.isArray(record.data.allow_ips) ? record.data.allow_ips.join(', ') : record.data.allow_ips;
			}
		},
		{
			name: 'token',
			type: 'string',
			len: 100
		},
		{
			name: 'online',
			type: 'bool',
			store: false
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'HL7Server.getServers',
			create: 'HL7Server.addServer',
			update: 'HL7Server.updateServer',
			destroy: 'HL7Server.deleteServer'
		},
		reader: {
			totalProperty: 'total',
			root: 'data'
		}
	}
});
