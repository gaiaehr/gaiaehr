 /**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientCalendarEvents', {
	extend   : 'Ext.data.Model',
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
		},
		reader     : {
			type: 'json'
		}
	}
});