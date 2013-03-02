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

Ext.define('App.model.patient.Disclosures', {
	extend: 'Ext.data.Model',
	table: {
		name:'disclosures',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Disclosures'
	},
	fields: [

        {name: 'id', type: 'int'},
        {name: 'eid', type: 'int'},
        {name: 'pid', type: 'int'},
        {name: 'uid', type: 'int'},
        {name: 'date', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'type', type: 'string'},
        {name: 'recipient', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'active', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Patient.getPatientDisclosures,
			create  : Patient.createPatientDisclosure,
			update  : Patient.updatePatientDisclosure
		}
	}
});

