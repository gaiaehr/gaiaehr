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

Ext.define('App.model.patient.Encounter', {
	extend : 'Ext.data.Model',
	table: {
		name:'encounters',
		comment:'Encounter Data'
	},
	fields : [
		{name: 'eid', type: 'int', comment: 'Encounter ID'},
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