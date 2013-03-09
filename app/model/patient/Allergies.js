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

Ext.define('App.model.patient.Allergies', {
	extend: 'Ext.data.Model',
	table: {
		name:'allergies',
		comment:'Patient Allergies'
	},
	fields: [
		{name: 'id', type: 'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Patient Allergies ID'},
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'created_uid', type: 'int'},
		{name: 'updated_uid', type: 'int'},
		{name: 'create_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'allergy_type', type: 'string'},
		{name: 'allergy', type: 'string'},
		{name: 'allergy1', type: 'string'},
		{name: 'allergy2', type: 'string'},
		{name: 'allergy_name', type: 'int'},
		{name: 'begin_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'end_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'reaction', type: 'string'},
		{name: 'reaction1', type: 'string'},
		{name: 'reaction2', type: 'string'},
		{name: 'reaction3', type: 'string'},
		{name: 'reaction4', type: 'string'},
		{name: 'location', type: 'string'},
		{name: 'severity', type: 'string'},
        {name: 'alert', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getPatientAllergies,
			create: Medical.addPatientAllergies,
			update: Medical.updatePatientAllergies
		}
	}
});