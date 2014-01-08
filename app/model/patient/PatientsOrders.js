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
	table: {
		name:'patient_orders',
		comment:'Patients Orders'
	},
	fields: [
        {
	        name: 'id',
	        type: 'int',
	        comment: 'Patient Lab Order ID'
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
	        name: 'code',
	        type: 'string',
	        comment: 'Order code'
        },
        {
	        name: 'code_type',
	        type: 'string',
	        defaultValue: 'loinc',
	        comment: 'Order code type loinc'
        },
        {
	        name: 'description',
	        type: 'string',
	        comment: 'Order Text Description'
        },
        {
	        name: 'date_ordered',
	        type: 'date',
	        dateFormat:'Y-m-d H:i:s',
	        comment: 'when the order was generated'
        },
        {
	        name: 'date_collected',
	        type: 'date',
	        dateFormat:'Y-m-d H:i:s',
	        comment: 'when the results were collected'
        },
        {
	        name: 'priority',
	        type: 'string',
	        comment: 'order priority'
        },
        {
	        name: 'status',
	        type: 'string',
	        comment: 'order status'
        },
        {
	        name: 'order_type',
	        type: 'string',
	        comment: 'rad || lab || cvx || rx'
        },
        {
	        name: 'note',
	        type: 'string'
        },
        {
	        name: 'resultsDoc',
	        type: 'string',
	        comment: 'collected results document if any'
        }
	],
	proxy : {
		type: 'direct',
		api : {
			read:Orders.getPatientOrders,
			create:Orders.addPatientOrder,
			update:Orders.updatePatientOrder,
			destroy:Orders.deletePatientOrder
		},
		remoteGroup:false
	}
});