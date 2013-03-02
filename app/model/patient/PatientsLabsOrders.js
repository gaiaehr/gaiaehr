/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.patient.PatientsLabsOrders', {
	extend: 'Ext.data.Model',
	table: {
		name:'patientslabsorders',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Patients Labs Orders'
	},
	fields: [
        { name: 'id', type: 'int' },
        { name: 'eid', type: 'int' },
        { name: 'pid', type: 'int' },
        { name: 'uid', type: 'int' },
        { name: 'description', type: 'string' },
        { name: 'date_created', type: 'date', dateFormat:'Y-m-d H:i:s' },
        { name: 'laboratory_id', type: 'int' },
        { name: 'document_id', type: 'int' },
        { name: 'order_type', type: 'string', defaultValue:'lab' },
        { name: 'order_items', type: 'auto' },
        { name: 'note', type: 'string' },
        { name: 'docUrl', type: 'string' }

	],
	proxy : {
		type: 'direct',
		api : {
			read:Orders.getPatientLabOrders,
			create:Orders.addPatientLabOrder,
			update:Orders.updatePatientLabOrder
		}
	}
});