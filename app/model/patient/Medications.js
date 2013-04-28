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

Ext.define('App.model.patient.Medications', {
	extend: 'Ext.data.Model',
	table: {
		name:'medications',
		comment:'Medications'
	},
	fields: [
        {name: 'id', type: 'int', comment: 'Medications ID'},
		{name: 'pid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'prescription_id', type: 'int'},
		{name: 'STR', type: 'string'},
		{name: 'CODE', type: 'string'},
		{name: 'RXCUI', type: 'string'},
		{name: 'ICDS', type: 'string'},
		{name: 'dose', type: 'string'},
		{name: 'take_pills', type: 'int'},
		{name: 'form', type: 'string'},
		{name: 'route', type: 'string'},
		{name: 'prescription_often', type: 'string'},
		{name: 'prescription_when', type: 'string'},
		{name: 'dispense', type: 'string'},
		{name: 'refill', type: 'string'},
		{name: 'create_date', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'begin_date', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'end_date', type:'date', dateFormat:'Y-m-d H:i:s'},

		{name: 'outcome', type: 'string'},
		{name: 'alert', type: 'bool'},
		{name: 'ocurrence', type: 'string'},
		{name: 'referred_by', type: 'string'},


	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getPatientMedications,
			create: Medical.addPatientMedications,
			update: Medical.updatePatientMedications
		}
	}
});

