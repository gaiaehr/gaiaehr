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

Ext.define('App.model.patient.PatientCalendarEvents', {
	extend   : 'Ext.data.Model',
	table: {
		name:'patientcalendarevents',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Patient Calendar Events'
	},
	fields   : [
		{name: 'id', type: 'int'},
		{name: 'user_id', type: 'int'},
		{name: 'category', type: 'int'},
		{name: 'facility', type: 'int'},
		{name: 'billing_facillity', type: 'int'},
		{name: 'patient_id', type: 'int'},
		{name: 'title', type: 'string'},
		{name: 'status', type: 'string'},
		{name: 'start', type: 'date', dateFormat:'Y-m-d H:s:i'},
		{name: 'end', type: 'date', dateFormat:'Y-m-d H:s:i'},
		{name: 'data', type: 'string'},
		{name: 'rrule', type: 'string'},
		{name: 'loc', type: 'string'},
		{name: 'notes', type: 'string'},
		{name: 'url', type: 'string'},
		{name: 'ad', type: 'string'}
	],
	proxy    : {
		type       : 'direct',
		api        : {
			read: Calendar.getPatientFutureEvents
		}
	}
});