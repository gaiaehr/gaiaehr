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

Ext.define('App.model.administration.HL7Message', {
	extend: 'Ext.data.Model',
	table: {
		name: 'hl7_messages',
		comment: 'hl7 messages data'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'msg_type',
			type: 'string',
			len: 15,
			index: true,
			comment: 'example VXU ADT OBX'
		},
		{
			name: 'message',
			type: 'string',
			dataType: 'mediumtext',
			comment: 'Original HL7 message'
		},
		{
			name: 'response',
			type: 'string',
			dataType: 'mediumtext',
			comment: 'HL7 acknowledgment message'
		},
		{
			name: 'foreign_facility',
			type: 'string',
			len: 60,
			comment: 'From or To external facility'
		},
		{
			name: 'foreign_application',
			type: 'string',
			len: 60,
			comment: 'From or To external Application'
		},
		{
			name: 'foreign_address',
			type: 'string',
			len: 180,
			comment: 'incoming or outgoing address'
		},
		{
			name: 'isOutbound',
			type: 'bool',
			comment: 'outbound 1, inbound 0'
		},
		{
			name: 'date_processed',
			type: 'date',
			comment: 'When Message was Received or Send',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'status',
			type: 'int',
			len: 1,
			index: true,
			comment: '0 = hold, 1 = processing, 2 = queue, 3 = processed, 4 = error'
		},
		{
			name: 'error',
			type: 'string',
			index: true,
			comment: 'connection error message'
		},
		{
			name: 'reference',
			type: 'string',
			len: 60,
			comment: 'Reference number or file name'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'HL7Messages.getMessages'
		},
		reader: {
			totalProperty: 'total',
			root: 'data'
		}
	}
});
