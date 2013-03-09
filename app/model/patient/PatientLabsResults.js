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

Ext.define('App.model.patient.PatientLabsResults', {
	extend   : 'Ext.data.Model',
	table: {
		name:'patientlabsresults',
		comment:'Patient Labs Results'
	},
	fields   : [
        {name: 'id', type: 'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Patient Labs Results ID'},
		{name: 'pid', type: 'int'},
		{name: 'uid', type: 'int'},
		{name: 'auth_uid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'document_id', type: 'int'},
		{name: 'document_url'},
		{name: 'date', type: 'date', dateFormat:'Y-m-d H:s:i'},
		{name: 'data'},
		{name: 'columns'},
		{name: 'parent_id'}
	],
	proxy    : {
		type       : 'direct',
		api        : {
			read: Medical.getPatientLabsResults,
			create: Medical.addPatientLabsResult,
			update: Medical.updatePatientLabsResult,
			destroy: Medical.deletePatientLabsResult
		}
	}
});