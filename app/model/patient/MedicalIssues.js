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

Ext.define('App.model.patient.MedicalIssues', {
	extend: 'Ext.data.Model',
	table: {
		name:'medicalissues',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Medical Issues'
	},
	fields: [
        {name: 'id', type: 'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Medical Issues ID'},
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'created_uid', type: 'int'},
		{name: 'updated_uid', type: 'int'},
		{name: 'create_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'code', type: 'string'},
		{name: 'code_text', type: 'string'},
		{name: 'begin_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'end_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'ocurrence', type: 'string'},
		{name: 'referred_by', type: 'string'},
		{name: 'outcome', type: 'string'},
        {name: 'alert', type: 'bool'}

	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getMedicalIssues,
			create: Medical.addMedicalIssues,
			update: Medical.updateMedicalIssues
		}
	}
});

