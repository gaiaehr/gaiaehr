/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.Encounter', {
	extend : 'Ext.data.Model',
	table: {
		name:'encounter',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Encounter Data'
	},
	fields : [
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'open_uid', type: 'int'},
		{name: 'provider_uid', type: 'int'},
		{name: 'supervisor_uid', type: 'int'},
		{name: 'brief_description', type: 'string'},
		{name: 'visit_category', type: 'string'},
		{name: 'facility', type: 'string'},
		{name: 'billing_facility', type: 'string'},
		{name: 'priority', type: 'string'},
		{name: 'service_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'close_date', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'onset_date', type: 'date', dateFormat:'Y-m-d H:i:s'}
	],
    idProperty: 'eid',
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
	    // model,                                           name of association,            ID of owner model, foreignKey of association
        {model: 'App.model.patient.Vitals',                 name: 'vitals',                 primaryKey: 'eid', foreignKey: 'eid'},
        {model: 'App.model.patient.ReviewOfSystems',        name: 'reviewofsystems',        primaryKey: 'eid', foreignKey: 'eid'},
        {model: 'App.model.patient.ReviewOfSystemsCheck',   name: 'reviewofsystemschecks',  primaryKey: 'eid', foreignKey: 'eid'},
        {model: 'App.model.patient.SOAP',                   name: 'soap',                   primaryKey: 'eid', foreignKey: 'eid'},
        {model: 'App.model.patient.SpeechDictation',        name: 'speechdictation',        primaryKey: 'eid', foreignKey: 'eid'},
        {model: 'App.model.patient.HCFAOptions',            name: 'hcfaoptions',            primaryKey: 'eid', foreignKey: 'eid'}
    ]
//    associations: [
//        {type: 'hasOne',  model: 'App.model.patient.ReviewOfSystems', foreignKey: 'eid', getterName:'getReviewofsystems'},
//        {type: 'hasOne',  model: 'App.model.patient.ReviewOfSystemsCheck', getterName:'getReviewofsystemschecks'},
//        {type: 'hasOne',  model: 'App.model.patient.SOAP', foreignKey: 'eid', getterName:'getSoap'},
//        {type: 'hasOne',  model: 'App.model.patient.SpeechDictation', foreignKey: 'eid', getterName:'getSpeechdictation'},
//        {type: 'hasOne',  model: 'App.model.patient.HCFAOptions', foreignKey: 'eid', getterName:'getHcfaoptions'}
//    ]
});