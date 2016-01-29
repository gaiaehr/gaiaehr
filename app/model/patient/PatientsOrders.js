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

Ext.define('App.model.patient.PatientsOrders', {
	extend: 'Ext.data.Model',
	requires: [
		'App.model.patient.PatientsOrderResult'
	],
	table: {
		name: 'patient_orders',
		comment: 'Patients Orders'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int',
			index: true,
			comment: 'encounter id'
		},
		{
			name: 'pid',
			type: 'int',
			index: true,
			comment: 'patient ID'
		},
		{
			name: 'uid',
			type: 'int',
			comment: 'user ID who created the order'
		},
		{
			name: 'hl7_recipient_id',
			type: 'int',
			comment: 'laboratory id if electronic request'
		},
		{
			name: 'code',
			type: 'string',
			len: 25,
			comment: 'Order code'
		},
		{
			name: 'code_type',
			type: 'string',
			defaultValue: 'LOINC',
			len: 15,
			comment: 'Order code type LOINC'
		},
		{
			name: 'description',
			type: 'string',
			comment: 'Order Text Description'
		},
		{
			name: 'date_ordered',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'when the order was generated',
			index: true
		},
		{
			name: 'date_collected',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'when the results were collected',
			index: true
		},
		{
			name: 'priority',
			type: 'string',
			len: 25,
			comment: 'order priority',
			index: true
		},
		{
			name: 'status',
			type: 'string',
			len: 25,
			comment: 'order status',
			index: true
		},
		{
			name: 'order_type',
			type: 'string',
			comment: 'Order is radiology or laboratory.',
			index: true
		},
        {
            name: 'type',
            type: 'string',
            store: false,
            convert: function(v, record)
            {
                switch(record.data.order_type)
                {
                    case 'lab':
                        return _('laboratory');
                        break;
                    case 'rad':
                        return _('radiology');
                        break;
                }
            }
        },
		{
			name: 'note',
			type: 'string'
		},
        {
            name: 'void',
            type: 'boolean',
            defaultValue: false,
            comment: 'VOID the Order'
        },
        {
            name: 'void_comment',
            type: 'string',
            comment: 'VOID Comments',
            len: 100
        }
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Orders.getPatientOrders',
			create: 'Orders.addPatientOrder',
			update: 'Orders.updatePatientOrder',
			destroy: 'Orders.deletePatientOrder'
		},
		remoteGroup: false
	},
	associations: [
		{
			type: 'hasMany',
			model: 'App.model.patient.PatientsOrderResult',
			name: 'results',
			foreignKey: 'order_id'
		}
	]
});
