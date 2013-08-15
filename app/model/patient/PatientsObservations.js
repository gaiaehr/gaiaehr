/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

Ext.define('App.model.patient.PatientsObservations', {
	extend: 'Ext.data.Model',
	table: {
		name:'patient_observations',
		comment:'Patients Observations'
	},
	fields: [
        {
	        name: 'id',
	        type: 'int',
	        comment: 'Results/Observations'
        },
        {
	        name: 'order_id',
	        type: 'int',
	        comment: 'Order ID'
        },
        {
	        name: 'code',
	        type: 'string',
	        comment: 'OBX 3'
        },
        {
	        name: 'code_type',
	        type: 'string',
	        comment: 'OBX 3'
        },
        {
	        name: 'value',
	        type: 'string',
	        comment: 'OBX 5'
        },
        {
	        name: 'units',
	        type: 'string',
	        comment: 'OBX 6'
        },
        {
	        name: 'reference_rage',
	        type: 'string',
	        comment: 'OBX 7'
        },
        {
	        name: 'probability',
	        type: 'string',
	        comment: 'OBX 9'
        },
        {
	        name: 'abnormal_flag',
	        type: 'string',
	        comment: 'OBX 8'
        },
        {
	        name: 'nature_of_abnormal',
	        type: 'string',
	        comment: 'OBX 10'
        },
        {
	        name: 'observation_result_status',
	        type: 'string',
	        comment: 'OBX 11'
        },
        {
	        name: 'date_rage_values',
	        type: 'date',
	        dateFormat:'Y-m-d H:i:s',
	        comment: 'OBX 12 Effective Date of Reference Range Values'
        },
        {
	        name: 'date_observation',
	        type: 'date',
	        dateFormat:'Y-m-d H:i:s',
	        comment: 'OBX 14'
        },
        {
	        name: 'observer',
	        type: 'string',
	        comment: 'OBX 16'
        },
		{
			name: 'date_analysis',
			type: 'date',
			dateFormat:'Y-m-d H:i:s',
			comment: 'OBX 19'
		},
        {
	        name: 'notes',
	        type: 'string',
	        comment: 'OBX NTE segments'
        },
        {
	        name: 'resultsDoc',
	        type: 'string',
	        comment: 'Reference to HL7 or document'
        }
	],
	proxy : {
		type: 'direct',
		api : {
			read:Orders.getPatientObservations,
			create:Orders.addPatientObservation,
			update:Orders.updatePatientObservation,
			destroy:Orders.deletePatientObservation
		},
		remoteGroup:false
	}
});