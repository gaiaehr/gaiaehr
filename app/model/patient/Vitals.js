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

Ext.define('App.model.patient.Vitals', {
	extend   : 'Ext.data.Model',
	table: {
		name:'vitals',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Vitals'
	},
	fields   : [
        {name: 'id', type: 'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Vital ID'},
		{name: 'pid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'uid', type: 'int'},
		{name: 'auth_uid', type: 'int'},
		{name: 'date', type: 'date', dateFormat:'Y-m-d H:i:s' },
		{name: 'weight_lbs', type: 'float', useNull:true},
		{name: 'weight_kg', type: 'float', useNull:true},
		{name: 'height_in', type: 'float', useNull:true},
		{name: 'height_cm', type: 'float', useNull:true},
		{name: 'bp_systolic', type: 'float', useNull:true},
		{name: 'bp_diastolic', type: 'float', useNull:true},
		{name: 'pulse', type: 'int', useNull:true},
		{name: 'respiration', type: 'int', useNull:true},
		{name: 'temp_f', type: 'float', useNull:true},
		{name: 'temp_c', type: 'float', useNull:true},
		{name: 'temp_location', type: 'string'},
		{name: 'oxygen_saturation', type: 'float', useNull:true},
		{name: 'head_circumference_in', type: 'float', useNull:true},
		{name: 'head_circumference_cm', type: 'float', useNull:true},
		{name: 'waist_circumference_in', type: 'float', useNull:true},
		{name: 'waist_circumference_cm', type: 'float', useNull:true},
		{name: 'bmi', type: 'int', useNull:true},
		{name: 'bmi_status', type: 'string', useNull:true},
		{name: 'other_notes', type: 'string'},
		{name: 'administer_by', type: 'string'},
		{name: 'authorized_by', type: 'string'},

		{name: 'bp_systolic_normal', type: 'int', defaultValue: 120 },
		{name: 'bp_diastolic_normal', type: 'int', defaultValue: 80 }

	],
	proxy    : {
		type       : 'direct',
		api        : {
			read: Encounter.getVitals,
			create: Encounter.addVitals,
			update: Encounter.updateVitals
		},
		reader     : {
			type: 'json'
		}
	},
    belongsTo: { model: 'App.model.patient.Encounter', foreignKey: 'eid' }

});