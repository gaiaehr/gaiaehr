/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.Encounters', {
	extend : 'Ext.data.Model',
	fields : [
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'open_uid', type: 'string'},
		{name: 'close_uid', type: 'string'},
		{name: 'brief_description', type: 'string'},
		{name: 'visit_category', type: 'string'},
		{name: 'facility', type: 'string'},
		{name: 'billing_facility', type: 'string'},
		{name: 'sensitivity', type: 'string'},
		{name: 'service_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'close_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'onset_date', type: 'date', dateFormat:'Y-m-d H:i:s'}
	],
	proxy  : {
		type       : 'direct',
		api        : {
			read: Encounter.getEncounters
		},
		reader     : {
			type: 'json',
			root: 'encounter'
		}
	}
});