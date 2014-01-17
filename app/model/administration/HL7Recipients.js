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

Ext.define('App.model.administration.HL7Recipients', {
	extend: 'Ext.data.Model',
	table: {
		name: 'hl7_recipients',
		comment: 'hl7 Recipients Data'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'recipient_type',
			type: 'string',
			comment: 'http or file'
		},
		{
			name: 'recipient_facility',
			type: 'string',
			len: 80,
			comment: 'Facility Name'
		},
		{
			name: 'recipient_address',
			type: 'string',
			len: 1000,
			comment: 'Facility Name'
		},
		{
			name: 'recipient_application',
			type: 'string',
			len: 80,
			comment: 'Application Name'
		},
		{
			name: 'recipient',
			type: 'string',
			comment: 'url or Directory Path'
		},
		{
			name: 'port',
			type: 'string',
			len: 10,
			comment: 'url port if any'
		},
		{
			name: 'isSecure',
			type: 'bool',
			comment: 'If secure then user secret_key'
		},
		{
			name: 'secret_key',
			type: 'string'
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: HL7Messages.getRecipients
		},
		reader: {
			totalProperty: 'total',
			root: 'data'
		}
	}
});
