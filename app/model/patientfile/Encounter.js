/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patientfile.Encounter', {
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
		{name: 'start_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'close_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'onset_date', type: 'date', dateFormat:'Y-m-d H:i:s'}
	],
	proxy  : {
		type       : 'direct',
		api        : {
			read: Encounter.getEncounter,
			create: Encounter.createEncounter,
			update: Encounter.updateEncounter
		},
		reader     : {
			type: 'json',
			root: 'encounter'
		}
	},
    hasMany: [
        {model: 'App.model.patientfile.Vitals', name: 'vitals', primaryKey: 'eid'},
        {model: 'App.model.patientfile.ReviewOfSystems', name: 'reviewofsystems', primaryKey: 'eid'},
        {model: 'App.model.patientfile.ReviewOfSystemsCheck', name: 'reviewofsystemschecks', primaryKey: 'eid'},
        {model: 'App.model.patientfile.SOAP', name: 'soap', primaryKey: 'eid'},
        {model: 'App.model.patientfile.SpeechDictation', name: 'speechdictation', primaryKey: 'eid'}
    ]
});