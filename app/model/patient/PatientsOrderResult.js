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

Ext.define('App.model.patient.PatientsOrderResult', {
	extend: 'Ext.data.Model',
	requires: [
		'App.model.patient.PatientsOrderObservation'
	],
	table: {
		name: 'patient_order_results',
		comment: 'Patients Results OBR'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int',
			index: true
		},
		{
			name: 'ordered_uid',
			type: 'int',
			index: true
		},
		{
			name: 'signed_uid',
			type: 'int',
			index: true
		},
		{
			name: 'order_id',
			type: 'int',
			index: true,
			comment: 'OBR-2'
		},
		{
			name: 'code',
			type: 'string',
			len: 40
		},
		{
			name: 'code_text',
			type: 'string',
			len: 150
		},
		{
			name: 'code_type',
			type: 'string',
			len: 20
		},
		{
			name: 'lab_order_id',
			type: 'string',
			len: 50,
			index: true,
			comment: 'OBR-3'
		},
		{
			name: 'lab_name',
			type: 'string',
			len: 150
		},
		{
			name: 'lab_address',
			type: 'string',
			len: 200
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			index: true
		},
		{
			name: 'result_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d',
			index: true
		},
		{
			name: 'observation_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d',
			index: true
		},
		{
			name: 'result_status',
			type: 'string',
			len: 40
		},
		{
			name: 'reason_code',
			type: 'string',
			len: 40
		},
		{
			name: 'specimen_code',
			type: 'string',
			len: 40
		},
		{
			name: 'specimen_text',
			type: 'string',
			len: 180
		},
		{
			name: 'specimen_code_type',
			type: 'string',
			len: 40
		},
		{
			name: 'specimen_notes',
			type: 'string'
		},
		{
			name: 'documentId',
			type: 'string',
			comment: 'this is the document or hl7 message id - example -> doc|123 or hl7|123',
			len: 40
		},
		{
			name: 'upload',
			type: 'string',
			store: false
		},
        {
            name: 'void',
            type: 'bool',
            defaultValue: false,
            comment: 'VOID the order'
        },
        {
            name: 'void_comment',
            type: 'string',
            comment: 'VOID comments',
            len: 100
        }
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Orders.getOrderResults',
			create: 'Orders.addOrderResults',
			update: 'Orders.updateOrderResults',
			destroy: 'Orders.deleteOrderResults'
		},
		remoteGroup: false
	},
	associations: [
		{
			type: 'hasMany',
			model: 'App.model.patient.PatientsOrderObservation',
			name: 'observations',
			foreignKey: 'result_id'
		},
		{
			type: 'belongsTo',
			model: 'App.model.patient.PatientsOrders',
			getterName: 'getOrder',
			setterName: 'setOrder',
			primaryKey: 'id',
			foreignKey: 'order_id'
		}
	]

});
